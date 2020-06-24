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
                            <b-button
                                variant="success"
                                @click="agregarSerie"
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

                        <h5 v-if="item.content.dn_temporal">
                            DN TEMPORAL: {{ item.content.dn_temporal }}
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

        <b-form v-if="newIccMode" @reset="resetNewIcc">
            <b-form-group
                id="input-group-1"
                label="Icc:"
                label-for="icc"
            
            >
                <b-form-input
                    id="icc"
                    type="text"
                    readonly
                    :value="nuevoIcc.icc"
                ></b-form-input>
            </b-form-group>

            <b-form-group
                id="input-group-2"
                label="Your Name:"
                label-for="input-2"
            >
                <b-form-input
                    id="input-2"
                    required
                    placeholder="Enter name"
                ></b-form-input>
            </b-form-group>

            <b-form-group id="input-group-3" label="Food:" label-for="input-3">
                <b-form-select
                    id="input-3"
                    
                    required
                ></b-form-select>
            </b-form-group>

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

            nuevoIcc:{}
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
            const self = this;

            axios
                .get("/search/exact-search", {
                    params: { search: this.searchValue },
                })
                .then(function (response) {
                    console.log(response.data[0]);

                    const item = response.data[0];

                    var nuevaSerie = {};

                    switch (item.type) {
                        case "iccs":
                            self.newIcc(item.searchable.icc);

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
                            self.articulos.unshift(nuevaSerie);
                            break;
                    }
                    // console.log(nuevaSerie);
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        eliminarItem(item, index) {
            this.articulos.splice(index, 1);
        },
        newIcc(icc) {
            
            this.showList = false;

            this.newIccMode = true;

            this.nuevoIcc.icc = icc;
        },
        resetNewIcc(){
            this.nuevoIcc = {};
            this.newIccMode = false;
            this.showList = true;
        },


        agregarGeneral(evt) {
            evt.preventDefault();

            this.articulo.type = "general";

            const nuevoItem = this.articulo;

            this.articulos.unshift(nuevoItem);

            this.articulo = {
                title: "",
                content: {
                    description: "",
                },
                precio: 0,
                type: "",
            };

            console.log(this.articulos);
        },
    },
};
</script>

<style></style>
