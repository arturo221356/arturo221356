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
                        a la derecha
                    </div>
                </b-collapse>
            </b-navbar>
            <div class="jumbotron">
                <div class="col-md-11 mx-auto">
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

                    <h1>Agregar Producto a traspasar:</h1>
                    <validation-observer
                        ref="observer"
                        v-slot="{ handleSubmit }"
                    >
                        <b-form @submit.prevent="handleSubmit(storeTraspaso)">
                            <ValidationProvider
                                name="company"
                                v-slot="validationContext"
                                rules="required"
                            >
                                <b-form-group
                                    label="Sucursal de destino:"
                                    label-for="select-sucursal"
                                    class="mt-4"
                                >
                                    <select-general
                                        url="/get/sucursales"
                                        pholder="Seleccionar Sucursal"
                                        v-model="sucursal"
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
                                    <b-button variant="success" type="submit"
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
                            <div class="d-flex flex-row">
                                <div class="p-2">
                                    <h5>{{ item.serie }}</h5>
                                </div>
                                <div class="p-2" v-if="item.equipo">
                                    <B>Equipo:</B> {{ item.equipo.marca }}
                                    {{ item.equipo.modelo }}
                                </div>
                                <div class="p-2">
                                    <B>Sucursal:</B> {{ item.sucursal.name }}
                                </div>
                                <div class="p-2">
                                    <B>Status:</B> {{ item.status.status }}
                                </div>
                                <div class="p-2" v-if="item.company">
                                    <B>compañia:</B> {{ item.company.name }}
                                </div>
                                <div class="p-2" v-if="item.tipoSim">
                                    {{ item.tipoSim.name }}
                                </div>
                                <div class="ml-auto">
                                    <small
                                        ><b-button
                                            variant="danger"
                                            size="sm"
                                            @click="eliminarItem(item, index)"
                                            >Eliminar</b-button
                                        >
                                    </small>
                                </div>
                            </div>
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
            traspasoType: 1,

            sucursal: null,

            aceptacionRequired: false,

            showList: true,

            searchValue: "",

            searchResults: [],

            alert: {
                show: false,
                title: "",
                variant: "",
                list: [],
            },

            items: [
                {
                    id: 1,
                    serie: "8925034545455454547",
                    type: "iccs",
                    status: { status: "disponible" },
                    sucursal: { name: "tonala" },
                },
                {
                    id: 2,
                    serie: 123456789123456,
                    type: "imeis",
                    status: { name: "disponible" },
                    sucursal: { name: "chipinkuan" },
                    equipo: {
                        marca: "nokia 3311",
                        modelo: "3311",
                        precio: 999,
                    },
                },
            ],

            isLoading: false,

            options: [{ text: "Sucursal a sucursal", value: 1 }],
        };
    },
    methods: {
        confirmProductChange(e) {
            if (this.items.length > 0) {
                if (!confirm("Desea continuar se borrara la informacion??")) {
                    e.preventDefault();
                } else {
                }
            }
        },
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },

        eliminarItem(item, index) {
            this.items.splice(index, 1);
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
            // this.isLoading = true;

            const data = new FormData();
            data.append("data", JSON.stringify(this.items));
            data.append("file", this.file);
            data.append("sucursal_id", this.sucursal.id);

            axios.post(`/admin/inventario/traspasos/`, data).then((res) => {
                console.log(res);

                this.$bvToast.toast(`Recarga Agregada con exito`, {
                    title: "Exito",
                    autoHideDelay: 5000,
                    appendToast: true,
                    solid: true,
                    variant: "success",
                    toaster: "b-toaster-bottom-full",
                });
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

                                sucursal: item.searchable.sucursal,

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
            }
        },
    },
};
</script>

<style></style>
