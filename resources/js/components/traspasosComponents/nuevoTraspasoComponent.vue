<template>
    <div>
        <div v-if="is('super-admin') || can('traspasar stock')">
            <h1>Agregar Producto a traspasar:</h1>
            <validation-observer ref="observer" v-slot="{ handleSubmit }">
                <b-form @submit.prevent="handleSubmit(storeTraspaso)">
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
                                :state="getValidationState(validationContext)"
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
            <div class="mt-3">
                <h5 v-show="items.length > 0">
                    {{ `Cantidad: ${items.length}` }}
                </h5>
            </div>
            <!-- cards -->
            <b-list-group class="mt-5">
                <b-list-group-item v-for="(item, index) in items" :key="index">
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
                                    @click="eliminarItem(item, index)"
                                    >Eliminar</b-button
                                >
                            </div>
                        </div>
                    </div>
                </b-list-group-item>
            </b-list-group>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            inventario: null,

            aceptacionRequired: false,

            searchValue: "",

            searchResults: [],
            items: [],
        };
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        handleSearch: _.debounce(function () {
            this.searchProduct();
        }, 300),
        searchProduct() {
            if (this.searchValue.length >= 5) {
                axios
                    .get("/search/traspaso-prediction", {
                        params: { search: this.searchValue },
                    })
                    .then((response) => {
                        this.searchResults = response.data;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            } else {
                this.searchResults = [];
            }
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
                });

                this.isLoading = false;

                this.items = [];

                this.traspasoType = "historial";
            });
        },

        eliminarItem(item, index) {
            this.items.splice(index, 1);
        },
    },
};
</script>

<style></style>
