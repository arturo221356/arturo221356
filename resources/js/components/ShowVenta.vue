<template>
    <div>
        <b-overlay :show="isLoading" rounded="sm">
            <b-jumbotron>
                <b-container>
                    <b-row>
                        <b-col cols="12"
                            ><h3>
                                {{ ventaData.distribution }}
                            </h3></b-col
                        >
                    </b-row>
                    <b-row class="mt-3">
                        <b-col cols="6">
                            <h5>
                                <B>Sucursal:</B> {{ ventaData.inventario_name }}
                            </h5>
                            <h5><B>Vendedor:</B> {{ ventaData.vendedor }}</h5>

                            <h5>
                                <B>Domicilio:</B>
                                {{ ventaData.sucursal_domicilio }}
                            </h5>
                        </b-col>
                        <b-col cols="6">
                            <h5><B>Folio:</B> {{ ventaData.folio }}</h5>
                            <h5><B>Fecha:</B> {{ ventaData.fecha }}</h5>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="12">
                            <h5><B>Cliente:</B> {{ ventaData.cliente }}</h5>
                        </b-col>
                    </b-row>
                </b-container>

                <hr class="my-4" />

                <b-row>
                    <b-col cols="12">
                        <b-list-group>
                            <b-list-group-item
                                class="flex-column align-items-start"
                                v-for="productoGeneral in ventaData.productosGenerales"
                                :key="productoGeneral.id"
                            >
                                <h5>{{ productoGeneral.name }}</h5>

                                <p>{{ productoGeneral.description }}</p>

                                <p align="right">
                                    <B>Precio:</B> ${{
                                        productoGeneral.pivot.price
                                    }}
                                </p>
                            </b-list-group-item>

                            <b-list-group-item
                                class="flex-column align-items-start"
                                v-for="imei in ventaData.imeis"
                                :key="imei.id"
                            >
                                <h5 v-if="imei.equipo">
                                    {{ imei.equipo.marca }}
                                    {{ imei.equipo.modelo }}
                                </h5>

                                <p>Imei: {{ imei.imei }}</p>

                                <p align="right">
                                    <B>Precio:</B> ${{ imei.pivot.price }}
                                </p>
                            </b-list-group-item>

                            <b-list-group-item
                                class="flex-column align-items-start"
                                v-for="icc in ventaData.iccs"
                                :key="icc.id"
                            >
                                <h5 v-if="icc.linea">
                                    {{ icc.linea.sub_product.name }}
                                </h5>

                                <p>
                                    {{ icc.linea.product.name }}<br />
                                    Compañia: {{ icc.company.name }}<br />
                                    Numero: {{ icc.linea.dn }}<br />
                                    Icc: {{ icc.icc }}<br />
                                </p>

                                <p align="right">
                                    <B>Precio:</B> ${{ icc.pivot.price }}
                                </p>
                            </b-list-group-item>

                            <div >

                           
                            <b-list-group-item
                            
                                class="flex-column align-items-start"
                                v-for="transaction in ventaData.transactions"
                                :key="transaction.id"

                                :variant="transaction.taecel_success == false ? 'danger' : ''"
                            >
                                <h5 >
                                    {{ transaction.recarga.name }}
                                </h5>

                                Compañia: {{ transaction.company.name }}<br />
                                Numero: {{ transaction.dn }}<br />

                                <div v-if="transaction.taecel == true">
                                    Mensaje:
                                    {{ transaction.taecel_message }}<br />
                                    
                                    <div v-if="transaction.taecel_folio">
                                        Folio: {{ transaction.taecel_folio}}<br />
                                    </div>
                                    
                                </div>

                                <p align="right">
                                    <B>Precio:</B> ${{
                                        transaction.pivot.price
                                    }}
                                </p>
                            </b-list-group-item>
                             </div>

                            <hr class="my-2" />

                            <b-list-group-item
                                class="flex-column align-items-start"
                            >
                                <h4 align="right">Total: ${{ventaData.total}}</h4>
                            </b-list-group-item>


                        </b-list-group>
                    </b-col>
                </b-row>
            </b-jumbotron>
        </b-overlay>
    </div>
</template>

<script>
export default {
    prop: ["value"],

    props: {
        ventaId: {
            type: Number,
            required: false,
            default: null,
        },
    },

    data: function () {
        return {
            isLoading: false,

            ventaData: {
                distribution: null,
                folio: null,
                inventario_name: null,
                sucursal_domicilio: null,
                vendedor: null,
                cliente: null,
                imeis: [],
                productosGenerales: null,
                iccs: [],
                transactions: null,
                fecha: null,
            },
        };
    },
    methods: {
        loadData() {
             this.isLoading = true;
            axios.get(`/ventas/${this.ventaId}`, {}).then(
                function (response) {
                    console.log(response.data.data);

                    this.ventaData = response.data.data;

                    this.isLoading = false;

                }.bind(this)
            );
        },
    },
    computed:{
        computedListVariant: function(){
            if(this.ventaData.transactions.taecel_success == false){
                 return 'danger'
            }
           
        }
    },
    created: function () {
        this.loadData();
    },
};
</script>

<style></style>
