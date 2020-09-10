<template>
    <div>
        <b-navbar
            toggleable="lg"
            type="light"
            style="background-color: #dedede;"
        >
            <b-navbar-brand href="#">Ventas</b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
                <b-col sm="8">
                    <b-input-group>
                        <b-form-input
                            v-model="searchValue"
                            autocomplete="off"
                            placeholder="Buscar Producto"
                            list="search-results"
                            @update="searchProduct"
                        ></b-form-input>

                        <datalist id="search-results">
                            <option
                                v-for="(list, index) in searchResults"
                                :key="index"
                                >{{ list.title }}</option
                            >
                        </datalist>

                        <b-input-group-append>
                            <b-button variant="success" @click="agregarSerie"
                                >Agregar</b-button
                            >
                        </b-input-group-append>
                    </b-input-group>
                </b-col>

                <!-- Right aligned nav items -->
                <b-col sm="4">
                    <b-button v-b-modal.modal-producto-general
                        >Agregar venta general</b-button
                    >
                </b-col>
            </b-collapse>
        </b-navbar>
        <!-- cards -->
        <b-list-group v-if="showList == true">
            <b-list-group-item
                href="#"
                class="flex-column align-items-start"
                v-for="(item, index) in articulos"
                :key="index"
            >
                <div class="d-flex">
                    <div class="col-4 justify-content-start">
                        <h3 class="mb-1">{{ item.title }}</h3>

                        <h5 v-if="item.content.icc">
                            ICC: {{ item.content.icc }}
                        </h5>

                        <h5 v-if="item.content.imei">
                            Imei: {{ item.content.imei }}
                        </h5>

                        <h5 v-if="item.content.dn">
                            DN: {{ item.content.dn }}

                            <div
                                v-if="item.content.recargaDn"
                                class="badge badge-primary text-wrap"
                                style="width: 6rem;"
                            >
                                Recarga ${{ item.content.recargaDn }}
                            </div>
                        </h5>

                        <h5 v-if="item.content.dnTemporal">
                            DN TEMPORAL: {{ item.content.dnTemporal }}
                            <div
                                v-if="item.content.recargaDnTemporal"
                                class="badge badge-primary text-wrap"
                                style="width: 6rem;"
                            >
                                Recarga ${{ item.content.recargaDnTemporal }}
                            </div>
                        </h5>

                        <h5 v-if="item.content.description">
                            {{ item.content.description }}
                        </h5>
                    </div>
                    <div class="col-4 row justify-content-md-cente">
                        <p class="text-xl-left">
                            {{ item.content.description }}
                        </p>
                    </div>
                    <div class="col-4 row justify-content-end">
                        <small
                            ><b-button
                                variant="danger"
                                @click="eliminarItem(item, index)"
                                >Eliminar</b-button
                            >
                            <h5 class="mt-3">
                                Precio: ${{ item.precio }}
                            </h5></small
                        >
                    </div>
                </div>
            </b-list-group-item>
        </b-list-group>

        <!-- formulario de icc -->

        <b-form @reset="resetNewIcc" v-if="newIccMode" @submit.prevent="addIcc">
            <!-- aqui tengo que quitarlo solo si content.icc  -->

            <b-form-group label="Icc:">
                <b-form-input
                    id="icc"
                    type="text"
                    readonly
                    :value="nuevoIcc.content.icc"
                ></b-form-input>
            </b-form-group>

            <!-- selecciona el producto de icc  -->

            <b-form-group label="Producto:">
                <select-iccproduct
                    v-on:action="productChange"
                ></select-iccproduct>
            </b-form-group>

            <!-- selecciona el subproducto de icc -->
            <b-form-group
                label="Sub producto:"
                v-if="nuevoIcc.content.iccProductId"
            >
                <select-iccsubproduct
                    :parentProduct="nuevoIcc.content.iccProductId"
                    v-on:iccsubproducto="subproductChange"
                ></select-iccsubproduct>
            </b-form-group>
            <!-- insertar dn  -->
            <b-form-group
                label="DN:"
                label-for="dn"
                v-if="newIccSettings.dnRequired"
            >
                <b-form-input
                    id="dn"
                    type="number"
                    required
                    autocomplete="off"
                    max="999999999"
                    v-model="nuevoIcc.content.dn"
                ></b-form-input>
            </b-form-group>

            <!-- insertar dn temporal -->
            <b-form-group
                label="DN temporal:"
                label-for="dn_temporal"
                v-if="newIccSettings.dnTemporalRequired"
            >
                <b-input-group>
                    <b-form-input
                        id="dn_temporal"
                        type="number"
                        autocomplete="off"
                        max="999999999"
                        v-model="nuevoIcc.content.dnTemporal"
                    ></b-form-input>

                    <template v-slot:append>
                        <b-dropdown text="Recargar" variant="primary">
                            <b-dropdown-item>Action C</b-dropdown-item>
                            <b-dropdown-item>Action D</b-dropdown-item>
                        </b-dropdown>
                    </template>
                </b-input-group>
            </b-form-group>

            <!-- insertar nip -->
            <b-form-group
                label="NIP:"
                label-for="nip"
                v-if="newIccSettings.nipRequired"
            >
                <b-form-input
                    id="nip"
                    type="number"
                    autocomplete="off"
                    max="9999"
                    v-model="nuevoIcc.content.nip"
                ></b-form-input>
            </b-form-group>

            <!-- costo sim -->
            <b-form-group label="Costo sim:" label-for="costo_sim">
                <b-form-input
                    id="costo_sim"
                    type="number"
                    autocomplete="off"
                    max="9999"
                    v-model.number="nuevoIcc.content.costoSim"
                    placeholder="Costo de la tarjeta sim"
                ></b-form-input>
            </b-form-group>

            <b-form-input readonly :value="nuevaIccPrecio"></b-form-input>

            <!-- submit y reset buttons -->
            <b-button type="submit" variant="primary">Agregar</b-button>

            <b-button type="reset" variant="danger">Cancelar</b-button>
        </b-form>

        {{ totalVenta }}

        <!-- modal producto general -->
        <b-modal id="modal-producto-general">
            <b-form @submit="agregarGeneral">
                <b-form-input
                    placeholder="Nombre"
                    v-model="articulo.title"
                ></b-form-input>

                <b-form-input
                    placeholder="Descripcion"
                    v-model="articulo.content.description"
                ></b-form-input>

                <b-form-input
                    placeholder="precio"
                    v-model.number="articulo.precio"
                    type="number"
                ></b-form-input>

                <b-button type="submit" variant="primary">Agregar</b-button>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
