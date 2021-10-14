<template>
    <div>
        <b-overlay :show="isLoading" rounded="sm">
            <b-navbar
                toggleable="lg"
                type="light"
                style="background-color: #dedede;"
            >
                <b-navbar-brand href="#">Cargar Inventario</b-navbar-brand>

                <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

                <b-collapse id="nav-collapse" is-nav>
                    <b-navbar-nav>
                        <b-nav-form>
                            <b-form-radio-group
                                v-model.lazy="producto"
                                :options="productOptions"
                                buttons
                                name="radios-btn-default"
                            >
                            </b-form-radio-group>
                        </b-nav-form>
                    </b-navbar-nav>

                    <!-- Right aligned nav items -->
                    <b-navbar-nav class="ml-auto"> </b-navbar-nav>
                    <div>
                        <b-button
                            variant="danger"
                            size="sm"
                            @click="erroresButton"
                            v-if="errores.length > 0"
                            >Errores
                            <b-badge variant="light">{{
                                errores.length
                            }}</b-badge></b-button
                        >

                        <b-button
                            variant="success"
                            size="sm"
                            @click="exitososButton"
                            v-if="exitosos.length > 0"
                            >Exitosos
                            <b-badge variant="light">{{
                                exitosos.length
                            }}</b-badge></b-button
                        >
                    </div>
                </b-collapse>
            </b-navbar>
            <div class="jumbotron">
                <div class="col-md-11 mx-auto">
                    <b-alert
                        dismissible
                        v-model="alert.show"
                        :variant="alert.variant"
                    >
                        <h4 class="alert-heading">{{ alert.title }}</h4>

                        {{ alert.message }}

                        <ol v-if="alert.list.length > 0">
                            <li
                                v-for="(detail, index) in alert.list"
                                :key="index"
                            >
                                {{ detail.serie }}
                                <em
                                    v-for="(errs, index) in detail.errores"
                                    :key="index"
                                    >-- {{ errs }}</em
                                >
                            </li>
                        </ol>
                    </b-alert>

                    <!-- </ValidationProvider> -->

                    <div v-if="producto == 'Imei'">
                        <imei-product
                            v-on:is-loading="isLoading = $event"
                            v-on:exitosos="exitosos = $event"
                            v-on:errores="errores = $event"
                        ></imei-product>
                    </div>
                    <div v-if="producto == 'Icc'">
                        <icc-product
                            v-on:is-loading="isLoading = $event"
                            v-on:exitosos="exitosos = $event"
                            v-on:errores="errores = $event"
                        ></icc-product>
                    </div>
                    <div v-if="producto == 'Otros'">
                        <otros-product
                            v-on:is-loading="isLoading = $event"
                            v-on:exitosos="exitosos = $event"
                            v-on:errores="errores = $event"
                        ></otros-product>
                    </div>
                </div>
            </div>
        </b-overlay>
    </div>
</template>

<script>
import ImeiProduct from "./cargarinvcomponents/ImeiProductComponent.vue";
import IccProduct from "./cargarinvcomponents/IccProductComponent.vue"; 
import OtrosProduct from "./cargarinvcomponents/OtroProductComponent.vue";

export default {
    components: {
        ImeiProduct,
        OtrosProduct,
        IccProduct,
       
    },
    data() {
        return {
            isLoading: false,

            producto: "Imei",

            file: null,

            inventario: null,

            productOptions: [
                { text: "Imeis", value: "Imei" },
                { text: "Icc", value: "Icc" },
                { text: "Otros", value: "Otros" },
            ],
            alert: {
                show: false,
                title: "",
                variant: "",
                list: [],
            },

            //array con respuesta del servidor con las series exitosas
            exitosos: [],
            //array con respuesta del servidor con las series erroneas
            errores: [],
        };
    },

    methods: {
        //crea el boton de errores y regresa un alert con los valores
        erroresButton() {
            this.alert.show = true;
            this.alert.title = "Errores";
            this.alert.variant = "danger";

            if (this.errores.length > 0) {
                this.alert.list = this.errores;
            } else {
                this.alert.list = [];
            }
        },
        //crea el boton de exitosos y regresa un alert con los valores
        exitososButton() {
            this.alert.show = true;

            this.alert.variant = "success";

            this.alert.title = "Exitosos";
            if (this.exitosos.length > 0) {
                this.alert.list = this.exitosos;
            } else {
                this.alert.list = [];
            }
        },
    },
};
</script>
