<template>
    <b-overlay :show="isLoading" rounded="sm">
        <div class="jumbotron">
            <div class="col-md-8 mx-auto">
                <validation-observer ref="observer" v-slot="{ handleSubmit }">
                    <b-form @submit.prevent="handleSubmit(verificarIcc)">
                        <h1>Portabilidad</h1>

                        <ValidationProvider
                            name="icc"
                            v-slot="validationContext"
                            rules="required|Icc|numeric"
                        >
                            <b-form-group label="Icc:" label-size="lg">
                                <b-input-group prepend="Icc">
                                    <b-form-input
                                        v-model.lazy="icc"
                                        type="number"
                                        list="search-results"
                                        :state="
                                            getValidationState(
                                                validationContext
                                            )
                                        "
                                        autocomplete="off"
                                        placeholder="Agregar Icc"
                                    ></b-form-input>

                                    <b-input-group-append>
                                        <b-button
                                            variant="success"
                                            type="submit"
                                            >Agregar</b-button
                                        >
                                    </b-input-group-append>
                                    <b-form-invalid-feedback>{{
                                        validationContext.errors[0]
                                    }}</b-form-invalid-feedback>
                                </b-input-group>
                            </b-form-group>
                        </ValidationProvider>
                    </b-form>
                </validation-observer>

                <div class="row">
                    <div class="col-md-12 mt-3">
                        <b-alert
                            :variant="
                                response.success == true ? 'success' : 'danger'
                            "
                            show
                            v-if="response"
                            >{{ response.message }}</b-alert
                        >
                    </div>
                </div>

                {{porta.company}}
            </div>
        </div>
    </b-overlay>
</template>

<script>
export default {
    data: function () {
        return {
            icc: null,

            response: null,

            isLoading: false,



            porta:{
                icc: null,
                dn: null,
                nip: null,
                company: null, 
                cliente:{
                    nombre: null,
                    curp: null,
                    referencia: null,

                },
                trafico: null, 

            }


        };
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },

        verificarIcc() {
            this.isLoading = true;
            axios
                .post("/linea/verificar-icc-externo", { icc: this.icc })
                .then((response) => {
                    if (response.data.success == true) {
                        console.log(response.data);
                        this.porta.icc = response.data.data.icc;
                        this.porta.company = response.data.data.company.name;
                    } else if (response.data.success == false) {
                        this.response = response.data;
                        console.log(response.data);
                    }
                    this.isLoading = false;
                })
                .catch((error) => {
                    console.log(error);
                    alert(error);
                    this.isLoading = false;
                });
        },
    },
};
</script>

<style></style>
