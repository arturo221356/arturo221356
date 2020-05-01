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
                                v-model="producto"
                                :options="options"
                                buttons
                                name="radios-btn-default"
                                @input="productoChange"
                            >
                            </b-form-radio-group>
                        </b-nav-form>

                        <b-nav-item>
                            <b-button
                                variant="outline-success"
                                squared
                                :pressed.sync="excelMode"
                                >Importar de Excel</b-button
                            >
                        </b-nav-item>
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

                    <b-form @submit="agregarserie">
                        <!-- grupo para producto -->
                        <b-form-group
                            v-if="producto === 'Imei'"
                            label="Equipo"
                            label-for="select-equipo"
                            label-size="lg"
                            :invalid-feedback="equipoValidation.message"
                            :state="false"
                        >
                            <select-equipo
                                seleccionado=""
                                name="select-equipo"
                                v-on:equipo="equipoChange"
                            ></select-equipo>
                        </b-form-group>
                        <!-- grupo para sucursal -->
                        <b-form-group
                            label="Sucursal"
                            label-for="select-sucursal"
                            label-size="lg"
                            :invalid-feedback="sucursalValidation.message"
                            :state="false"
                        >
                            <select-sucursal
                                name="select-sucursal"
                                v-on:sucursal="sucursalChange"
                            ></select-sucursal>
                        </b-form-group>
                        <!-- gurpo para serie -->
                        <b-form-group
                            :label="producto"
                            label-for="serie"
                            label-size="lg"
                            :invalid-feedback="serieValidation.message"
                            :state="serieValidation.input"
                            v-if="excelMode === false"
                        >
                            <b-input-group>
                                <b-input
                                    :placeholder="`Ingresa 1 ${producto}`"
                                    name="serie"
                                    v-model="item.serie"
                                    autocomplete="off"
                                    :state="serieValidation.input"
                                    type="number"
                                ></b-input>

                                <b-input-group-append>
                                    <b-button
                                        variant="outline-success"
                                        type="submit"
                                        :disabled="validationFails"
                                        >Agregar</b-button
                                    >
                                </b-input-group-append>
                            </b-input-group>
                        </b-form-group>

                        <b-form-group
                            label="Excel"
                            label-size="lg"
                            v-if="excelMode === true"
                        >
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

                    <b-form-group :description="`Cantidad: ${items.length}`">
                        <b-button block variant="primary" @click="sendData()"
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
                            <small>{{ articulo.sucursalText }}</small>
                            <small v-if="producto === 'Imei'">{{
                                articulo.equipoText
                            }}</small>
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
            excelMode: false,

            file: null,

            exitosos: [],

            errores: [],

            postUrl: "/admin/imei",

            lengthRequired: 15,

            isLoading: false,

            item: {
                serie: null,
                sucursal: null,
                sucursalText: null,
                equipo: null,
                equipoText: null,
            },

            items: [],

            file: null,

            options: [
                { text: "Imeis", value: "Imei" },
                { text: "Icc", value: "Icc" },
                { text: "Otros", value: "otros" },
            ],
        };
    },
    methods: {
        productoChange() {
            this.items = [];

            if (this.producto == "Imei") {
                this.lengthRequired = 15;
                this.postUrl = "/admin/imei";
            } else if (this.producto == "Icc") {
                this.lengthRequired = 19;
                this.postUrl = "/admin/icc";
            }
        },

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

        sucursalChange(value) {
            this.item.sucursal = value.id;

            this.item.sucursalText = value.text;
        },

        equipoChange(value) {
            this.item.equipo = value.id;

            this.item.equipoText = value.text;

            console.log(this.item);
        },

        agregarserie(evt) {
            evt.preventDefault();

            const nuevoItem = this.item;

            this.items.unshift(nuevoItem);

            this.item = {
                serie: "",
                sucursal: this.item.sucursal,
                equipo: this.item.equipo,
                sucursalText: this.item.sucursalText,
                equipoText: this.item.equipoText,
            };

            console.log(this.items);
        },

        eliminarSerie(item, index) {
            this.items.splice(index, 1);
        },
        //envia los datos a laravel para su almacenamiento
        sendData() {
            this.isLoading = true;

            if (this.items.length > 0) {
                var postData = {
                    data: this.items,
                };
                var self = this;
                axios.post(this.postUrl, postData).then(function (response) {
                    console.log(response.data);

                    self.isLoading = false;

                    self.errores = response.data.errors;

                    self.exitosos = response.data.success; 
                });

                this.items = [];
            }

            if (this.file) {
                const formData = new FormData();
                formData.append("file", this.file);
                formData.set("sucursal_id", this.item.sucursal);
                formData.set("equipo_id", this.item.equipo);

                let settings = {
                    headers: { "content-type": "multipart/form-data" },
                };

                var self = this;
                axios
                    .post(this.postUrl, formData, settings)
                    .then(function (response) {
                        console.log(response.data);

                        self.isLoading = false;

                        self.errores = response.data.errors;

                        self.exitosos = response.data.success;
                    });
                
                this.file = null;
            }
        },
    },
    computed: {
        //validacion general del formulario para bloquear el boton agregar serie
        validationFails() {
            if (
                this.serieValidation.validation == true ||
                this.sucursalValidation.validation == true
            ) {
                return true;
            } else {
                if (
                    this.producto === "Imei" &&
                    this.equipoValidation.validation == true
                ) {
                    return true;
                } else {
                    return false;
                }
            }
        },
        //cuenta los errores
        countErrors() {
            if (this.errores) {
                return this.errores.length;
            }
        },
        countSuccess() {
            if (this.exitosos) {
                return this.exitosos.length;
            }
        },

        //validacion de el campo sucursal que no este vacio
        sucursalValidation() {
            var validationFails = {
                validation: true,
                message: "Sucursal requerida",
            };
            if (this.item.sucursal) {
                validationFails = false;
            }
            return validationFails;
        },
        equipoValidation() {
            var validationFails = {
                validation: true,
                message: "Equipo requerida",
            };
            if (this.item.equipo) {
                validationFails = false;
            }
            return validationFails;
        },

        //valida que las series de icc o de imei tengan los caracteres necesarios y que no esten repetidos dentro del array de objetos
        serieValidation() {
            //true for error
            var validationFails = {
                input: null,
                validation: true,
                message: "",
            };

            if (this.item.serie) {
                if (this.item.serie.length != this.lengthRequired) {
                    validationFails.input = false;
                    validationFails.message = `${this.producto} debe ser de ${this.lengthRequired} digitos`;
                } else {
                    if (this.items.some((e) => e.serie === this.item.serie)) {
                        validationFails.input = false;
                        validationFails.validation = true;
                        validationFails.message = `${this.producto} repetido`;
                    } else {
                        validationFails.input = true;
                        validationFails.validation = false;
                    }
                }
            }

            return validationFails;
        },
    },
};
</script>
