<template>
    <div>
        <b-overlay :show="isLoading" rounded="sm">
            <div>
                <div class="col-lg-8 mx-auto">
                    <div class="row"><h1>Reporte de Recargas</h1></div>

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
                        <b-button block @click="getTransactions"
                            >Cargar</b-button
                        >
                    </b-form>
                    <div class="mt-4">
                        <!-- <div
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
                        </div> -->
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-sm mt-auto">
                            <h5>
                                Resultado: {{ countItems }} recargas, Total: ${{
                                    totalRecargas
                                }}
                            </h5>
                        </div>
                        <div class="col-sm float-right">
                            <b-form-group label="Filtrar:" label-size="sm"
                                ><b-input
                                    placeholder="Filtrar"
                                    type="search"
                                    v-model="tableFilter"
                                ></b-input>
                            </b-form-group>
                        </div>
                    </div>
                    <div class="mt-4">
                        <b-table
                            :items="tableItems"
                            :filter="tableFilter"
                            :fields="tableFields"
                            hover
                            responsive
                            striped
                            stacked="sm"
                            head-variant="dark"
                            :busy="tableBusy"
                            :tbody-tr-class="rowClass"
                            @filtered="tableFiltered"
                        >
                        <template #table-busy>
                                <div class="text-center text-primary my-2">
                                    <b-spinner class="align-middle"></b-spinner>
                                    <strong>Loading...</strong>
                                </div>
                            </template>
                        </b-table>
                    </div>
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

            countItems: 0,

            tableItems: [],

            totalRecargas: 0,

            tableFields: [
                {
                    key: "dn",
                    label: "Numero",
                },
                {
                    key: "company_name",
                    label: "Compañia",
                    sortable: true,
                },
                {
                    key: "recarga_name",
                    label: "Nombre",
                    sortable: true,
                },
                {
                    key: "inventario_name",
                    label: "Inventario",
                    sortable: true,
                },
                {
                    key: "monto",
                    label: "Monto",
                    sortable: true,
                },
                {
                    key: "taecel_message",
                    label: "Mensaje",
                    sortable: true,
                },
                {
                    key: "fecha",
                    label: "Fecha",
                    sortable: true,
                },
            ],

            tableFilter: null,

            isLoading: false,

            initialDate: new Date().toISOString().substr(0, 10),

            finalDate: new Date().toISOString().substr(0, 10),

            chipsBar: { name: "Recargas", value: 0, variant: "primary" },
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

            return bars;
        },
    },
    methods: {
        // showLineas($producto){
        //     this.tableItems = [];

        //     switch ($producto){
        //         case 'Chips':
        //             this.tableItems = this.chips;
        //         break;

        //     }
        // },
        // getMonthFirst() {
        //     var today = new Date();

        //     this.initialDate = new Date(
        //         today.getFullYear(),
        //         today.getMonth(),
        //         1
        //     );
        // },

        rowClass(item, type) {
            if (!item || type !== "row") return;
            if (item.taecel_success == false) return "table-danger";
        },

        tableFiltered(filteredItems) {
            this.countItems = filteredItems.length;
            
            if(filteredItems.length > 0 ){
                let sum = filteredItems
                .map((o) => Number(o.monto))
                .reduce((a, c) => {
                    return a + c;
                });

            this.totalRecargas = sum;
            }else{
                this.totalRecargas = 0;
            }
            
        },

        getTransactions() {
            this.tableBusy = true;
            axios
                .get(`/transaction`, {
                    params: {
                        inventario_id: this.inventario.id,
                        initial_date: this.initialDate,
                        final_date: this.finalDate,
                    },
                })
                .then(
                    function (response) {
                        // this.chips.value = response.data;

                        this.tableItems = response.data.data;

                        if(response.data.data.length >0){
                            let sum = response.data.data
                            .map((o) => o.monto)
                            .reduce((a, c) => {
                                return a + c;
                            });

                        this.totalRecargas = sum;
                        }else{
                            this.totalRecargas = 0;
                        }
                        

                        this.countItems = this.tableItems.length;

                        this.tableBusy = false;

                        // this.chipsBar.value = response.data.data.length;
                    }.bind(this)
                )
                .catch(function (error) {
                    console.log(error);
                });
        },
    },
    created() {
        // this.getMonthFirst();
    },
};
</script>

<style></style>
