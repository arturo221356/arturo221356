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
                            url="/get/equipos"
                            pholder="Seleccionar Equipo"
                            v-model="item.equipo"
                            :state="getValidationState(validationContext)"
                            :equipo="true"
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
            </b-form>
        </validation-observer>

        <b-form-group :description="`Cantidad: ${items.length}`">
            <b-button
                block
                variant="primary"
                @click="sendData()"
                type="submit"
                :disabled="agregarButtonDisabled"
                >Agregar a Inventario</b-button
            >
        </b-form-group>

        <b-list-group class="d-flex justify-content-between">
            <b-list-group-item v-for="(articulo, index) in items" :key="index">
                {{ index + 1 }} :
                <strong>dfgdfgdf</strong>
                <small>fgdfgdf</small>
                <small>sdfsdfsdfsdf</small>

                <b-button
                    size="sm"
                    class="float-right"
                    variant="danger"
                    @click="eliminarSerie(articulo, index)"
                    >Eliminar</b-button
                >
            </b-list-group-item>
        </b-list-group>
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
            items: [],
        };
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        addItem() {
            this.items.unshift({ ...this.item });

            this.item.cantidad = 0;
        },
        //elimina la serie del array items
        eliminarSerie(item, index) {
            this.items.splice(index, 1);
        },
        sendData() {
            this.$emit("is-loading", true);

            const data = new FormData();

            data.append("data", JSON.stringify(this.items));

            data.set("equipo_id", this.item.id);

            data.set("cantidad", this.item.cantidad);

            data.set("inventario_id", this.item.inventario.id);

            axios
                .post("/otro", data, settings)
                .then((response) => {
                    this.$emit("errores", response.data.errors);

                    this.$emit("exitosos", response.data.success);

                    this.$emit("is-loading", false);
                })
                .catch((error) => {
                    alert(error);
                    this.$emit("is-loading", false);
                });

            this.items = [];

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
