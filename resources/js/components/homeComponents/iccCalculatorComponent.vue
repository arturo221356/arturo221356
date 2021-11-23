<template>
    <div>
        <b-overlay :show="isLoading" rounded="sm">
        <validation-observer ref="venta" v-slot="{ handleSubmit }">
            <b-form @submit.prevent="handleSubmit(consulta)">
                <ValidationProvider
                    rules="Icc|Luhn|required"
                    v-slot="validationContext"
                    name="Icc 1"
                >
                    <b-form-group label="Primer Icc" label-size="lg">
                        <b-input
                            type="number"
                            placeholder="Primer Icc"
                            v-model="icc1"
                            autocomplete="off"
                            :state="getValidationState(validationContext)"
                        ></b-input>
                        <b-form-invalid-feedback>{{
                            validationContext.errors[0]
                        }}</b-form-invalid-feedback>
                    </b-form-group>
                </ValidationProvider>
                <ValidationProvider
                    :rules="`Icc|Luhn|required|Icc2:${icc1}`"
                    v-slot="validationContext"
                    name="Icc 2"
                >
                    <b-form-group label="Ultimo Icc" label-size="lg">
                        <b-input
                            type="number"
                            placeholder="Ultimo Icc"
                            v-model="icc2"
                            autocomplete="off"
                            :state="getValidationState(validationContext)"
                        ></b-input>
                        <b-form-invalid-feedback>{{
                            validationContext.errors[0]
                        }}</b-form-invalid-feedback>
                    </b-form-group>
                </ValidationProvider>
                <b-button type="submit" variant="success" block
                    >Generar Archivo</b-button
                >
            </b-form>
        </validation-observer>
        </b-overlay>
    </div>
</template>

<script>
export default {
    data() {
        return {
            icc1: null,
            icc2: null,
            isLoading: false,
        };
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        consulta() {
            this.isLoading = true;
            axios({
                url: `/calculator/icc`,
                method: "POST",
                data: {
                    icc_1: this.icc1,
                    icc_2: this.icc2,
                },
                responseType: "blob", // important
            })
                .then((response) => {
                    const url = window.URL.createObjectURL(
                        new Blob([response.data])
                    );
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute("download", `Iccs_calculados.xlsx`);
                    document.body.appendChild(link);
                    link.click();
                    this.icc1 = null;
                    this.icc2 = null;
                    this.isLoading = false;
                })
                .catch((error) => {
                    alert(error);

                    this.isLoading = false;
                });
        },
    },
};
</script>

<style></style>
