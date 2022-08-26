<template>
    <div>
        <date-sucursal-picker-component
            titeName="Reporte de Ventas"
            postUrl="/get/ventas"
            v-on:is-loading="isLoading = $event"
            v-on:data-loaded="dataLoaded($event)"
        ></date-sucursal-picker-component>

        <div class="mx-auto mt-4 col-md-12">
            <div class="row">
                <div class="col-md-8 mt-auto">
                    <h5>
                        Resultado: {{ countItems }}, Total: ${{ totalVentas }}
                    </h5>
                </div>
                <div class="col-md-4 float-right">
                    <b-form-group label="Filtrar:" label-size="sm"
                        ><b-input
                            placeholder="Filtrar"
                            type="search"
                            v-model="tableFilter"
                        ></b-input>
                    </b-form-group>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <b-table
                :items="tableItems"
                :fields="tableFields"
                :filter="tableFilter"
                hover
                class="position-relative"
                responsive
                striped
                head-variant="dark"
                :busy="isLoading"
                @filtered="tableFiltered"
            >
                <template #table-busy>
                    <div class="text-center text-primary my-2">
                        <b-spinner class="align-middle"></b-spinner>
                        <strong>Cargando...</strong>
                    </div>
                </template>
                <template v-slot:cell(print)="row">
                    <div class="h4 mb-0" :style="{ cursor: 'pointer' }">
                        <b-icon
                            icon="file-earmark"
                            @click="downloadInvoice(row.item.folio)"
                        ></b-icon>
                    </div>
                </template>
                <template #cell(Productos)="venta">
                    <div v-if="venta.item.accesorios.length > 0">
                        <p
                            v-for="producto in venta.item.accesorios"
                            :key="producto.id"
                        >
                            <b>Codigo:</b> {{ producto.otro.codigo }}<br />
                            <b>Nombre:</b> {{ producto.otro.name }}<br />

                            <b>Descripcion:</b>
                            {{ producto.otro.description }}<br />

                            <b>Precio:</b> ${{ producto.pivot.price }}<br />
                        </p>
                    </div>
                    <div v-if="venta.item.productosGenerales.length > 0">
                        <p
                            v-for="producto in venta.item.productosGenerales"
                            :key="producto.id"
                        >
                            <b>Nombre:</b> {{ producto.name }}<br />

                            <b>Descripcion:</b>
                            {{ producto.description }}<br />

                            <b>Precio:</b> ${{ producto.pivot.price }}<br />
                        </p>
                    </div>
                    <div v-if="venta.item.imeis.length > 0">
                        <p
                            v-for="producto in venta.item.imeis"
                            :key="producto.id"
                        >
                            <b>Imei:</b> {{ producto.imei }}<br />

                            <b>Equipo:</b>
                            {{ producto.equipo.marca }}
                            {{ producto.equipo.modelo }}<br />

                            <b>Precio:</b> ${{ producto.pivot.price }}<br />
                        </p>
                    </div>
                    <div v-if="venta.item.iccs.length > 0">
                        <p
                            v-for="producto in venta.item.iccs"
                            :key="producto.id"
                        >
                            <b>Icc:</b> {{ producto.icc }}<br />

                            <!-- <b>Tipo:</b>
                            {{ producto.icc.type.name  }}<br /> -->

                            <b>Compañia:</b>
                            {{ producto.company.name }}<br />

                            <b>Numero:</b> {{ producto.linea.dn }}<br />

                            {{ producto.linea.product.name }}<br />

                            {{ producto.linea.sub_product.name }}<br />

                            <b>Precio:</b> ${{ producto.pivot.price }}<br />
                        </p>
                    </div>

                    <div v-if="venta.item.transactions.length > 0">
                        <p
                            v-for="producto in venta.item.transactions"
                            :key="producto.id"
                        >
                            <b> {{ producto.recarga.name }}</b
                            ><br />

                            <b>Compañia:</b>
                            {{ producto.company.name }}<br />

                            <b>Numero:</b> {{ producto.dn }}<br />

                            <b>Monto:</b> ${{ producto.monto }}<br />

                            <b>Folio:</b>
                            {{ producto.taecel_folio }}<br />

                            <b-alert
                                show
                                :variant="
                                    producto.taecel_success == true
                                        ? 'success'
                                        : 'danger'
                                "
                                >{{ producto.taecel_message }}</b-alert
                            >
                        </p>
                    </div>
                </template>
            </b-table>
        </div>
    </div>
</template>

<script>
import DateSucursalPickerComponent from "./dateSucursalPickerComponent.vue";
export default {
    components: {
        DateSucursalPickerComponent,
    },
    data: function () {
        return {
            inventario: null,

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
                {
                    key: "print",
                    label: "",
                    sortable: false,
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
                    .map((o) => Number(o.total))
                    .reduce((a, c) => {
                        return Number(a) + Number(c);
                    });

                this.totalVentas = sum;
            } else {
                this.totalVentas = 0;
            }
        },
        downloadInvoice(folio) {
            this.isLoading = true;
            axios({
                url: `/venta/comprobante/?folio=` + folio,
                method: "GET",
                responseType: "blob", // important
            })
                .then((response) => {
                    const url = window.URL.createObjectURL(
                        new Blob([response.data])
                    );
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute("download", `comprobante_${folio}.pdf`);
                    document.body.appendChild(link);
                    link.click();
                    this.isLoading = false;
                })
                .catch(function (error) {
                    console.log(error);
                    this.isLoading = false;
                });
        },

        dataLoaded(data) {
            this.tableItems = data.data;
            this.countItems = data.data.length;
            if (data.data.length > 0) {
                let sum = data.data
                    .map((o) => Number(o.total))
                    .reduce((a, c) => {
                        return Number(a) + Number(c);
                    });

                this.totalVentas = sum;
            } else {
                this.totalVentas = 0;
            }
        },
    },
};
</script>

<style></style>
