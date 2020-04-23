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
                <div class="col-md-6 mx-auto">
                    <h1>Agregar {{ producto }}:</h1>

                    <b-form @submit="agregarserie">
                        <b-form-group
                            v-if="producto === 'Imei'"
                            label="Equipo"
                            label-for="select-equipo"
                            label-size="lg"
                        >
                            <select-equipo
                                seleccionado=""
                                name="select-equipo"
                                v-on:equipo="equipoChange"
                            ></select-equipo>
                        </b-form-group>

                        <b-form-group
                            label="Sucursal"
                            label-for="select-sucursal"
                            label-size="lg"
                        >
                            <select-sucursal
                                v-on:sucursal="sucursalChange"
                            ></select-sucursal>
                        </b-form-group>

                        <b-form-group
                            :label="producto"
                            label-for="serie"
                            label-size="lg"
                        >
                            <b-input-group>
                                <b-input
                                    :placeholder="`Ingresa 1 ${producto}`"
                                    name="serie"
                                    v-model="item.serie"
                                    autocomplete="off"
                                ></b-input>

                                <b-input-group-append>
                                    <b-button
                                        variant="outline-success"
                                        type="submit"
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
                            v-for="(item, index) in items"
                            :key="index"
                        >
                            {{ index + 1 }}: {{ item.serie }}
                            {{ item.sucursalText }}
                            <b-button
                                size="sm"
                                class="float-right"
                                variant="danger"
                                @click="eliminarSerie(item, index)"
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

            item: { serie: "", sucursal: 1, sucursalText: "", equipo: "" },

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
            console.log(this.producto);

            this.items = [];
        },

        sucursalChange(value) {
            this.item.sucursal = value.id;

            this.item.sucursalText = value.text;

            console.log(this.item);
        },

        equipoChange(value) {
            this.item.equipo = value.id;
        },

        agregarserie(evt) {
            evt.preventDefault();

            const nuevoItem = this.item;

            this.items.push(nuevoItem);

            console.log(this.items);

            this.item = {
                serie: "",
                sucursal: this.item.sucursal,
                equipo: this.item.equipo,
                sucursalText: this.item.sucursalText,
            };
        },

        eliminarSerie(item, index) {
            this.items.splice(index, 1);
        },
    },
    computed: {
        seriesOrdenadas() {
            return this.items.reverse();
        },
    },
};
</script>
