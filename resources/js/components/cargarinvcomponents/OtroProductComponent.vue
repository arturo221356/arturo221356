<template>
    <div>
        <validation-observer ref="observer" v-slot="{ handleSubmit }">
            <b-form @submit.prevent="handleSubmit(addItem)">
                <!-- grupo para inventario -->
                <ValidationProvider v-slot="validationContext" rules="required">
                    <b-form-group
                        label="Inventario"
                        label-for="select-inventario"
                        label-size="lg"
                    >
                        <select-general
                            url="/inventario"
                            pholder="Seleccionar Inventario"
                            query="App\Sucursal"
                            
                            v-model="item.inventario"
                            :state="getValidationState(validationContext)"
                        >
                        </select-general>
                        <b-form-invalid-feedback
                            ><b-form-invalid-feedback>{{
                                validationContext.errors[0]
                            }}</b-form-invalid-feedback></b-form-invalid-feedback
                        >
                    </b-form-group>
                </ValidationProvider>
                <!-- valida que el producto sea 1 imei  -->
                <ValidationProvider v-slot="validationContext" rules="required">
                    <b-form-group label="Accesorio" label-size="lg">
                        <select-general
                            url="/get/otros"
                            pholder="Seleccionar Accesorio"
                            :accesorio="true"
                            v-model="item.accesorio"
                            :state="getValidationState(validationContext)"
                        ></select-general>

                        <b-form-invalid-feedback
                            ><b-form-invalid-feedback>{{
                                validationContext.errors[0]
                            }}</b-form-invalid-feedback></b-form-invalid-feedback
                        >
                    </b-form-group>
                </ValidationProvider>

                <ValidationProvider
                    v-slot="validationContext"
                    rules="required|numeric"
                >
                    <b-form-group label="Cantidad" label-size="lg">
                        <b-form-input
                            autocomplete="off"
                            v-model="item.cantidad"
                            type="number"
                            :state="getValidationState(validationContext)"
                            placeholder="Ingresa la cantidad"
                        ></b-form-input>

                        <b-form-invalid-feedback
                            ><b-form-invalid-feedback>{{
                                validationContext.errors[0]
                            }}</b-form-invalid-feedback></b-form-invalid-feedback
                        >
                    </b-form-group>
                </ValidationProvider>

                <b-form-group>
                    <b-button
                        block
                        variant="primary"
                        type="submit"
                        :disabled="agregarButtonDisabled"
                        >Agregar a Inventario</b-button
                    >
                </b-form-group>
            </b-form>
        </validation-observer>
    </div>
</template>

<script>
export default {
    data() {
        return {
            item: {
                accesorio: null,
                inventario: null,
                cantidad: 0,
            },
        };
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },

        addItem() {
            this.$emit("is-loading", true);

            const data = new FormData();

            data.set("accesorio_id", this.item.accesorio.id);

            data.set("cantidad", this.item.cantidad);

            data.set("inventario_id", this.item.inventario.id);

            axios
                .post("/add/otros", data)
                .then((response) => {
                     this.$bvToast.toast(response.data, {
                        title: "Exito",
                        autoHideDelay: 5000,
                        variant: "success",
                    });
                    this.item.cantidad = 0;
                    this.$emit("is-loading", false);
                    
                })
                .catch((error) => {
                    
                    this.$bvToast.toast(error.response.data, {
                        title: "Error",
                        autoHideDelay: 5000,
                        variant: "danger",
                    });
                    this.$emit("is-loading", false);
                });

            this.file = null;
        },
    },
    computed: {
        agregarButtonDisabled() {
            // if (this.items.length < 1) {
            //     return true;
            // } else {
            //     return false;
            // }
            return false;
        },
    },
};
</script>

<style></style>
