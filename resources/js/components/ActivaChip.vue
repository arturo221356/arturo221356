<template>
    <b-overlay :show="isLoading" rounded="sm">
        <div class="jumbotron">
            <div class="col-md-8 mx-auto">
                <validation-observer ref="observer" v-slot="{ handleSubmit }">
                    <b-form @submit.prevent="handleSubmit(recargarChip)">
                        <h1>Ingresa el numero de telefono</h1>

                        <ValidationProvider
                            name="dn"
                            v-slot="validationContext"
                            rules="numeric|required|length:10"
                        >
                            <b-form-group class="mt-4">
                                <b-input
                                    v-model="dn"
                                    type="number"
                                    placeholder="Ingresa numero de telefono"
                                    
                                    :state="
                                        getValidationState(validationContext)
                                    "
                                >
                                </b-input>
                                <b-form-invalid-feedback>{{
                                    validationContext.errors[0]
                                }}</b-form-invalid-feedback>
                            </b-form-group>
                        </ValidationProvider>
                        <b-button type="submit" block> Recargar</b-button>
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
            </div>
        </div>
    </b-overlay>
</template>

<script>
export default {
    data: function () {
        return {
            dn: null,

            response: null,

            isLoading: false,
        };
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        recargarChip() {
             this.isLoading = true;

            axios.post("/recarga-chip", { dn: this.dn }).then((response) => {
                console.log(response.data);

                this.response = response.data;

                this.isLoading = false;

                 this.dn = null;
            });
        },
    },
};
</script>

<style></style>
