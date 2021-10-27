<template>
    <div>
        <b-overlay :show="isLoading" rounded="sm">
            <div class="jumbotron jumbotron-fluid">
                <div>
                    <div class="col-xl-6 mx-auto">
                        <!-- <div v-if="iccDetails == false"> -->
                        <div class="col-xl-12">
                            <h1>Nueva venta</h1>
                        </div>
                        <!-- </div> -->

                        <div class="col-xl-auto ml-auto mt-4">
                            <b-button
                                class="mr-3"
                                v-if="can('create transaction')"
                                @click="$refs.recargaModal.open()"
                                >Agregar Recarga</b-button
                            >

                            <b-button
                                class="mr-3"
                                @click="$refs.generalModal.open()"
                            >
                                Agregar venta general
                            </b-button>

                            <b-button 
                            class="mr-3"
                            @click="$refs.accesorioModal.open()"
                            >
                                Agregar Accesorio
                            </b-button>
                        </div>
                        <buscar-producto-component
                            :productos="productos"
                            v-on:add-producto="productos.unshift($event)"
                            v-on:new-icc="$refs.iccModal.newIcc($event)"
                        ></buscar-producto-component>

                        <div class="mt-4">
                            <div
                                v-for="(producto, index) in productos"
                                :key="index"
                            >
                                <b-card :title="producto.serie">
                                    <div class="row">
                                        <div class="col-xl-10">
                                            <b-card-text>
                                                {{ producto.descripcion }}
                                            </b-card-text>
                                        </div>
                                        <div class="col-xl-2">
                                            <b-button
                                                href="#"
                                                variant="danger"
                                                @click="eliminarItem(index)"
                                                >Eliminar</b-button
                                            >
                                        </div>
                                    </div>

                                    <div class="col-xl-2 ml-auto mt-3">
                                        <h5 class="ml-auto">
                                            Precio: ${{ producto.precio }}
                                        </h5>
                                    </div>
                                </b-card>
                            </div>
                            <b-card
                                align="right"
                                border-variant="primary"
                                v-if="productos.length > 0"
                            >
                                <b-button
                                    block
                                    variant="success"
                                   @click=" $refs.ventaModal.open()"
                                >
                                    Vender</b-button
                                >
                                <h4 class="ml-auto mt-4">
                                    Total: ${{ totalVenta }}.00
                                </h4>
                            </b-card>
                        </div>
                    </div>
                </div>
            </div>
        </b-overlay>

        <recarga-modal-component
            ref="recargaModal"
            v-on:add-recarga="productos.unshift($event)"
        ></recarga-modal-component>

        <general-modal-component
            ref="generalModal"
            v-on:add-general="productos.unshift($event)"
        ></general-modal-component>

        <accesorio-modal-component
            :productos="productos"
            ref="accesorioModal"
            v-on:add-accesorio="productos.unshift($event)"
        ></accesorio-modal-component>

        <nuevo-icc-modal-component
            ref="iccModal"
            v-on:add-icc="productos.unshift($event)"
        ></nuevo-icc-modal-component>
        <venta-modal-component
            :total-venta="totalVenta"
            :productos="productos"
            v-on:new-venta="newVenta"
            ref="ventaModal"
        ></venta-modal-component>
        <show-venta ref="showVenta"></show-venta>
    </div>
</template>

<script>
import RecargaModalComponent from "./nuevaventacomponents/recargaModalComponent.vue";
import BuscarProductoComponent from "./nuevaventacomponents/buscarPoductoComponent.vue";
import GeneralModalComponent from "./nuevaventacomponents/generalModalComponent.vue";
import NuevoIccModalComponent from "./nuevaventacomponents/nuevoIccModalComponent.vue";
import VentaModalComponent from "./nuevaventacomponents/ventaModalComponent.vue";
import ShowVenta from "./nuevaventacomponents/ShowVenta.vue";
import AccesorioModalComponent from "./nuevaventacomponents/accesorioModalComponent.vue";
export default {
    components: {
        RecargaModalComponent,
        AccesorioModalComponent,
        GeneralModalComponent,
        BuscarProductoComponent,
        NuevoIccModalComponent,
        VentaModalComponent,
        ShowVenta,
    },
    data() {
        return {
            isLoading: false,

            productos: [],

        };
    },
    methods: {
        eliminarItem(index) {
            this.productos.splice(index, 1);
        },

        newVenta(data) {
            this.isLoading = true;

            const params = {
                productos: this.productos,
                comentario: data.comentario,
                cliente: data.cliente,
            };
            this.$refs['ventaModal'].close();

            axios
                .post("/ventas", params)

                .then((response) => {

                    this.productos = [];

                    this.isLoading = false;

                     this.$refs['showVenta'].open(response.data);
                })
                .catch(function (error) {
                    alert(error);
                     this.isLoading = false;
                });
        },
    },
    computed: {
        totalVenta: function () {
            let total = 0;

            this.productos.forEach((element) => {
                total += Number(element.precio);
            });

            return total;
        },
    },
};
</script>

<style></style>
