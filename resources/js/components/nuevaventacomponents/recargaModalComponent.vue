<template>
    <div>
        <b-modal id="recarga-modal" hide-footer @hide="hideRecargaModal">
            <validation-observer ref="observer" v-slot="{ handleSubmit }">
                <b-form @submit.prevent="handleSubmit(addRecarga)">
                    <ValidationProvider
                        name="numero"
                        v-slot="validationContext"
                        rules="required|digits:10|numeric"
                    >
                        <b-form-group label="Numero" label-size="lg">
                            <b-input
                                placeholder="Insertar Numero"
                                v-model="newRecarga.dn"
                                type="number"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            ></b-input>
                            <b-form-invalid-feedback>{{
                                validationContext.errors[0]
                            }}</b-form-invalid-feedback>
                        </b-form-group>
                    </ValidationProvider>

                    <ValidationProvider
                        name="compañia"
                        v-slot="validationContext"
                        rules="required"
                    >
                        <b-form-group label="Compañia" label-size="lg">
                            <select-general
                                url="/get/companies"
                                v-model="newRecarga.company"
                                :state="getValidationState(validationContext)"
                            >
                            </select-general>
                            <b-form-invalid-feedback>{{
                                validationContext.errors[0]
                            }}</b-form-invalid-feedback>
                        </b-form-group>
                    </ValidationProvider>

                    <ValidationProvider
                        name="recarga"
                        v-slot="validationContext"
                        rules="required"
                    >
                        <b-form-group
                            v-if="newRecarga.company"
                            label="Recarga"
                            label-size="lg"
                        >
                            <select-general
                                url="/get/recargas"
                                :query="newRecarga.company.id"
                                v-model="newRecarga.recarga"
                                :state="getValidationState(validationContext)"
                            >
                            </select-general>
                            <b-form-invalid-feedback>{{
                                validationContext.errors[0]
                            }}</b-form-invalid-feedback>
                        </b-form-group>
                    </ValidationProvider>
                    <b-button variant="success" type="submit">Agregar</b-button>
                    <b-button @click="hideRecargaModal">Cancelar</b-button>
                </b-form>
            </validation-observer>
        </b-modal>
    </div>
</template>

<script>
export default {
    data() {
        return {
            newRecarga:{
                dn: null,
                compnay: null,
                recarga: null,
                type: "recargas",
            }
        };
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        open(){
            this.$root.$emit("bv::show::modal", "recarga-modal");
        },
        hideRecargaModal() {
            this.$root.$emit("bv::hide::modal", "recarga-modal");

            this.newRecarga = {
                dn: null,
                recarga: null,
                company: null,
                type: "recargas",
            };
        },

        addRecarga() {
            const recarga = {
                serie: this.newRecarga.recarga.name,
                precio: this.newRecarga.recarga.monto,
                recargaId: this.newRecarga.recarga.id,
                dn: this.newRecarga.dn,
                descripcion: `${this.newRecarga.recarga.name} Numero: ${this.newRecarga.dn}`,
                type: "recargas",
            };
            
            this.$emit("add-recarga", { ...recarga });

            this.hideRecargaModal();

            
        },
    },
};
</script>

<style></style>
