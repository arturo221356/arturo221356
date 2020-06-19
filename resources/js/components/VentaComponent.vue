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
                
               
                    <b-row>
                        <b-col  sm="8">
                        <b-form-input
                            v-model="searchValue"
                            autocomplete="off"
                            placeholder="Buscar Producto"
                          
                            list="search-results"
                            @keyup="searchProduct"
                            
                        ></b-form-input>
                       
                        <datalist id="search-results" >
                            <option
                                v-for="(list, index) in searchResults"
                                :key="index"
                                >{{list.title}}</option
                            >
                            
                        </datalist>
                        </b-col>

                
                
                
                <!-- Right aligned nav items -->
                <b-col sm="4">
                    <b-button v-b-modal.modal-producto-general
                        >Agregar venta general</b-button
                    >
                </b-col>
                </b-row>
            </b-collapse>
        </b-navbar>
        <!-- cards -->
        <b-list-group>
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
            if(this.searchValue.length >= 5){
            
            axios
                .get("/pruebas",{params:{search: this.searchValue}})
                .then(function (response) {
                    
                    console.log(response.data);

                    self.searchResults = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });}
                else{
                    self.searchResults = [];
                }
        },
        eliminarItem(item, index) {
            this.articulos.splice(index, 1);
        },

        agregarGeneral(evt) {
            evt.preventDefault();

            this.articulo.type = "general";

            this.articulo.content.dn = "3310512007 ";

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
