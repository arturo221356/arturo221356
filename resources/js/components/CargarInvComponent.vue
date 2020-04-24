<template>
    <div>
        <b-overlay :show="busy" rounded="sm">
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
                    </b-navbar-nav>

                    <!-- Right aligned nav items -->
                    <b-navbar-nav class="ml-auto"> </b-navbar-nav>
                </b-collapse>
            </b-navbar>

            <div class="jumbotron">
                <div class="col-md-11 mx-auto">
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
                        >
                            <b-input-group>
                                <b-input
                                    :placeholder="`Ingresa 1 ${producto}`"
                                    name="serie"
                                    v-model="item.serie"
                                    autocomplete="off"
                                    :state="serieValidation.input"
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

                    <b-form-group :description="`Cantidad: ${items.length}`">
                        <b-button block variant="primary"
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

            busy: false,

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
                { text: "Imeis", value: "Imei", disabled: this.busy },
                { text: "Icc", value: "Icc", disabled: this.busy },
                { text: "Otros", value: "otros", disabled: this.busy },
            ],
        };
    },
    methods: {
        productoChange() {
            this.items = [];
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
                if (this.producto === "Imei" && this.equipoValidation.validation == true) {
                    return true;
                } else {
                    return false;
                }
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
            var lengthRequired = 0;

            if (this.producto == "Imei") {
                lengthRequired = 15;
            } else if (this.producto == "Icc") {
                lengthRequired = 20;
            }
            if (this.item.serie) {
                if (this.item.serie.length != lengthRequired) {
                    validationFails.input = false;
                    validationFails.message = `${this.producto} debe ser de ${lengthRequired} digitos`;
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
