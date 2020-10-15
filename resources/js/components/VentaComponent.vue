<template>
    <div>
        <b-overlay :show="isLoading" rounded="sm">
            <div class="jumbotron jumbotron-fluid">
                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <div v-if="iccDetails == false">
                            <h1>Nueva venta</h1>

                            <b-form @submit.prevent="agregarProducto">
                                <b-form-group
                                    class="mt-4"
                                    label="Buscar producto:"
                                    label-size="lg"
                                >
                                    <b-input-group>
                                        <b-form-input
                                            v-model="searchValue"
                                            autocomplete="off"
                                            placeholder="Buscar Producto"
                                            list="search-results"
                                            @keyup.stop="handleSearch"
                                        ></b-form-input>

                                        <datalist id="search-results">
                                            <option
                                                v-for="(list,
                                                index) in searchResults"
                                                :key="index"
                                                >{{ list.title }}</option
                                            >
                                        </datalist>

                                        <b-input-group-append>
                                            <b-button
                                                type="submit"
                                                variant="success"
                                                >Agregar</b-button
                                            >
                                        </b-input-group-append>
                                    </b-input-group>
                                </b-form-group>
                            </b-form>
                        </div>
                        <div v-if="iccDetails == true">
                            <div class="row">
                                <div class="col-sm">
                                    <h1
                                        class="float-right"
                                        :style="{ cursor: 'pointer' }"
                                    >
                                        <b-icon
                                            icon="x-circle-fill"
                                            variant="danger"
                                            @click="closeIccDetails"
                                        ></b-icon>
                                    </h1>
                                </div>
                            </div>
                            <b-form>
                                <h3>Icc: {{ currentIcc.icc }}</h3>

                                <h5>Compañia: {{ currentIcc.company.name }}</h5>

                                <h5>Tipo sim: {{ currentIcc.type.name }}</h5>

                                <h5>Estatus: {{ currentIcc.status }}</h5>

                                <div v-if="currentIcc.linea">
                                    <h5>
                                        Estatus Linea:
                                        {{ currentIcc.linea.status }}
                                        {{ currentIcc.linea.reason }}
                                    </h5>
                                </div>

                                <div v-else></div>

                                <b-form-group
                                    label="Numero"
                                    label-size="lg"
                                    class="mt-3"
                                >
                                    <b-input
                                        type="number"
                                        v-model="iccData.dn"
                                        placeholder="Ingresa numero de telefono"
                                        :disabled="disableLineaInputs"
                                    ></b-input>
                                </b-form-group>
                                <b-form-group
                                    label="Producto"
                                    label-size="lg"
                                    class="mt-3"
                                >
                                    <select-general
                                        url="/get/icc-products"
                                        pholder="Seleccionar Producto"
                                        v-model.number="iccData.iccProduct"
                                        :disabled="disableLineaInputs"
                                    >
                                    </select-general>
                                </b-form-group>
                                <b-form-group
                                    label="SubProducto"
                                    label-size="lg"
                                    class="mt-3"
                                    v-if="showSubProductoSelect"
                                >
                                    <select-general
                                        url="/get/icc-subproducts"
                                        :query="iccData.iccProduct.id"
                                        :query2="currentIcc.company.id"
                                        pholder="Seleccionar Subproducto"
                                        v-model="iccData.iccSubProduct"
                                        v-on:input="subproductUpdated"
                                    >
                                    </select-general>
                                </b-form-group>
                                <!-- inputs de linea nueva -->

                                <b-form-group
                                    label="Recarga"
                                    label-size="lg"
                                    class="mt-3"
                                    v-if="showRecargaInputs"
                                >
                                    <select-general
                                        url="/get/recargas"
                                        :query="currentIcc.company.id"
                                        pholder="Seleccionar Recarga"
                                        v-model="iccData.recarga"
                                        :disabled="disableRecargaInputs"
                                    >
                                    </select-general>
                                </b-form-group>
                                <!-- inputs de portabilidad -->
                                <div
                                    v-if="
                                        iccData.iccProduct &&
                                        iccData.iccProduct.id == 2
                                    "
                                >
                                    <b-form-group
                                        label="Nip"
                                        label-size="lg"
                                        class="mt-3"
                                    >
                                        <b-input
                                            placeholder="Nip"
                                            type="number"
                                            v-model="iccData.porta.nip"
                                        ></b-input>
                                    </b-form-group>

                                    <b-form-group
                                        label="Trafico"
                                        label-size="lg"
                                        class="mt-3"
                                    >
                                        
                                        <b-form-radio-group
                                            v-model="iccData.porta.trafico"
                                            buttons
                                            button-variant="primary"
                                            :options="[{text:'Si',value: true},{text:'No',value:false}]"
                                            
                                        ></b-form-radio-group>
                                    </b-form-group>

                                    <b-form-group
                                        label="Fvc"
                                        label-size="lg"
                                        class="mt-3"
                                    >
                                        <b-form-datepicker
                                            :max="fvc.max"
                                            :min="fvc.min"
                                            placeholder="Fecha ventana de cambio"
                                            v-model="iccData.porta.fvc"
                                        ></b-form-datepicker>
                                    </b-form-group>
                                </div>

                                <b-form-group class="mt-3">
                                    <b-button
                                        
                                        block
                                        @click="buildIcc"
                                    >
                                        Agregar
                                    </b-button>
                                </b-form-group>
                            </b-form>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-10 mx-auto">
                        <b-list-group>
                            <b-list-group-item
                                v-for="(producto, index) in productos"
                                :key="index"
                                >{{ producto.serie }}
                                {{ producto.descripcion }} {{ producto.precio }}
                            </b-list-group-item>
                        </b-list-group>
                    </div>
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
            iccDetails: false,

            isLoading: false,

            searchValue: null,

            searchResults: [],

            currentIcc: null,

            fvc: {
                min: minDate,

                max: maxDate,
            },

            iccData: {
                iccProduct: null,
                iccSubProduct: null,
                dn: null,
                recarga: null,
                porta: {
                    nip: null,
                    fvc: minDate,
                    trafico: null,
                },
            },

            productos: [],
        };
    },

    methods: {
        handleSearch: _.debounce(function () {
            this.searchProduct();
        }, 300),

        subproductUpdated() {
            if (
                this.iccData.iccSubProduct &&
                this.iccData.iccSubProduct.recarga_id
            ) {
                this.iccData.recarga = this.iccData.iccSubProduct.recarga_id;
            } else {
                this.iccData.recarga = null;
            }
        },

        buildIcc() {
            var icc = {
                id: this.currentIcc.id,
                serie: this.currentIcc.icc,
                status: this.currentIcc.status,
                inventario: this.currentIcc.inventario.inventarioable.name,
                dn: this.iccData.dn,
                iccProduct: this.iccData.iccProduct,
                iccSubProduct: this.iccData.iccSubProduct,
                recarga: this.iccData.recarga,
                porta: this.iccData.porta,
                type: "iccs",
            };

            if(this.iccData.iccSubProduct){
                icc.precio = this.iccData.iccSubProduct.precio;
            }
            if(this.iccData.iccProduct === 2){
                icc.descripcion = `Numero: ${icc.dn}   Producto: ${icc.iccProduct.name}`;
            }else{
                icc.descripcion = `Numero: ${icc.dn}   Producto: ${icc.iccProduct.name}   Subproducto: ${icc.iccSubProduct.name}`;
            }

            this.productos.unshift({ ...icc });

            this.closeIccDetails();
        },

        newIcc(icc) {
            this.iccDetails = true;

            this.currentIcc = icc;

            if (icc.linea) {
                this.iccData.dn = icc.linea.dn;

                this.iccData.iccProduct = icc.linea.icc_product_id;
            }
        },
        closeIccDetails() {
            this.iccDetails = false;

            this.currentIcc = null;

            this.iccData = {
                iccProduct: null,
                iccSubProduct: null,
                dn: null,
                recarga: null,
                porta: {
                    nip: null,
                    fvc: this.minDate,
                    trafico: null,
                },
            };
        },

        agregarProducto() {
            const CancelToken = axios.CancelToken;

            const source = CancelToken.source();

            axios
                .get(
                    "/search/venta-exact",
                    {
                        params: { search: this.searchValue },
                    },
                    {
                        cancelToken: source.token,
                    }
                )
                .then((response) => {
                    if (response.data.length > 0) {
                        switch (response.data[0].type) {
                            case "imeis":
                                const item = response.data[0].searchable;

                                const imei = {
                                    id: item.id,
                                    serie: item.imei,
                                    status: item.status,
                                    descripcion: `${item.equipo.marca}  ${item.equipo.modelo}`,
                                    inventario:
                                        item.inventario.inventarioable.name,
                                    precio: item.equipo.precio,
                                    type: "imeis",
                                };

                                this.productos.unshift({ ...imei });

                                break;
                            case "iccs":
                                this.newIcc(response.data[0].searchable);

                                break;
                        }
                    } else {
                        this.$bvToast.toast("No encontrado", {
                            title: `${this.searchValue}`,
                            variant: "warning",
                            solid: true,
                        });
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        searchProduct() {
            const self = this;
            if (this.searchValue.length >= 5) {
                axios
                    .get("/search/venta-prediction", {
                        params: { search: this.searchValue },
                    })
                    .then(function (response) {
                        self.searchResults = response.data;
                    })
                    .catch(function (thrown) {
                        if (axios.isCancel(thrown)) {
                            console.log("Request canceled", thrown.message);
                        } else {
                            // handle error
                        }
                    });
            } else {
                self.searchResults = [];
            }
        },
    },
    watch: {},
    computed: {
        showSubProductoSelect: function () {
            if (this.iccData.iccProduct && this.iccData.iccProduct.id != 2) {
                return true;
            } else {
                return false;
            }
        },
        disableLineaInputs: function () {
            if (this.currentIcc.linea) {
                return true;
            } else {
                return false;
            }
        },
        disableRecargaInputs: function () {
            if (this.iccData.iccSubProduct.recarga_id) {
                return true;
            } else {
                return false;
            }
        },

        showRecargaInputs: function () {
            if (
                this.iccData.iccSubProduct &&
                this.iccData.iccSubProduct.recarga_required == true
            ) {
                return true;
            } else {
                return false;
            }
        },
    },
};
</script>

<style></style>
