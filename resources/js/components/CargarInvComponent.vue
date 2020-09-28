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
                                :options="options"
                                buttons
                                name="radios-btn-default"
                                @click.native="confirmProductChange($event)"
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
                                countErrors
                            }}</b-badge></b-button
                        >

                        <b-button
                            variant="success"
                            size="sm"
                            @click="exitososButton"
                            v-if="exitosos.length > 0"
                            >Exitosos
                            <b-badge variant="light">{{
                                countSuccess
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

                    <h1>Agregar {{ producto }}:</h1>

                    <!-- nuevo formulario -->
                    <validation-observer
                        ref="observer"
                        v-slot="{ handleSubmit }"
                    >
                        <b-form @submit.prevent="handleSubmit(agregarSerie)">
                            <!-- valida que el producto sea 1 imei  -->
                            <ValidationProvider
                                v-slot="validationContext"
                                rules="required"
                                v-if="producto == 'Imei'"
                            >
                                <b-form-group label="Equipo" label-size="lg">
                                    <select-general
                                        url="/get/equipos"
                                        pholder="Seleccionar Equipo"
                                        v-model="item.equipo"
                                        :state="
                                            getValidationState(
                                                validationContext
                                            )
                                        "
                                        :equipo="true"
                                    ></select-general>

                                    <b-form-invalid-feedback
                                        ><b-form-invalid-feedback>{{
                                            validationContext.errors[0]
                                        }}</b-form-invalid-feedback></b-form-invalid-feedback
                                    >
                                </b-form-group>
                            </ValidationProvider>

                            <ValidationProvider
                                v-slot="validationContext"
                                rules="required"
                                v-if="producto == 'Icc'"
                            >
                                <b-form-group label="Compañia" label-size="lg">
                                    <select-general
                                        url="/get/companies"
                                        pholder="Seleccionar Compañia"
                                        v-model="item.company"
                                        :state="
                                            getValidationState(
                                                validationContext
                                            )
                                        "
                                    ></select-general>

                                    <b-form-invalid-feedback
                                        ><b-form-invalid-feedback>{{
                                            validationContext.errors[0]
                                        }}</b-form-invalid-feedback></b-form-invalid-feedback
                                    >
                                </b-form-group>
                            </ValidationProvider>

                            <!-- grupo para inventario -->
                            <ValidationProvider
                                v-slot="validationContext"
                                rules="required"
                            >
                                <b-form-group
                                    label="Inventario"
                                    label-for="select-inventario"
                                    label-size="lg"
                                >
                                    <select-general
                                        url="/inventario"
                                        pholder="Seleccionar Inventario"
                                        v-model="item.inventario"
                                        :state="
                                            getValidationState(
                                                validationContext
                                            )
                                        "
                                    >
                                    </select-general>
                                    <b-form-invalid-feedback
                                        ><b-form-invalid-feedback>{{
                                            validationContext.errors[0]
                                        }}</b-form-invalid-feedback></b-form-invalid-feedback
                                    >
                                </b-form-group>
                            </ValidationProvider>

                            <!-- grupo para tipo de icc -->
                            <ValidationProvider
                                v-slot="validationContext"
                                rules="required"
                                v-if="item.company"
                            >
                                <b-form-group
                                    label="Tipo de Tarjeta sim"
                                    label-size="lg"
                                >
                                    <select-general
                                        url="/get/icctypes"
                                        pholder="Seleccionar tipo de sim"
                                        v-model="item.simType"
                                        :query="item.company.id"
                                        :state="
                                            getValidationState(
                                                validationContext
                                            )
                                        "
                                    >
                                    </select-general>
                                    <b-form-invalid-feedback
                                        ><b-form-invalid-feedback>{{
                                            validationContext.errors[0]
                                        }}</b-form-invalid-feedback></b-form-invalid-feedback
                                    >
                                </b-form-group>
                            </ValidationProvider>

                            <!-- valida la entrada de serie -->
                            <ValidationProvider
                                v-slot="validationContext"
                                :rules="`${
                                    serieRequired ? 'required' : ''
                                }|numeric|${producto}`"
                                :name="producto"
                            >
                                <b-form-group
                                    :label="producto"
                                    label-for="serie"
                                    label-size="lg"
                                    :invalid-feedback="
                                        validationContext.errors[0]
                                    "
                                    :state="
                                        getValidationState(validationContext)
                                    "
                                >
                                    <b-input-group>
                                        <b-input
                                            :placeholder="`Ingresa 1 ${producto}`"
                                            name="serie"
                                            v-model="item.serie"
                                            autocomplete="off"
                                            type="number"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                        ></b-input>

                                        <b-input-group-append>
                                            <b-button
                                                variant="success"
                                                type="submit"
                                                >Agregar</b-button
                                            >
                                        </b-input-group-append>
                                    </b-input-group>
                                </b-form-group>
                            </ValidationProvider>
                            <!-- agregar excel -->
                            <b-form-group label="Excel" label-size="lg">
                                <b-form-file
                                    v-model="file"
                                    :state="Boolean(file)"
                                    placeholder="Agregar archivo Excel"
                                    drop-placeholder="Arrastra el archivo aqui"
                                    browse-text="Excel"
                                    accept=".xlsx, .csv"
                                ></b-form-file>
                            </b-form-group>
                        </b-form>
                    </validation-observer>

                    <b-form-group :description="`Cantidad: ${items.length}`">
                        <b-button
                            block
                            variant="primary"
                            @click="sendData()"
                            type="submit"
                            :disabled="agregarButtonDisabled"
                            >Agregar a Inventario</b-button
                        >
                    </b-form-group>

                    <b-list-group class="d-flex justify-content-between">
                        <b-list-group-item
                            v-for="(articulo, index) in items"
                            :key="index"
                        >
                            {{ index + 1 }} :
                            <strong>{{ articulo.serie }}</strong>
                            <small>{{ articulo.inventario.name }}</small>
                            <small v-if="producto === 'Imei'"
                                >{{ articulo.equipo.marca }}
                                {{ articulo.equipo.modelo }} ${{
                                    articulo.equipo.precio
                                }}</small
                            >
                            <small v-if="producto === 'Icc'"
                                >{{ articulo.company.name }}
                                {{ articulo.simType.name }}</small
                            >
                            <b-button
                                size="sm"
                                class="float-right"
                                variant="danger"
                                @click="eliminarSerie(articulo, index)"
                                >Eliminar</b-button
                            >
                        </b-list-group-item>
                    </b-list-group>
                </div>
            </div>
        </b-overlay>
    </div>
</template>

<script>
export default {
    data() {
        return {
            producto: "Imei",

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

            postUrl: "/imei",

            isLoading: false,

            //valores actuales del formulario
            item: {
                serie: null,
                inventario: null,
                company: null,
                simType: null,
                equipo: null,
            },
            serieRequired: true,

            items: [],
            //Archivo de excel
            file: null,
            //opciones del radio de productos
            options: [
                { text: "Imeis", value: "Imei" },
                { text: "Icc", value: "Icc" },
                { text: "Otros", value: "otros" },
            ],
        };
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },

        confirmProductChange(e) {
            if (this.items.length > 0) {
                if (!confirm("Desea continuar se borrara la informacion??")) {
                    e.preventDefault();
                } else {
                }
            }
        },

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
        clearItem() {
            Object.keys(this.item).forEach((k) => delete this.item[k]);
        },
        clearItems() {
            this.items = [];
        },

        //agrega serie a array items
        agregarSerie() {
            const serie = this.item;

            this.items.unshift({ ...serie });

            this.item.serie = "";
        },
        //elimina la serie del array items
        eliminarSerie(item, index) {
            this.items.splice(index, 1);
        },
        //envia los datos a laravel para su almacenamiento
        sendData() {
            this.isLoading = true;

            const self = this;

            const settings = {
                headers: {
                    "content-type": "multipart/form-data",
                },
            };

            const data = new FormData();
            data.append("data", JSON.stringify(this.items));
            data.append("file", this.file);
            if (this.producto == "Icc") {
                data.set("icc_type_id", this.item.simType.id);
                data.set("company_id", this.item.company.id);
            } else if (this.producto == "Imei") {
                data.set("equipo_id", this.item.equipo.id);
            }
            data.set("inventario_id", this.item.inventario.id);

            axios.post(this.postUrl, data, settings).then(function (response) {
                console.log(response.data);

                self.isLoading = false;

                self.errores = response.data.errors;

                self.exitosos = response.data.success;
            });

            this.items = [];

            this.file = null;
        },
    },
    computed: {
        //cuenta los errores de la peticion send data
        countErrors() {
            if (this.errores) {
                return this.errores.length;
            }
        },
        //cuenta los valores exitosos de la peticion send data
        countSuccess() {
            if (this.exitosos) {
                return this.exitosos.length;
            }
        },
        agregarButtonDisabled() {
            if (
                this.item.inventario == null ||
                (this.items.length == 0 && this.file == null)
            ) {
                return true;
            } else {
                if (this.producto == "Imei" && this.item.equipo == null) {
                    return true;
                } else if (
                    this.producto == "Icc" &&
                    (this.item.company == null || this.item.simType == null)
                ) {
                    return true;
                } else {
                    return false;
                }
            }
        },
    },
    watch: {
        producto: function () {
            if (this.producto == "Imei") {
                this.postUrl = "/imei";
            } else if (this.producto == "Icc") {
                this.postUrl = "/icc";
            }
            this.clearItem();

            this.clearItems();
        },
    },
};
</script>
