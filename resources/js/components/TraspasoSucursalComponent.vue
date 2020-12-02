<template>
    <div>
        <b-overlay :show="isLoading" rounded="sm">
            <b-navbar
                toggleable="lg"
                type="light"
                style="background-color: #dedede;"
            >
                <b-navbar-brand href="#">Traspaso</b-navbar-brand>

                <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

                <b-collapse id="nav-collapse" is-nav>
                    <b-navbar-nav>
                        <b-nav-form>
                            <b-form-radio-group
                                v-model.lazy="traspasoType"
                                :options="traspasoOptions"
                                buttons
                                v-if="can('traspasar stock')"
                            >
                            </b-form-radio-group>
                        </b-nav-form>
                    </b-navbar-nav>

                    <!-- Right aligned nav items -->
                    <b-navbar-nav class="ml-auto"> </b-navbar-nav>
                    <div>
                        a la derecha
                    </div>
                </b-collapse>
            </b-navbar>
            <div class="jumbotron jumbotron-fluid">
                <div class="col-md-8 mx-auto">
                    <!-- alerta -->
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

                    <!-- nuevo traspaso -->
                    <div
                        v-show="traspasoType == 'nuevo'"
                        v-if="can('traspasar stock')"
                    >
                        <h1>Agregar Producto a traspasar:</h1>
                        <validation-observer
                            ref="observer"
                            v-slot="{ handleSubmit }"
                        >
                            <b-form
                                @submit.prevent="handleSubmit(storeTraspaso)"
                            >
                                <ValidationProvider
                                    name="company"
                                    v-slot="validationContext"
                                    rules="required"
                                >
                                    <b-form-group
                                        label="Inventario de destino:"
                                        label-for="select-inventario"
                                        class="mt-4"
                                    >
                                        <select-general
                                            url="/inventario"
                                            pholder="Seleccionar Inventario"
                                            v-model="inventario"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                        >
                                        </select-general>
                                        <b-form-invalid-feedback>{{
                                            validationContext.errors[0]
                                        }}</b-form-invalid-feedback>
                                    </b-form-group>
                                </ValidationProvider>

                                <b-form-group label="¿Aceptacion requerida?">
                                    <b-form-radio-group
                                        v-model="aceptacionRequired"
                                        :options="[
                                            { text: 'Si', value: true },
                                            { text: 'No', value: false },
                                        ]"
                                        button-variant="outline-primary"
                                        buttons
                                        name="radios-btn-default"
                                    ></b-form-radio-group>
                                </b-form-group>

                                <b-button
                                    type="submit"
                                    class="mt-3"
                                    variant="outline-primary"
                                    v-if="items.length > 0"
                                    block
                                    >Realizar traspazo</b-button
                                >
                            </b-form>
                            <b-form @submit="agregarSerie">
                                <b-input-group class="mt-5">
                                    <b-form-input
                                        v-model="searchValue"
                                        autocomplete="off"
                                        placeholder="Buscar Producto"
                                        list="search-results"
                                        @update="handleSearch"
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
                                </b-input-group>
                            </b-form>
                        </validation-observer>

                        <!-- cards -->
                        <b-list-group v-if="showList == true" class="mt-5">
                            <b-list-group-item
                                v-for="(item, index) in items"
                                :key="index"
                            >
                                <div>
                                    <div class="row">
                                        <div class="col-sm">
                                            <h5>{{ item.serie }}</h5>
                                        </div>

                                        <div class="col-sm">
                                            <B>Origen:</B>
                                            {{ item.inventario.name }}
                                        </div>
                                        <div class="col-sm">
                                            <B>Status:</B>
                                            {{ item.status }}
                                        </div>
                                        <div class="col-sm" v-if="item.equipo">
                                            <B>Equipo:</B>
                                            {{ item.equipo.marca }}
                                            {{ item.equipo.modelo }}
                                        </div>
                                        <div class="col-sm" v-if="item.company">
                                            <B>Compañia:</B>
                                            {{ item.company.name }}
                                            {{ item.tipoSim.name }}
                                        </div>

                                        <div class="col-sm">
                                            <b-button
                                                class="float-right"
                                                variant="danger"
                                                size="sm"
                                                @click="
                                                    eliminarItem(item, index)
                                                "
                                                >Eliminar</b-button
                                            >
                                        </div>
                                    </div>
                                </div>
                            </b-list-group-item>
                        </b-list-group>
                    </div>

                    <!-- historial de traspasos -->

                    <div
                        v-show="traspasoType == 'historial'"
                        v-if="can('ver traspasos')"
                    >
                        <h1>Historial de traspasos</h1>
                        <validation-observer
                            ref="observer"
                            v-slot="{ handleSubmit }"
                        >
                            <b-form
                                @submit.prevent="
                                    handleSubmit(retrieveHistorial)
                                "
                            >
                                <!-- fecha inicial -->
                                <ValidationProvider
                                    name="fecha-inicio"
                                    v-slot="validationContext"
                                    rules="required"
                                >
                                    <b-form-group
                                        label="Fecha inicio:"
                                        class="mt-4"
                                    >
                                        <b-form-datepicker
                                            id="fecha-inicio"
                                            v-model="initialDate"
                                            class="mb-2"
                                            :max="maxDate"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                        ></b-form-datepicker>
                                        <b-form-invalid-feedback>{{
                                            validationContext.errors[0]
                                        }}</b-form-invalid-feedback>
                                    </b-form-group>
                                </ValidationProvider>

                                <!-- fecha final -->
                                <ValidationProvider
                                    name="fecha-final"
                                    v-slot="validationContext"
                                    rules="required"
                                >
                                    <b-form-group
                                        label="Fecha final:"
                                        class="mt-4"
                                    >
                                        <b-form-datepicker
                                            id="fecha-final"
                                            v-model="finalDate"
                                            class="mb-2"
                                            :max="maxDate"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                        ></b-form-datepicker>
                                        <b-form-invalid-feedback>{{
                                            validationContext.errors[0]
                                        }}</b-form-invalid-feedback>
                                    </b-form-group>
                                </ValidationProvider>

                                <b-form-group>
                                    <b-form-radio-group
                                        v-model="traspasosAccepted"
                                        :options="[
                                            {
                                                text: 'Aceptados',
                                                value: true,
                                            },
                                            {
                                                text: 'Pendientes',
                                                value: false,
                                            },
                                        ]"
                                        buttons
                                        button-variant="outline-primary"
                                        size="lg"
                                        name="radio-btn-outline"
                                        @input="retrieveHistorial"
                                    ></b-form-radio-group>
                                </b-form-group>
                                <b-button block type="submit" variant="primary"
                                    >Buscar</b-button
                                >
                            </b-form>
                        </validation-observer>
                        <b-table
                            :busy="tableLoading"
                            hover
                            responsive
                            striped
                            head-variant="dark"
                            table-variant="light"
                            :items="historialItems"
                            :fields="computedHistorialTableFields"
                            class="mt-5"
                        >
                            <template v-slot:cell(detalles)="row">
                                <b-button
                                    size="sm"
                                    @click="traspasoLoadDetails(row.item)"
                                >
                                    Detalles
                                </b-button>
                            </template>
                            <template v-slot:cell(accepted)="row">
                                <div v-if="row.item.accepted">
                                    {{ row.item.updated_at }}
                                </div>
                            </template>

                            <template v-slot:table-busy>
                                <div class="text-center text-primary my-2">
                                    <b-spinner class="align-middle"></b-spinner>
                                    <strong>Loading...</strong>
                                </div>
                            </template>
                        </b-table>
                    </div>

                    <!-- detalle de traspaso-->
                    <div v-if="traspasoType == 'detalle'">
                        <div class="row">
                            <div class="col-sm">
                                <h1
                                    class="float-right"
                                    :style="{ cursor: 'pointer' }"
                                >
                                    <b-icon
                                        icon="x-circle-fill"
                                        variant="danger"
                                        @click="traspasoType = 'historial'"
                                    ></b-icon>
                                </h1>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <h3>
                                    <b>Traspaso folio:</b>
                                    {{ detailTraspaso.id }}
                                </h3>
                            </div>
                            <div class="col-sm">
                                <h5>
                                    <b>Destino:</b>
                                    {{ detailTraspaso.destino_name }}
                                </h5>
                            </div>

                            <div class="col-sm">
                                <h5>
                                    <b>Fecha:</b>
                                    {{ detailTraspaso.created_at }}
                                </h5>
                            </div>

                            <div
                                class="col-sm"
                                v-if="detailTraspaso.accepted == true"
                            >
                                <h5>
                                    <b>Aceptado:</b>
                                    {{ detailTraspaso.updated_at }}
                                </h5>
                            </div>

                            <div
                                class="col-sm"
                                v-if="detailTraspaso.accepted == true"
                            >
                                <h5>
                                    <b>Aceptado por:</b>
                                    {{ detailTraspaso.user_name }}
                                </h5>
                            </div>
                            <div
                                class="col-sm"
                                v-if="
                                    detailTraspaso.iccs &&
                                    detailTraspaso.iccs.length > 0
                                "
                            >
                                <h5>
                                    <b>SIMs:</b>
                                    {{ detailTraspaso.iccs.length }}
                                </h5>
                            </div>
                            <div
                                class="col-sm"
                                v-if="
                                    detailTraspaso.imeis &&
                                    detailTraspaso.imeis.length > 0
                                "
                            >
                                <h5>
                                    <b>Equipos:</b>
                                    {{ detailTraspaso.imeis.length }}
                                </h5>
                            </div>
                        </div>
                        <div
                            class="row mt-2"
                            v-if="detailTraspaso.accepted == false"
                        >
                            <div class="col-sm">
                                <b-form-group class="mt-2">
                                    <b-button
                                        variant="danger"
                                        @click="
                                            cancelarTraspaso(detailTraspaso.id)
                                        "
                                        v-if="can('cancelar traspaso')"
                                        block
                                    >
                                        Cancelar Traspaso
                                    </b-button>
                                </b-form-group>
                                <b-form-group class="mt-2">
                                    <b-button
                                        variant="success"
                                        @click="
                                            aceptarTraspaso(detailTraspaso.id)
                                        "
                                        v-if="can('aceptar traspaso')"
                                        block
                                    >
                                       Aceptar Traspaso
                                    </b-button>
                                </b-form-group>
                            </div>
                        </div>

                        <!-- lista de chips -->
                        <b-list-group class="mt-5">
                            <b-list-group-item
                                v-for="(item, index) in detailTraspaso.series"
                                :key="index"
                            >
                                <div class="row">
                                    <div class="col-sm" v-if="item.imei">
                                        <h5>{{ item.imei }}</h5>
                                    </div>
                                    <div
                                        class="col-sm float-left"
                                        v-if="item.imei"
                                    >
                                        <B>Equipo: </B>
                                        {{ item.equipo.marca }}
                                        {{ item.equipo.modelo }}
                                    </div>

                                    <div class="col-sm" v-if="item.icc">
                                        <h5>{{ item.icc }}</h5>
                                    </div>
                                    <div
                                        class="col-sm float-left"
                                        v-if="item.icc"
                                    >
                                        <B>Compañia: </B>
                                        {{ item.company.name }}
                                        {{ item.type.name }}
                                    </div>

                                    <div class="col-sm float-left">
                                        <B>Origen:</B>
                                        {{ item.pivot.old_inventario_name }}
                                    </div>
                                </div>
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
    data() {
        return {
            traspasoType: "historial",

            tableLoading: false,

            detailTraspaso: {},

            traspasosAccepted: true,

            historialTableFields: [
                {
                    key: "id",
                    sortable: true,
                    label: "Folio",
                },
                {
                    key: "inventario_name",
                    sortable: true,
                    label: "Destino",
                },
                {
                    key: "created_at",
                    sortable: true,
                    label: "Fecha de Creacion",
                },
                {
                    key: "accepted",
                    sortable: true,
                    label: "Aceptado",
                },
                {
                    key: "user_name",
                    sortable: true,
                    label: "Aceptado por",
                },
                {
                    key: "detalles",
                    label: "Detalles",
                },
            ],

            historialItems: [],

            finalDate: new Date().toISOString().substr(0, 10),

            initialDate: new Date(Date.now() - 5184000000)
                .toISOString()
                .substring(0, 10),

            inventario: null,

            aceptacionRequired: true,

            showList: true,

            searchValue: "",

            searchResults: [],

            alert: {
                show: false,
                title: "",
                variant: "",
                list: [],
            },

            items: [],

            isLoading: false,

            traspasoOptions: [
                { text: "Nuevo traspaso", value: "nuevo" },
                { text: "Historial", value: "historial" },
            ],
        };
    },
    computed: {
        maxDate: function () {
            const now = new Date();
            const today = new Date(
                now.getFullYear(),
                now.getMonth(),
                now.getDate()
            );

            return today;
        },
        computedHistorialTableFields: function () {
            // If the user isn't an admin, filter out fields that require auth.
            // if (!this.isUserAdmin)
            //     return this.historialTableFields.filter((field) => !field.requiresAdmin);
            // // If the user IS an admin, return all fields.
            // else

            return this.historialTableFields;
        },
    },
    methods: {
        handleSearch: _.debounce(function () {
            this.searchProduct();
        }, 300),

        cancelarTraspaso(item) {
            this.isLoading = true;
            axios
                .delete(`/inventario/traspasos/${item}`)
                .then(
                    function (response) {
                        this.isLoading = false;
                        this.traspasoType = "historial";
                        this.retrieveHistorial();
                    }.bind(this)
                )
                .catch(function (error) {
                    console.log(error);
                });
        },
        aceptarTraspaso(item) {
            this.isLoading = true;
            axios
                .put(`/inventario/traspasos/${item}`)
                .then(
                    function (response) {
                        this.isLoading = false;
                        this.traspasoType = "historial";
                        this.retrieveHistorial();
                    }.bind(this)
                )
                .catch(function (error) {
                    console.log(error);
                });
        },
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },

        traspasoLoadDetails(item) {
            this.isLoading = true;

            this.traspasoType = "detalle";

            axios
                .get(`/inventario/traspasos/${item.id}`)
                .then(
                    function (response) {
                        this.detailTraspaso = response.data.data;
                        this.isLoading = false;
                        console.log(this.detailTraspaso);
                    }.bind(this)
                )
                .catch(function (error) {
                    console.log(error);
                });
        },

        eliminarItem(item, index) {
            this.items.splice(index, 1);
        },
        retrieveHistorial() {
            this.tableLoading = true;

            axios
                .get("/inventario/traspasos", {
                    params: {
                        initial_date: this.initialDate,
                        final_date: this.finalDate,
                        accepted: this.traspasosAccepted,
                    },
                })
                .then(
                    function (response) {
                        this.historialItems = response.data.data;

                        this.tableLoading = false;
                    }.bind(this)
                )
                .catch(function (error) {
                    console.log(error);
                });
        },

        searchProduct() {
            const self = this;
            if (this.searchValue.length >= 5) {
                axios
                    .get("/search/traspaso-prediction", {
                        params: { search: this.searchValue },
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

        storeTraspaso() {
            this.isLoading = true;

            const data = new FormData();
            data.append("data", JSON.stringify(this.items));
            data.append("file", this.file);
            data.append("inventario_id", this.inventario.id);
            data.append("aceptacion_required", this.aceptacionRequired);

            axios.post(`/inventario/traspasos`, data).then((res) => {
                this.$bvToast.toast(`Traspaso creado con exito`, {
                    title: "Exito",
                    autoHideDelay: 5000,
                    appendToast: true,
                    solid: true,
                    variant: "success",
                    toaster: "b-toaster-bottom-full",
                });

                this.isLoading = false;

                this.items = [];

                this.traspasoType = "historial";
            });
        },

        agregarSerie(event) {
            event.preventDefault();

            if (this.items.some((item) => item.serie === this.searchValue)) {
                alert("duplicado");
            } else {
                axios
                    .get("/search/traspaso-exact", {
                        params: { search: this.searchValue },
                    })
                    .then(
                        function (response) {
                            console.log(response.data[0]);

                            const item = response.data[0];

                            const nuevoItem = {
                                type: item.type,

                                id: item.searchable.id,

                                serie: item.title,

                                inventario:
                                    item.searchable.inventario.inventarioable,

                                status: item.searchable.status,

                                equipo: item.searchable.equipo,

                                tipoSim: item.searchable.type,

                                company: item.searchable.company,
                            };

                            this.items.unshift(nuevoItem);
                        }.bind(this)
                    )
                    .catch(function (error) {
                        console.log(error);
                    });
                this.searchValue = null;

                this.searchResults = [];
            }
        },
    },
};
</script>

<style></style>
