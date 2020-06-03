<template>
    <div>
        <b-navbar
            toggleable="lg"
            type="light"
            style="background-color: #dedede;"
        >
            <b-navbar-brand href="#">Productos Icc</b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav>
                    <b-nav-item>
                        <b-link
                            v-if="createMode == false"
                            @click="createMode = true"
                            >Agregar Producto</b-link
                        >
                        <b-link
                            v-if="createMode == true"
                            @click="createMode = false"
                            >Cancelar</b-link
                        >
                    </b-nav-item>

                    <b-nav-item> </b-nav-item>
                </b-navbar-nav>

                <!-- Right aligned nav items -->
                <b-navbar-nav class="ml-auto">
                    <!-- <b-form-input
                        type="search"
                        v-model="filter"
                        placeholder="Buscar"
                    ></b-form-input> -->
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>

        <b-tabs
            fill
            content-class="mt-3"
            v-if="createMode == false"
            pills
            class="mt-3"
        >
            <b-tab
                v-for="product in products"
                :key="product.id"
                :title="product.name"
            >
                <iccsubproduct-component :product_id="product.id"></iccsubproduct-component>
            </b-tab>
        </b-tabs>

        <b-container v-if="createMode == true" class="mt-5">
            <validation-observer ref="observer" v-slot="{ handleSubmit }">
                <b-form @submit.prevent="handleSubmit(storeProducto)">
                    <ValidationProvider
                        name="nombre"
                        v-slot="validationContext"
                        rules="required|min:3"
                        autocomplete="off"
                    >
                        <b-form-group label="Nombre">
                            <b-form-input
                                type="text"
                                v-model="producto.name"
                                placeholder="Nombre"
                                :state="getValidationState(validationContext)"
                            >
                            </b-form-input>
                            <b-form-invalid-feedback>{{
                                validationContext.errors[0]
                            }}</b-form-invalid-feedback>
                        </b-form-group>

                        <b-form-group label="Requiere Recarga?">
                            <b-form-radio-group
                                id="btn-radios-1"
                                v-model="producto.recarga_required"
                                :options="YesOrNo"
                                buttons
                                name="radios-btn-default"
                            ></b-form-radio-group>
                        </b-form-group>

                        <b-form-group label="Tiene precio inicial?">
                            <b-form-radio-group
                                id="btn-radios-1"
                                v-model="producto.initial_price"
                                :options="YesOrNo"
                                buttons
                                name="radios-btn-default"
                            ></b-form-radio-group>
                        </b-form-group>

                        <b-form-group label="puede tener costo el sim?">
                            <b-form-radio-group
                                id="btn-radios-1"
                                v-model="producto.sim_cost"
                                :options="YesOrNo"
                                buttons
                                name="radios-btn-default"
                            ></b-form-radio-group>
                        </b-form-group>

                        <!-- <b-form-group label="Se le aplicara la recarga externa?">
                            <b-form-radio-group
                                id="btn-radios-1"
                                v-model="producto.sim_cost"
                                :options="YesOrNo"
                                buttons
                                name="radios-btn-default"
                            ></b-form-radio-group>
                        </b-form-group> -->
                    </ValidationProvider> 

                    <b-button size="sm" variant="primary" type="submit">
                        Guardar
                    </b-button>
                </b-form>
            </validation-observer>
        </b-container>

        <!-- info modal -->
    </div>
</template>

<script>
export default {
    data() {
        return {
            filter: null,

            products: [],

            createMode: false,

            infoModal: {
                id: "info-modal",
                title: "",
                content: "",
                itemId: "",
            },
            producto: {
                name: null,
                recarga_required: false,
                initial_price:false,
                sim_cost:false,
            },
            YesOrNo: [
                { text: "No", value: false },
                { text: "Si", value: true },
            ],
           
        };
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },

        loadProductos() {
            this.isBusy = true;
            axios
                .get("/admin/productos/sims")
                .then((response) => {
                    this.products = response.data;
                    this.isBusy = false;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
        },

        storeProducto() {
            const params = {
                name: this.producto.name,
                recarga_required: this.producto.recarga_required,
                initial_price: this.producto.initial_price,
                sim_cost: this.producto.sim_cost,

            };
            axios.post(`/admin/productos/sims`, params).then((res) => {
                this.loadProductos();

                this.$bvToast.toast(`Producto Agregada con exito`, {
                    title: "Exito",
                    autoHideDelay: 5000,
                    appendToast: true,
                    solid: true,
                    variant: "success",
                    toaster: "b-toaster-bottom-full",
                });
            });
        },
    },
    created() {
        this.loadProductos();
    },
};
</script>

<style></style>
