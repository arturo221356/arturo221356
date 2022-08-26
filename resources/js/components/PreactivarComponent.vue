<template>
    <div>
        <b-overlay :show="isLoading" rounded="sm">
            <b-navbar
                toggleable="lg"
                type="light"
                style="background-color: #dedede;"
            >
                <b-navbar-brand href="#">Preactivar</b-navbar-brand>

                <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

                <b-collapse id="nav-collapse" is-nav>
                    <b-navbar-nav> </b-navbar-nav>

                    <!-- Right aligned nav items -->
                    <b-navbar-nav class="ml-auto">
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
                    </b-navbar-nav>
                    <div></div>
                </b-collapse>
            </b-navbar>
            <div class="jumbotron jumbotron-fluid">
                <div class="col-md-8 mx-auto">
                    <h1>Preactivar lineas:</h1>

                    <validation-observer
                        ref="observer"
                        v-slot="{ handleSubmit }"
                        v-show="lineaDetail == false"
                    >
                        <b-form @submit.prevent="handleSubmit(verificarIcc)">
                            <ValidationProvider
                                name="icc"
                                v-slot="validationContext"
                                rules="required|Icc|numeric"
                            >
                                <b-form-group label="Icc:" label-size="lg">
                                    <b-input-group prepend="Icc">
                                        <b-form-input
                                            v-model.lazy="icc"
                                            type="number"
                                            list="search-results"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                            autocomplete="off"
                                            @keyup.stop="handleSearch"
                                            placeholder="Agregar Icc"
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
                                                variant="success"
                                                type="submit"
                                                >Agregar</b-button
                                            >
                                        </b-input-group-append>
                                        <b-form-invalid-feedback>{{
                                            validationContext.errors[0]
                                        }}</b-form-invalid-feedback>
                                    </b-input-group>
                                </b-form-group>
                            </ValidationProvider>
                            <b-alert
                                show
                                variant="danger"
                                v-if="verifyError"
                                dismissible
                                >{{ verifyError.message }}</b-alert
                            >

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
                                        {{ detail.icc
                                        }}<em v-if="detail.dn"
                                            >-- {{ detail.dn }}
                                        </em>
                                        <em
                                            v-for="(errs,
                                            index) in detail.errores"
                                            :key="index"
                                            >-- {{ errs }}</em
                                        >
                                    </li>
                                </ol>
                            </b-alert>

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

                            <Validation-Provider
                                v-if="can('asignar recarga')||is('super-admin')"
                                name="recargable"
                                v-slot="validationContext"
                                rules="required"
                            >
                                <b-form-group
                                    label="Recargables"
                                    label-size="lg"
                                    description="Aqui es para seleccionar la recarga de activacion. para la funcion de primera recarga ¡Activa chip! aplica para todas las lineas de la lista"
                                >
                                    <b-form-radio-group
                                        v-model="recargable"
                                        :options="[
                                            { text: 'Si', value: true },
                                            { text: 'No', value: false },
                                        ]"
                                        size="lg"
                                        :state="
                                            getValidationState(
                                                validationContext
                                            )
                                        "
                                    ></b-form-radio-group>
                                    <b-form-invalid-feedback>{{
                                        validationContext.errors[0]
                                    }}</b-form-invalid-feedback>
                                </b-form-group>
                            </Validation-Provider>
                            <Validation-Provider
                                v-if="recargable == true"
                                name="recarga"
                                v-slot="validationContext"
                                rules="required"
                            >
                                <b-form-group
                                    label="Monto de recarga"
                                    label-size="lg"
                                    description="Selecciona el monto de recarga para la activacion de la funcion ¡Activa chip!"
                                >
                                    <b-form-select
                                        v-model="montoRecarga"
                                        :options="recargaOptions"
                                    ></b-form-select>
                                    <b-form-invalid-feedback>{{
                                        validationContext.errors[0]
                                    }}</b-form-invalid-feedback>
                                </b-form-group>
                            </Validation-Provider>
                        </b-form>
                    </validation-observer>
                    <validation-observer
                        v-if="lineaDetail == true"
                        v-slot="{ handleSubmit }"
                    >
                        <div class="row">
                            <div class="col-sm">
                                <h1
                                    class="float-right"
                                    :style="{ cursor: 'pointer' }"
                                >
                                    <b-icon
                                        icon="x-circle-fill"
                                        variant="danger"
                                        @click="lineaDetail = false"
                                    ></b-icon>
                                </h1>
                            </div>
                        </div>
                        <b-form @submit.prevent="handleSubmit(agregarIcc)">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>
                                        <B>Compañia:</B>
                                        {{ currentIcc.company }}
                                    </h4>
                                </div>

                                <div class="col-md-12">
                                    <h4><B>Tipo:</B> {{ currentIcc.type }}</h4>
                                </div>

                                <div class="col-md-12">
                                    <h4><B>Icc:</B> {{ currentIcc.icc }}</h4>
                                </div>
                            </div>

                            <ValidationProvider
                                rules="numeric|required|length:10"
                                v-slot="validationContext"
                                name="Numero"
                            >
                                <b-form-group label="Numero" label-size="lg">
                                    <b-input
                                        v-model="currentIcc.dn"
                                        type="number"
                                        :state="
                                            getValidationState(
                                                validationContext
                                            )
                                        "
                                        placeholder="Insertar numero"
                                        autocomplete="off"
                                    ></b-input>
                                    <b-form-invalid-feedback>{{
                                        validationContext.errors[0]
                                    }}</b-form-invalid-feedback>
                                </b-form-group>
                            </ValidationProvider>

                            <b-button type="submit" block
                                >Agregar linea</b-button
                            >
                        </b-form>
                    </validation-observer>

                    <b-button
                        block
                        variant="success"
                        class="mt-3"
                        v-if="preactivarButtonVisible"
                        @click="preactivarIcc"
                        >Preactivar</b-button
                    >

                    <b-list-group class="mt-5">
                        <b-list-group-item
                            v-for="(articulo, index) in iccs"
                            :key="index"
                        >
                            {{ index + 1 }} :
                            <strong>{{ articulo.icc }}</strong>
                            <small>{{ articulo.company }}</small>
                            <small>{{ articulo.type }}</small>
                            <small>{{ articulo.dn }}</small>

                            <b-button
                                size="sm"
                                class="float-right"
                                variant="danger"
                                @click="eliminarIcc(articulo, index)"
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
            recargable: null,

            isLoading: false,

            icc: null,

            searchResults: [],

            montoRecarga: 50,

            iccs: [],

            lineaDetail: false,

            recargaOptions: [
                { value: 50, text: "Recarga de $50" },

                { value: 60, text: "Recarga de $60" },

                { value: 100, text: "Recarga de $100" },

                 { value: 150, text: "Recarga de $150" },

                
            ],

            file: null,

            alert: {
                show: false,
                title: "",
                variant: "",
                list: [],
            },

            verifyError: null,

            //array con respuesta del servidor con las series exitosas
            exitosos: [],
            //array con respuesta del servidor con las series erroneas
            errores: [],

            currentIcc: {
                icc: null,
                company: null,
                type: null,
                dn: null,
            },
        };
    },
    methods: {
        handleSearch: _.debounce(function () {
            this.searchProduct();
        }, 300),
        
        searchProduct(){

            self = this;
            if (this.icc >= 5) {
                axios
                    .get("/search/traspaso-prediction", {
                        params: { search: this.icc },
                    })
                    .then(function (response) {
                        self.searchResults = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            } else {
                self.searchResults = [];
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
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        verificarIcc() {
            if (this.iccs.some((item) => item.icc === this.icc)) {
                this.$bvToast.toast("Duplicado", {
                    title: `${this.icc}`,
                    variant: "warning",
                    solid: true,
                });
            } else {
                this.isLoading = true;
                axios
                    .post("/linea/verificar-icc", { icc: this.icc })
                    .then((response) => {
                        if (response.data.success == true) {
                            this.lineaDetail = true;

                            this.currentIcc.icc = response.data.data.icc;

                            this.currentIcc.company =
                                response.data.data.company.name;

                            this.currentIcc.type = response.data.data.type.name;

                            this.icc = null;
                        } else if (response.data.success == false) {
                            this.verifyError = response.data;
                            console.log(response.data);
                        }
                        this.isLoading = false;
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    });
            }
        },
        agregarIcc() {
            if (this.iccs.some((item) => item.dn === this.currentIcc.dn)) {
                this.$bvToast.toast("Duplicado", {
                    title: `${this.currentIcc.dn}`,
                    variant: "warning",
                    solid: true,
                });
            } else {
                const serie = this.currentIcc;

                this.iccs.unshift({ ...serie });

                this.icc = null;

                this.currentIcc = {
                    icc: null,
                    company: null,
                    type: null,
                    dn: null,
                };
                this.lineaDetail = false;

                this.verifyError = null;
            }
        },

        eliminarIcc(item, index) {
            this.iccs.splice(index, 1);
        },

        preactivarIcc() {
            this.isLoading = true;

             this.errores = [];

             this.exitosos = [];

            const settings = {
                headers: {
                    "content-type": "multipart/form-data",
                },
            };

            const data = new FormData();
            data.append("data", JSON.stringify(this.iccs));
            data.set("recargable", this.recargable);
            data.set("monto", this.montoRecarga);
            data.append("file", this.file);
            axios
                .post("/preactivar-prepago", data, settings)
                .then((response) => {
                    if (response.data.success == true) {
                        this.lineaDetail = true;

                        this.currentIcc.icc = response.data.data.icc;

                        this.currentIcc.company =
                            response.data.data.company.name;

                        this.currentIcc.type = response.data.data.type.name;

                        this.icc = null;

                        
                    }
                    this.iccs = [];

                    this.file = null;

                    this.isLoading = false;

                    this.errores = response.data.errors;

                    this.exitosos = response.data.success;

                    if(this.errores.length > 0){
                        this.$bvToast.toast("Ocurrieron algunos errores", {
                            title: `Errores`,
                            variant: "warning",
                            solid: true,
                        });
                    }else{
                        this.$bvToast.toast("Exitosa", {
                            title: `Preactivacion`,
                            variant: "success",
                            solid: true,
                        });
                    }

                    this.verifyError = null;

                    
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
        },
    },
    watch: {


    },
    computed: {
        preactivarButtonVisible: function () {
            if (this.lineaDetail == true) {
                return false;
            } else if (this.file == null && this.iccs.length == 0) {
                return false;
            } else {
                return true;
            }
        },

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
    },
};
</script>

<style></style>
