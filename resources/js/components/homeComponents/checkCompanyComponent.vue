<template>
    <div>
        <b-overlay :show="isLoading" rounded="sm">
        <validation-observer ref="venta" v-slot="{ handleSubmit }">
            <b-form @submit.prevent="handleSubmit(checkItx)">
                <ValidationProvider
                    rules="digits:10|required"
                    v-slot="validationContext"
                >
                    <b-form-group label="Consulta de Compañia" label-size="lg">
                        <b-input
                            placeholder="Insertar el numero"
                            v-model="numero"
                            autocomplete="off"
                            :state="getValidationState(validationContext)"
                        ></b-input>
                        <b-form-invalid-feedback>{{
                            validationContext.errors[0]
                        }}</b-form-invalid-feedback>
                    </b-form-group>
                </ValidationProvider>
                <b-button type="submit" variant="success" block
                    >Consultar</b-button
                >
            </b-form>
        </validation-observer>
        <div class="mt-2" v-if="mensaje.message">
            <b-alert
                variant="info"
                show
                >{{ mensaje.message }}</b-alert
            >
        </div>
        </b-overlay>
    </div>
</template>

<script>
export default {
    data() {
        return {
            numero: null,

            isLoading: false,
            mensaje: {
                
                message: null,
            },
        };
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        checkItx() {
            this.isLoading = true;

            const params = {
                numero: this.numero,
            };

            axios
                .post("/check/company", params)

                .then((response) => {
                    console.log(response);
                    this.isLoading = false;
                    this.mensaje = {
                        
                        message: response.data.result[0].value,
                    };
                })
                .catch((error) => {
                    this.mensaje = {
                        
                        message: error,
                    };
                    this.isLoading = false;
                });
        },
    },
};
</script>

<style></style>
