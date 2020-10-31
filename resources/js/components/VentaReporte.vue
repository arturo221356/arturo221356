<template>
    <div>
        <b-overlay :show="isLoading" rounded="sm">
            <div>
                <div class="col-lg-8 mx-auto">
                    <div class="row"><h1>Reporte de Ventas</h1></div>

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
                        <b-button block @click="getVentas">Cargar</b-button>
                    </b-form>

                    <div class="mt-4">
                        <b-table
                        :items="tableItems"
                        
                        :filter="tableFilter"
                        hover
                        responsive
                        striped
                        stacked="sm"
                        head-variant="dark"
                        
                        :busy="tableBusy"
                        
                    >
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

            tableItems: [],
            
            tableFilter: null,

            isLoading: false,

            initialDate: null,

            finalDate: new Date().toISOString().substr(0, 10),

            chipsBar: { name: "Chips", value: 0, variant: "primary" },

            chips: [],
        };
    },
    computed:
     {
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
        showLineas($producto){
            this.tableItems = [];

            switch ($producto){
                case 'Chips':
                    this.tableItems = this.chips;
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

        getVentas(){
            
            axios
                .get(`/ventas`, { })
                .then(
                    function (response) {

                        this.tableItems = response.data;
                        console.log(response);

                    }.bind(this)
                )
                .catch(function (error) {
                    console.log(error);
                });
        }
    },
    created() {
        this.getMonthFirst();

        
    },
};
</script>

<style></style>
