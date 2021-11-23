<template>
    <div>
        <b-overlay :show="isLoading" rounded="sm">
            <div class="containter">
                <div class="col-md-8 mx-auto">
                    <div class="row"><h1>Reporte de Lineas</h1></div>

                    <b-form>
                        <div class="mt-4 row">
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
                        <b-button
                            block
                            @click="loadData"
                            :disabled="inventario == null ? true : false"
                            >Cargar</b-button
                        >

                        <b-button
                            block
                            @click="downloadExcel"
                            variant="success"
                            :disabled="inventario == null ? true : false"
                            >Descargar Excel</b-button
                        >
                    </b-form>
                </div>
                <div class="mt-4 mx-auto col-md-8">
                    <div v-for="bar in bars" :key="bar.index" class="row mt-2">
                        <div class="col-2 mb-2">
                            <b-overlay
                                :show="bar.loading"
                                rounded
                                opacity="0.6"
                                spinner-small
                                class="d-inline-block"
                            >
                                <b-button
                                    @click="showLineas(bar.name)"
                                    :variant="bar.variant"
                                    >{{ bar.name }}
                                    <b-badge variant="light">{{
                                        bar.value
                                    }}</b-badge>
                                </b-button>
                            </b-overlay>
                        </div>
                        <div class="col-md-10">
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

            <div class="mx-auto mt-4 col-md-12">
                <div class="col-lg-3 float-right">
                    <b-form-group label="Filtrar:" label-size="sm"
                        ><b-input
                            autocomplete="off"
                            placeholder="Filtrar"
                            type="search"
                            v-model="tableFilter"
                        ></b-input>
                    </b-form-group>
                </div>

                <b-table
                    :items="tableItems"
                    :filter="tableFilter"
                    caption-top
                    hover
                    responsive
                    striped
                    head-variant="dark"
                    :busy="tableBusy"
                    @filtered="tableFiltered"
                >
                    <template slot="table-caption"
                        >Resultado: {{ countItems }}</template
                    >

                    <template slot="emptyfiltered">No se encontraron</template>
                </b-table>
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

            initialDate: new Date().toISOString().substr(0, 10),

            finalDate: new Date().toISOString().substr(0, 10),

            chipsBar: {
                name: "Chips",
                value: 0,
                variant: "primary",
                loading: false,
            },

            portasBar: {
                name: "Portas",
                value: 0,
                variant: "primary",
                loading: false,
            },
            exportadosBar: {
                name: "Exportados",
                value: 0,
                variant: "danger",
                loading: false,
            },

            chips: [],

            portas: [],

            exportadas: [],

            countItems: 0,
        };
    },
    watch: {
        tableItems: function (items) {
            this.countItems = items.length;
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

        bars: function () {
            var bars = [];

            bars.push(this.chipsBar);

            bars.push(this.portasBar);

            bars.push(this.exportadosBar);

            return bars;
        },
        fieldKeys: function () {
            if (this.tableItems.length > 0) {
                return Object.keys(this.tableItems[0]);
            }
        },
    },
    methods: {
        tableFiltered(filteredItems) {
            this.countItems = filteredItems.length;
        },
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

        getActivatedChips() {
            this.chipsBar.loading = true;

            axios
                .post(`/chip/activated`, {
                    inventario_id: this.inventario.id,
                    initial_date: this.initialDate,
                    final_date: this.finalDate,
                })
                .then((response) => {
                    // this.chips.value = response.data;
                    console.log(response.data.data);

                    this.chips = response.data.data;

                    this.chipsBar.value = response.data.data.length;

                    this.showLineas();

                    this.chipsBar.loading = false;
                })
                .catch((error) => {
                    this.chipsBar.loading = false;
                    alert(error);
                });
        },
        getPortas() {
            this.portasBar.loading = true;
            axios
                .post(`/get/porta`, {
                    inventario_id: this.inventario.id,
                    initial_date: this.initialDate,
                    final_date: this.finalDate,
                })
                .then((response) => {
                    // this.chips.value = response.data;
                    console.log(response.data.data);

                    this.portas = response.data.data;

                    this.portasBar.value = response.data.data.length;

                    this.showLineas();

                    this.portasBar.loading = false;
                })
                .catch((error) => {
                    alert(error);
                    this.portasBar.loading = false;
                });
        },
        getExportadas() {
            this.exportadosBar.loading = true;
            axios
                .post(`/get/exportada`, {
                    inventario_id: this.inventario.id,
                    initial_date: this.initialDate,
                    final_date: this.finalDate,
                })
                .then((response) => {
                    // this.chips.value = response.data;
                    console.log(response.data.data);

                    this.exportadas = response.data.data;

                    this.exportadosBar.value = response.data.data.length;

                    this.showLineas();

                    this.exportadosBar.loading = false;
                })
                .catch((error) => {
                    alert(error);
                    this.exportadosBar.loading = false;
                });
        },

        downloadExcel() {
            this.isLoading = true;
            axios({
                url: `/linea/exportar/excel`,
                method: "POST",
                data: {
                    inventario_id: this.inventario.id,
                    initial_date: this.initialDate,
                    final_date: this.finalDate,
                },
                responseType: "blob", // important
            })
                .then((response) => {
                    const url = window.URL.createObjectURL(
                        new Blob([response.data])
                    );
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute("download", `Lineas.xlsx`);
                    document.body.appendChild(link);
                    link.click();
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
