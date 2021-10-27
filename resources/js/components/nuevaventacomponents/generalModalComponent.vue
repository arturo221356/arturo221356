<template>
    <div>
        <b-modal
            id="general-modal"
            hide-footer
            title="Venta general"
            @hide="hideGeneralModal"
        >
            <validation-observer ref="general" v-slot="{ handleSubmit }">
                <b-form @submit.prevent="handleSubmit(addGeneral)">
                    <ValidationProvider
                        name="nombre"
                        v-slot="validationContext"
                        rules="required"
                    >
                        <b-form-group label="Nombre" label-size="lg">
                            <b-input
                                placeholder="Nombre"
                                v-model="ventaGeneral.nombre"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            ></b-input>
                            <b-form-invalid-feedback>{{
                                validationContext.errors[0]
                            }}</b-form-invalid-feedback>
                        </b-form-group>
                    </ValidationProvider>
                    <ValidationProvider
                        name="Descripcion"
                        v-slot="validationContext"
                        rules="required"
                    >
                        <b-form-group label="Descripcion" label-size="lg">
                            <b-input
                                placeholder="Descripcion"
                                v-model="ventaGeneral.descripcion"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            ></b-input>
                            <b-form-invalid-feedback>{{
                                validationContext.errors[0]
                            }}</b-form-invalid-feedback>
                        </b-form-group>
                    </ValidationProvider>
                    <ValidationProvider
                        name="precio"
                        v-slot="validationContext"
                        rules="required|numeric"
                    >
                        <b-form-group label="Precio" label-size="lg">
                            <b-input
                                placeholder="Precio"
                                type="number"
                                v-model.number="ventaGeneral.precio"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            ></b-input>
                            <b-form-invalid-feedback>{{
                                validationContext.errors[0]
                            }}</b-form-invalid-feedback>
                        </b-form-group>
                    </ValidationProvider>

                    <b-form-group>
                        <b-button variant="success" type="submit"
                            >Agregar</b-button
                        >
                        <b-button @click="hideGeneralModal">Cancelar</b-button>
                    </b-form-group>
                </b-form>
            </validation-observer>
        </b-modal>
    </div>
</template>

<script>
export default {
    data() {
        return {
            ventaGeneral: {
                nombre: null,
                descripcion: null,
                precio: null,
                type: "generales",
            },
        };
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        open() {
             this.$root.$emit("bv::show::modal", "general-modal");
            
        },
        hideGeneralModal() {
            this.$root.$emit("bv::hide::modal", "general-modal");

            this.ventaGeneral = {
                nombre: null,
                descripcion: null,
                precio: null,
                type: "generales",
            };
        },
        addGeneral() {
            const general = {
                serie: this.ventaGeneral.nombre,
                precio: this.ventaGeneral.precio,
                descripcion: this.ventaGeneral.descripcion,
                type: "generales",
            };

            this.$emit("add-general", { ...general });


            this.hideGeneralModal();
        },
    },
};
</script>

<style></style>
