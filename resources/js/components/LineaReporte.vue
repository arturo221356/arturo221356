<template>
    <div>
        <b-overlay :show="isLoading" rounded="sm">
            <div>
                <div class="col-lg-10 mx-auto">
                    <div class="row"><h1>Reporte de Lineas</h1></div>

                    <b-form>
                        <div class="row mt-4">
                            <div class="col-lg-6">
                                <b-form-group label="Fecha Inicial">
                                    <b-form-datepicker
                                        v-model="initialDate"
                                        locale="es"
                                        :max="maxDate"
                                    ></b-form-datepicker>
                                </b-form-group>
                            </div>
                            <div class="col-lg-6">
                                <b-form-group label="Fecha Final">
                                    <b-form-datepicker
                                        v-model="finalDate"
                                        :max="maxDate"
                                        locale="es"
                                    ></b-form-datepicker>
                                </b-form-group>
                            </div>
                        </div>
                        <b-form-group label="Inventario:" label-size="lg">
                            <select-general
                                url="/inventario"
                                pholder="Seleccionar Inventario"
                                v-model="inventario"
                                :empty="true"
                                :todas="can('get inventarios') ? true : false"
                            >
                            </select-general>
                        </b-form-group>
                        <b-button block @click="loadData">Cargar</b-button>
                    </b-form>
                    <div class="mt-4">
                        <div
                            v-for="bar in bars"
                            :key="bar.index"
                            class="row mb-1"
                        >
                            <div class="col-sm-2">
                                <b-button
                                    @click="showLineas(bar.name)"
                                    :variant="bar.variant"
                                    >{{ bar.name }}
                                    <b-badge variant="light">{{
                                        bar.value
                                    }}</b-badge></b-button
                                >
                            </div>
                            <div class="col-sm-10 pt-1">
                                <b-progress
                                    height="2rem"
                                    :value="bar.value"
                                    :variant="bar.variant"
                                    :max="100"
                                    show-value
                                ></b-progress>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <b-table
                        :items="tableItems"
                        :filter="tableFilter"
                        hover
                        responsive
                        striped
                        stacked="xl"
                        head-variant="dark"
                        :busy="tableBusy"
                    >
                    </b-table>
                </div>
            </div>
        </b-overlay>
    </div>
</template>

<script>
export default {
    data: function () {
        return {
            inventario: null,

            tableBusy: false,

            tableItems: [],

            tableFilter: null,

            isLoading: false,

            initialDate: null,

            finalDate: new Date().toISOString().substr(0, 10),

            chipsBar: {
                name: "Chips",
                value: 0,
                variant: "primary",
            },

            portasBar: {
                name: "Portas",
                value: 0,
                variant: "primary",
            },
            exportadosBar: {
                name: "Exportados",
                value: 0,
                variant: "danger",
            },

            chips: [],

            portas: [],

            exportadas:[],
        };
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

        bars: function () {
            var bars = [];

            bars.push(this.chipsBar);

            bars.push(this.portasBar);

            bars.push(this.exportadosBar);

            return bars;
        },
    },
    methods: {
        loadData() {
            this.getPortas();
            this.getActivatedChips();
            this.getExportadas();
        },
        showLineas($producto) {
            this.tableItems = [];

            switch ($producto) {
                case "Chips":
                    this.tableItems = this.chips;
                    break;
                case "Portas":
                    this.tableItems = this.portas;
                    break;
                case "Exportados":
                    this.tableItems = this.exportadas;
                    break;
            }
        },
        getMonthFirst() {
            var today = new Date();

            this.initialDate = new Date(
                today.getFullYear(),
                today.getMonth(),
                1
            );
        },

        getActivatedChips() {
            axios
                .post(`/chip/activated`, {
                    inventario_id: this.inventario.id,
                    initial_date: this.initialDate,
                    final_date: this.finalDate,
                })
                .then(
                    function (response) {
                        // this.chips.value = response.data;
                        console.log(response.data.data);

                        this.chips = response.data.data;

                        this.chipsBar.value = response.data.data.length;

                        this.showLineas();
                    }.bind(this)
                )
                .catch(function (error) {
                    console.log(error);
                });
        },
        getPortas() {
            axios
                .post(`/get/porta`, {
                    inventario_id: this.inventario.id,
                    initial_date: this.initialDate,
                    final_date: this.finalDate,
                })
                .then(
                    function (response) {
                        // this.chips.value = response.data;
                        console.log(response.data.data);

                        this.portas = response.data.data;

                        this.portasBar.value = response.data.data.length;

                        this.showLineas();
                    }.bind(this)
                )
                .catch(function (error) {
                    console.log(error);
                });
        },
        getExportadas() {
            axios
                .post(`/get/exportada`, {
                    inventario_id: this.inventario.id,
                    initial_date: this.initialDate,
                    final_date: this.finalDate,
                })
                .then(
                    function (response) {
                        // this.chips.value = response.data;
                        console.log(response.data.data);

                        this.exportadas = response.data.data;

                        this.exportadosBar.value = response.data.data.length;

                        this.showLineas();
                    }.bind(this)
                )
                .catch(function (error) {
                    console.log(error);
                });
        },
    },
    created() {
        this.getMonthFirst();
    },
};
</script>

<style></style>