export default {
    data() {
        return {
            searchValue: "",

            showList: true,

            newIccMode: false,

            cliente: {
                name: null,

                lastName: null,

                curp: null,

                referencia: null,

                email: null,

                rfc: null,
            },

            newIccSettings: {
                dnRequired: false,

                dnTemporalRequired: false,

                nipRequired: false,

                recargaRequired: false,

                initialPriceRequired: false,
            },

            searchResults: [],

            articulos: [],

            articulo: {
                title: "",
                content: {
                    description: "",
                },
                precio: null,
                type: "",
            },

            nuevoIcc: {
                title: "",
                content: {
                    icc: null,
                    iccProductId: null,
                    iccSubProductId: null,
                    dn: null,
                    dnTemporal: null,
                    nip: null,
                    costoSim: null,
                },
                precio: null,
                type: "iccs",
            },
        };
    },
    computed: {
        totalVenta: function () {
            var sum = 0;
            this.articulos.forEach(function (item) {
                sum += item.precio;
            });

            return sum;
        },
        nuevaIccPrecio: function () {
            var precio = 0;
            if (this.nuevoIcc.content.costoSim) {
                precio += this.nuevoIcc.content.costoSim;
            }
            return precio;
        },
    },
    methods: {
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
                    .catch(function (error) {
                        console.log(error);
                    });
            } else {
                self.searchResults = [];
            }
        },
        agregarSerie() {
            axios
                .get("/search/venta-exact", {
                    params: { search: this.searchValue },
                })
                .then(
                    function (response) {
                        console.log(response.data[0]);

                        const item = response.data[0];

                        var nuevaSerie = {};

                        switch (item.type) {
                            case "iccs":
                                this.newIcc(item.searchable.icc);

                                break;

                            case "imeis":
                                nuevaSerie = {
                                    title: `${item.searchable.equipo.marca}  ${item.searchable.equipo.modelo}`,

                                    content: {
                                        imei: item.searchable.imei,
                                    },

                                    precio: item.searchable.equipo.precio,

                                    type: item.type,
                                };
                                this.articulos.unshift(nuevaSerie);
                                break;
                            case "recargas":
                                this.addRecarga(item);

                                break;
                        }
                        // console.log(nuevaSerie);
                    }.bind(this)
                )
                .catch(function (error) {
                    console.log(error);
                });
                this.searchValue = null;
        },
        eliminarItem(item, index) {
            this.articulos.splice(index, 1);
        },
        addIcc() {
            this.nuevoIcc.precio = this.nuevaIccPrecio;
            console.log(this.nuevoIcc);
            this.articulos.unshift(this.nuevoIcc);
            this.newIccMode = false;
            this.showList = true;
            this.resetNewIcc();
        },
        addRecarga(item, numero) {
            
            var dn = null;

            if(!numero){
                dn = prompt("Please enter your name", "Harry Potter");
            }
            else{ dn = numero; }

            const nuevaRecarga = {
                title: `${item.searchable.name}`,

                content: {
                    dn: dn,
                },

                precio: item.searchable.monto,

                type: item.type,
            };
             this.articulos.unshift(nuevaRecarga);

        },
        newIcc(icc) {
            this.showList = false;

            this.newIccMode = true;

            this.nuevoIcc.content.icc = icc;
        },
        resetNewIcc() {
            this.nuevoIcc = {
                title: "",
                content: {
                    iccProductId: null,
                    iccSubProductId: null,
                    dn: null,
                    dnTemporal: null,
                    nip: null,
                    costoSim: null,
                },
                precio: null,
                type: "",
            };
            this.newIccMode = false;
            this.showList = true;
        },
        resetArticulo() {
            this.articulo = {
                title: "",
                content: {
                    description: "",
                },
                precio: 0,
                type: "",
            };
        },

        agregarGeneral(evt) {
            evt.preventDefault();

            this.articulo.type = "general";

            const nuevoItem = this.articulo;

            this.articulos.unshift(nuevoItem);

            this.resetArticulo();

            console.log(this.articulos);
        },
        productChange(value) {
            this.nuevoIcc.content.iccProductId = value.id;

            this.nuevoIcc.title = value.text;

            this.newIccSettings.dnRequired = value.settings.dnRequired;

            this.newIccSettings.dnTemporalRequired =
                value.settings.dnTemporalRequired;

            this.newIccSettings.nipRequired = value.settings.nipRequired;

            this.newIccSettings.recargaRequired =
                value.settings.recargaRequired;

            this.newIccSettings.initialPriceRequired =
                value.settings.initialPriceRequired;
        },
        subproductChange(value) {
            this.nuevoIcc.content.iccSubProductId = value.id;
        },
    },
};
</script>

<style></style>
