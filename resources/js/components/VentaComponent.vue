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
                                            @click="iccDetails = false"
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
                                    con linea
                                </div>

                                <div v-else></div>
                                <b-form-group
                                    label="Producto"
                                    label-size="lg"
                                    class="mt-3"
                                >
                                    <select-general
                                        url="/get/icc-products"
                                        pholder="Seleccionar Producto"
                                        
                                        v-model="iccData.iccProduct"
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
                                        pholder="Seleccionar Subproducto"
                                        :empty="true"
                                       
                                    >
                                    </select-general>
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
        return {
            iccDetails: false,

            isLoading: false,

            searchValue: null,

            searchResults: [],

            currentIcc: null,

            iccData:{
                iccProduct: null,
                iccSubProduct: null,
            },

            productos: [],
        };
    },

    methods: {
        handleSearch: _.debounce(function () {
            this.searchProduct();
        }, 300),

        newIcc(icc) {
            this.iccDetails = true;

            this.currentIcc = icc;

            console.log(icc);
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

                                this.productos.unshift(imei);

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
                        console.log(response.data);

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
    watch:{
        
    },
    computed: {
        showSubProductoSelect: function(){
            
            if(this.iccData.iccProduct){
                return true;
            }else{
                return false;
            }
            
            
           
        }
    }
};
</script>

<style></style>
