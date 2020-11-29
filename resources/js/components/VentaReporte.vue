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
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-sm mt-auto">
                                <h5>
                                    Resultado: {{ countItems }}, Total: ${{
                                        totalVentas
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
                                :fields="tableFields"
                                :filter="tableFilter"
                                hover
                                responsive
                                striped
                                stacked="sm"
                                head-variant="dark"
                                :busy="tableBusy"
                                @filtered="tableFiltered"
                            >
                                <template #table-busy>
                                    <div class="text-center text-primary my-2">
                                        <b-spinner
                                            class="align-middle"
                                        ></b-spinner>
                                        <strong>Loading...</strong>
                                    </div>
                                </template>
                                <template #cell(Productos)="venta">
                                    <div
                                        v-if="
                                            venta.item.productosGenerales
                                                .length > 0
                                        "
                                    >
                                        <p
                                            v-for="producto in venta.item
                                                .productosGenerales"
                                            :key="producto.id"
                                        >
                                            <b>Nombre:</b> {{ producto.name
                                            }}<br />

                                            <b>Descripcion:</b>
                                            {{ producto.description }}<br />

                                            <b>Precio:</b> ${{
                                                producto.pivot.price
                                            }}<br />
                                        </p>
                                    </div>
                                    <div v-if="venta.item.imeis.length > 0">
                                        <p
                                            v-for="producto in venta.item.imeis"
                                            :key="producto.id"
                                        >
                                            <b>Imei:</b> {{ producto.imei
                                            }}<br />

                                            <b>Equipo:</b>
                                            {{ producto.equipo.marca }}
                                            {{ producto.equipo.modelo }}<br />

                                            <b>Precio:</b> ${{
                                                producto.pivot.price
                                            }}<br />
                                        </p>
                                    </div>
                                    <div v-if="venta.item.iccs.length > 0">
                                        <p
                                            v-for="producto in venta.item.iccs"
                                            :key="producto.id"
                                        >
                                            <b>Icc:</b> {{ producto.icc }}<br />

                                            <b>Compañia:</b>
                                            {{ producto.company.name }}<br />

                                            <b>Numero:</b> {{ producto.linea.dn
                                            }}<br />

                                            {{ producto.linea.product.name
                                            }}<br />

                                            {{ producto.linea.sub_product.name
                                            }}<br />

                                            <b>Precio:</b> ${{
                                                producto.pivot.price
                                            }}<br />
                                        </p>
                                    </div>

                                    <div
                                        v-if="
                                            venta.item.transactions.length > 0
                                        "
                                    >
                                        <p
                                            v-for="producto in venta.item
                                                .transactions"
                                            :key="producto.id"
                                        >
                                            <b> {{ producto.recarga.name }}</b
                                            ><br />

                                            <b>Compañia:</b>
                                            {{ producto.company.name }}<br />

                                            <b>Numero:</b> {{ producto.dn
                                            }}<br />

                                            <b>Monto:</b> ${{ producto.monto
                                            }}<br />

                                            <b>Folio:</b>
                                            {{ producto.taecel_folio }}<br />

                                            <b-alert
                                                show
                                                :variant="
                                                    producto.taecel_success ==
                                                    true
                                                        ? 'success'
                                                        : 'danger'
                                                "
                                                >{{
                                                    producto.taecel_message
                                                }}</b-alert
                                            >
                                        </p>
                                    </div>
                                </template>
                            </b-table>
                        </div>
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
            // aok: {
            //     item: {
            //         distribution: "promoviles 1",
            //         folio: 1,
            //         inventario_name: "Bodega",
            //         sucursal_domicilio: "madero 110",
            //         vendedor: "Vendedor User",
            //         cliente: "público en general",
            //         productosGenerales: [
            //             {
            //                 id: 1,
            //                 name: "qweqw",
            //                 description: "qweqwe",
            //                 price: 123,
            //                 created_at: "2020-11-21T23:10:45.000000Z",
            //                 updated_at: "2020-11-21T23:10:45.000000Z",
            //                 pivot: {
            //                     venta_id: 1,
            //                     ventaable_id: 1,
            //                     ventaable_type: "App\\ProductoGeneral",
            //                     price: 123,
            //                 },
            //             },
            //         ],
            //         total: 123,
            //         imeis: [],
            //         iccs: [],
            //         transactions: [],
            //         fecha: "21/11/20 05:10:45",
            //     },
            //     index: 0,
            //     field: { key: "Productos", label: "Productos" },
            //     unformatted: "",
            //     value: "",
            //     detailsShowing: false,
            //     rowSelected: false,
            // },
            inventario: null,

            tableBusy: false,

            countItems: 0,

            totalVentas: 0,

            tableItems: [],

            tableFields: [
                {
                    key: "folio",
                    label: "Folio",
                    sortable: true,
                },
                {
                    key: "cliente",
                    label: "Cliente",
                    sortable: true,
                },
                {
                    key: "inventario_name",
                    label: "Inventario",
                    sortable: true,
                },
                {
                    key: "vendedor",
                    label: "Vendedor",
                    sortable: true,
                },
                "Productos",
                {
                    key: "comment",
                    label: "Comentario",
                },
                {
                    key: "fecha",
                    label: "Fecha",
                    sortable: true,
                },
                {
                    key: "total",
                    label: "Total",
                    sortable: true,
                },
            ],

            tableFilter: null,

            isLoading: false,

            initialDate: new Date().toISOString().substr(0, 10),

            finalDate: new Date().toISOString().substr(0, 10),

            chipsBar: { name: "Chips", value: 0, variant: "primary" },

            chips: [],
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
        showLineas($producto) {
            this.tableItems = [];

            switch ($producto) {
                case "Chips":
                    this.tableItems = this.chips;
                    break;
            }
        },
        tableFiltered(filteredItems) {
            this.countItems = filteredItems.length;

            if (filteredItems.length > 0) {
                let sum = filteredItems
                    .map((o) => o.total)
                    .reduce((a, c) => {
                        return a + c;
                    });

                this.totalVentas = sum;
            } else {
                this.totalVentas = 0;
            }
        },


        getVentas() {
            this.tableBusy = true;
            axios
                .get(`/ventas`, {
                    params:{
                        initial_date: this.initialDate,
                       
                        final_date: this.finalDate,
                        
                        inventario_id: this.inventario.id,
                    }
                })
                .then(
                    function (response) {
                        this.tableItems = response.data.data;
                        this.countItems = this.tableItems.length;

                        this.tableBusy = false;
                        if (response.data.data.length > 0) {
                            let sum = response.data.data
                                .map((o) => Number(o.total))
                                .reduce((a, c) => {
                                    return a + c;
                                });

                            this.totalVentas = sum;
                        } else {
                            this.totalVentas = 0;
                        }
                    }.bind(this)
                )
                .catch(function (error) {
                    console.log(error);
                });
        },
    },

};
</script>

<style></style>
