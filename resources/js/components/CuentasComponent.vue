<template>
    <div>
        <b-overlay :show="isLoading" rounded="sm">
            <div class="jumbotron jumbotron-fluid">
                <div class="col-lg-12">
                    <h1>Cuentas</h1>
                </div>

                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="container">
                            <b-form-group
                                label="Periodo de tiempo"
                                label-size="lg"
                            >
                                <b-form-radio-group
                                    id="btn-radios-1"
                                    v-model="periodoTiempoPersonalizado"
                                    buttons
                                >
                                    <b-form-radio :value="false"
                                        >Ultimo Corte</b-form-radio
                                    >
                                    <b-form-radio :value="true"
                                        >Personalizado</b-form-radio
                                    >
                                </b-form-radio-group>
                            </b-form-group>
                        </div>

                        <div v-if="periodoTiempoPersonalizado == true">
                            <div class="col-lg-12">
                                <b-form-group label="Fecha Inicial">
                                    <b-form-datepicker
                                        locale="es"
                                        v-model="initialDate"
                                        :max="maxDate"
                                    ></b-form-datepicker>
                                </b-form-group>
                            </div>
                            <div class="col-lg-12">
                                <b-form-group label="Fecha Final">
                                    <b-form-datepicker
                                        locale="es"
                                        v-model="finalDate"
                                        :max="maxDate"
                                    ></b-form-datepicker>
                                </b-form-group>
                                <b-form-group>
                                    <b-button block variant="primary"
                                        >Cargar</b-button
                                    >
                                </b-form-group>
                            </div>
                        </div>

                        <b-list-group>
                            <b-list-group-item
                                button
                                v-for="caja in cajas"
                                :key="caja.id"
                                :active="selectedListItem.id == caja.id"
                                @click="selectCaja(caja)"
                            >
                                <div
                                    class="d-flex w-100 justify-content-between"
                                >
                                    <h5>
                                        <B>{{ caja.name }}</B>
                                    </h5>

                                    <small
                                        ><B>Ultimo Corte:</B>
                                        {{ caja.lastcorteDate }}</small
                                    >
                                </div>

                                <div class="float-right">
                                    Total: ${{ caja.total }}
                                </div>
                            </b-list-group-item>
                        </b-list-group>
                    </div>

                    <div class="col-md-8">
                        <div>
                            <b-tabs
                                content-class="mt-3"
                                v-if="selectedListItem.id"
                            >
                                <b-tab title="Resumen" active>
                                    <div class="col-12">
                                        <b-button size="sm" variant="primary"
                                            >Ajustar</b-button
                                        >
                                    </div>
                                    <div class="col-12">
                                        <h5><B>Ventas:</B> $1500</h5>
                                        <br />
                                        <h5><B>Gastos:</B> $500</h5>
                                        <br />
                                        <h5>
                                            <B>Total en caja:</B> ${{
                                                selectedListItem.total
                                            }}
                                        </h5>
                                    </div>
                                </b-tab>
                                <b-tab title="Ventas"><p>Ventas</p></b-tab>
                                <b-tab title="Gastos">
                                    <div class="col-12">
                                        <b-button size="sm" variant="primary"
                                            >Agregar Gasto</b-button
                                        >
                                    </div>
                                </b-tab>
                                <b-tab title="Cortes"><p>Cortes</p></b-tab>
                            </b-tabs>
                        </div>
                    </div>
                </div>
            </div>
        </b-overlay>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,

            cajas: [],

            periodoTiempoPersonalizado: false,

            selectedListItem: {
                id: null,
                total: 0,
            },
            initialDate: new Date().toISOString().substr(0, 10),

            finalDate: new Date().toISOString().substr(0, 10),
        };
    },
    methods: {
        loadCajas() {
            axios
                .post("/get/cajas")

                .then((response) => {
                    this.cajas = response.data.data;
                    console.log(response.data.data);
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        selectCaja(caja) {
            this.selectedListItem = caja;
        },
    },
    computed: {
        maxDate: function () {
            const now = new Date();
            const today = new Date(
                now.getFullYear(),
                now.getMonth(),
                now.getDate()
            );

            return today;
        },
    },
    created() {
        this.loadCajas();
    },
};
</script>

<style></style>
