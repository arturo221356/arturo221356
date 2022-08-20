<template>
    <div>
        <b-overlay :show="isLoading" rounded="sm">
        <h1>Portabilidad</h1>
        <div class="mt-3" v-if="porta.company">
            <h4>Compañia: {{ porta.company }}</h4>
        </div>
        <div class="mt-3" v-if="porta.vendedor">
            <h4>Vendedor: {{ porta.vendedor }}</h4>
        </div>

        <validation-observer
            ref="observer"
            v-slot="{ handleSubmit }"
            v-if="workingOnPorta == false"
        >
            <b-form @submit.prevent="handleSubmit(verificarIcc)">
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
                                :state="getValidationState(validationContext)"
                                autocomplete="off"
                                placeholder="Agregar Icc"
                            ></b-form-input>

                            <b-input-group-append>
                                <b-button variant="success" type="submit"
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

        <validation-observer
            ref="observer"
            v-slot="{ handleSubmit }"
            v-if="workingOnPorta == true"
        >
            <div class="row">
                <div class="col-sm">
                    <h1 class="float-right" :style="{ cursor: 'pointer' }">
                        <b-icon
                            icon="x-circle-fill"
                            variant="danger"
                            @click="resetAll()"
                        ></b-icon>
                    </h1>
                </div>
            </div>
            <b-form @submit.prevent="handleSubmit(agregarPorta)">
                <b-form-group label="Icc" label-size="lg" class="mt-3">
                    <b-input
                        type="number"
                        v-model="porta.icc"
                        autocomplete="off"
                        readonly
                    ></b-input>
                </b-form-group>
                <ValidationProvider
                    name="numero"
                    v-slot="validationContext"
                    rules="required|digits:10"
                >
                    <b-form-group label="Numero" label-size="lg" class="mt-3">
                        <b-input
                            placeholder="Numero"
                            type="number"
                            v-model="porta.dn"
                            autocomplete="off"
                            :state="getValidationState(validationContext)"
                        ></b-input>
                        <b-form-invalid-feedback>{{
                            validationContext.errors[0]
                        }}</b-form-invalid-feedback>
                    </b-form-group>
                </ValidationProvider>
                <ValidationProvider
                    name="nip"
                    v-slot="validationContext"
                    rules="required|digits:4"
                >
                    <b-form-group label="Nip" label-size="lg" class="mt-3">
                        <b-input
                            placeholder="Nip"
                            type="number"
                            v-model="porta.nip"
                            autocomplete="off"
                            :state="getValidationState(validationContext)"
                        ></b-input>
                        <b-form-invalid-feedback>{{
                            validationContext.errors[0]
                        }}</b-form-invalid-feedback>
                    </b-form-group>
                </ValidationProvider>

                <ValidationProvider
                    name="fvc"
                    v-slot="validationContext"
                    rules="required"
                >
                    <b-form-group label="Fvc" label-size="lg" class="mt-3">
                        <b-form-datepicker
                            :max="fvc.max"
                            :min="fvc.min"
                            placeholder="Fecha ventana de cambio"
                            v-model="porta.fvc"
                            :state="getValidationState(validationContext)"
                        ></b-form-datepicker>
                        <b-form-invalid-feedback>{{
                            validationContext.errors[0]
                        }}</b-form-invalid-feedback>
                    </b-form-group>
                </ValidationProvider>
                <b-button type="submit" block>
                    Enviar Porta
                </b-button>
            </b-form>
        </validation-observer>

        <div class="row">
            <div class="col-md-12 mt-3">
                <b-alert
                    :variant="response.success == true ? 'success' : 'danger'"
                    show
                    v-if="response"
                    >{{ response.message }}</b-alert
                >
            </div>
        </div>
        </b-overlay>
    </div>
</template>

<script>
export default {
    data: function () {
        const now = new Date();

        const today = new Date(
            now.getFullYear(),
            now.getMonth(),
            now.getDate()
        );

        const todayWithHour = new Date(now.getHours());

        var minDate = new Date(today);

        if (todayWithHour.getHours() < 16) {
            minDate.setDate(minDate.getDate() + 1);

            if (minDate.getDay() === 0) {
                minDate.setDate(minDate.getDate() + 1);
            }
        } else {
            minDate.setDate(minDate.getDate() + 2);

            if (minDate.getDay() === 0) {
                minDate.setDate(minDate.getDate() + 1);
            }
        }

        const maxDate = new Date(today);
        maxDate.setDate(maxDate.getDate() + 8);

        return {
            icc: null,

            workingOnPorta: false,

            response: null,

            isLoading: false,

            fvc: {
                min: minDate,

                max: maxDate,
            },

            porta: {
                icc: null,
                dn: null,
                nip: null,
                company: null,
                vendedor: null,
                fvc: minDate,
            },
        };
    },
    methods: {
        resetAll() {
            this.workingOnPorta = false;

            this.icc = null;

            this.porta = {
                dn: null,
                nip: null,
                company: null,
                vendedor: null,
            };
        },
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
                        this.porta.vendedor =
                            response.data.data.inventario.inventarioable.name;

                        this.workingOnPorta = true;
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
        agregarPorta() {
            this.isLoading = true;
            axios
                .post("/new/porta-externo", {
                    icc: this.porta.icc,
                    dn: this.porta.dn,
                    nip: this.porta.nip,
                    fvc: this.porta.fvc,
                })
                .then((response) => {
                    if (response.data.success == true) {
                        this.response = response.data;

                        this.resetAll();
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
