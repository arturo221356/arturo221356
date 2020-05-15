<template>
    <div>
        <b-navbar
            toggleable="lg"
            type="light"
            style="background-color: #dedede;"
        >
            <b-navbar-brand href="#">Equipos</b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav>
                    <b-link @click="newEquipo">Agregar Equipo</b-link>
                </b-navbar-nav>

                <!-- Right aligned nav items -->
                <b-navbar-nav class="ml-auto">
                    <b-form-input
                        type="search"
                        v-model="filter"
                        placeholder="Buscar"
                    ></b-form-input>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>

        <!-- info modal -->
        <b-modal
            :id="infoModal.id"
            :title="infoModal.title"
            ok-only
            @hide="resetInfoModal"
            ref="modal"
        >
            <pre>{{ infoModal.string }}</pre>

            <b-form-group label="Marca">
                <b-form-input
                    type="text"
                    v-model="equipo.marca"
                    placeholder="Marca"
                >
                </b-form-input>
            </b-form-group>
            <b-form-group label="Modelo">
                <b-form-input
                    type="text"
                    v-model="equipo.modelo"
                    placeholder="Modelo"
                >
                </b-form-input>
            </b-form-group>

            <b-form-group label="Precio">
                <b-form-input
                    type="number"
                    v-model="equipo.precio"
                    placeholder="Precio"
                >
                </b-form-input>
            </b-form-group>
            <b-form-group label="Costo">
                <b-form-input
                    type="number"
                    v-model="equipo.costo"
                    placeholder="Costo"
                >
                </b-form-input>
            </b-form-group>

            <!-- Footer del modal Botones -->

            <template v-slot:modal-footer="{ ok, cancel }">
                <b>Custom Footer</b>
                <!-- Emulate built in modal footer ok and cancel button actions -->
                <b-button
                    size="sm"
                    variant="success"
                    @click="chekForm(infoModal.content.id)"
                >
                    Guardar
                </b-button>

                <!-- Button with custom close trigger value -->
                <b-button
                    size="sm"
                    variant="outline-danger"
                    @click="deleteEquipo(infoModal.content.id)"
                >
                    Eliminar
                </b-button>

                <b-button size="sm" @click="cancel()">
                    Cancelar
                </b-button>
            </template>
        </b-modal>
        <!-- info modal -->

        <b-table
            striped
            hover
            :items="items"
            :fields="fields"
            :busy="isBusy"
            :filter="filter"
        >
            <!--busy template-->
            <template v-slot:table-busy>
                <div class="text-center text-primary my-2">
                    <b-spinner class="align-middle"></b-spinner>
                    <strong>Cargando...</strong>
                </div>
            </template>

            <!-- resultado template -->
            <template v-slot:table-caption
                >Resultado: - {{ countItems }}
            </template>

            <!--boton de editar -->
            <template v-slot:cell(editar)="row">
                <b-button @click="editEquipo(row.item, row.index)">
                    Editar</b-button
                >
            </template>
            <!--boton de editar -->
        </b-table>
    </div>
</template>
<script>
export default {
    data() {
        return {
            filter: null,
            isLoading: false,
            items: [],
            countItems: 0,
            isBusy: false,
            editMode: false,

            equipo: {
                marca: "",
                modelo: null,
                precio: null,
                costo: null,
            },

            fields: [
                {
                    key: "id",
                    label: "#",
                    sortable: true,
                    sortDirection: "desc",
                },
                {
                    key: "marca",
                    label: "Marca",
                    sortable: true,
                    sortDirection: "desc",
                },
                {
                    key: "modelo",
                    label: "Modelo",
                    sortable: true,
                    sortDirection: "desc",
                },
                {
                    key: "precio",
                    label: "Precio",
                    sortable: true,
                    sortDirection: "desc",
                },
                {
                    key: "costo",
                    label: "Costo",
                    sortable: true,
                    sortDirection: "desc",
                },
                { key: "editar", label: "Editar" },
            ],
            infoModal: {
                id: "info-modal",
                title: "",
                content: {},
                string: "",
            },
        };
    },
    methods: {
        newEquipo() {
            alert("nuevo equipo");
        },

        loadData() {
            this.isBusy = true;
            axios
                .get("/get/equipos")
                .then((response) => {
                    console.log(response.data);
                    this.items = response.data;
                    this.countItems = this.items.length;

                    this.isBusy = false;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
        },
        editEquipo(item, index, button) {
            this.editMode = true;
            this.infoModal.string = JSON.stringify(item, null, 2);
            this.infoModal.content.id = item.id;
            this.infoModal.title = `Row index: ${this.infoModal.content.id}`;
            this.equipo.marca = item.marca;
            this.equipo.modelo = item.modelo;
            this.equipo.precio = item.precio;
            this.equipo.costo = item.costo;
            this.$root.$emit("bv::show::modal", this.infoModal.id, button);
            console.log(this.infoModal.content);
        },

        deleteEquipo(id) {
            axios.delete(`/admin/productos/equipos/${id}`).then((res) => {
                this.$refs["modal"].hide();
                this.loadData();
                // console.log(res.data);

                // this.$bvToast.toast(`${res.data.message}`, {
                //     title: res.data.title,
                //     autoHideDelay: 5000,
                //     appendToast: true,
                //     solid: true,
                //     variant: res.data.variant,
                //     toaster: "b-toaster-bottom-full",
                // });
            });
        },
        updateEquipo(id) {
            const params = {
                marca: this.equipo.marca,
                modelo: this.equipo.modelo,
                precio: this.equipo.precio,
                costo: this.equipo.costo,
            };
            axios.put(`/admin/productos/equipos/${id}`, params).then((res) => {
                alert("Editado");

                this.$refs["modal"].hide();

                this.loadData();
            });
        },
        storeEquipo() {
            const params = {
                marca: this.equipo.marca,
                modelo: this.equipo.modelo,
                precio: this.equipo.precio,
                costo: this.equipo.costo,
            };
            axios.post(`/admin/productos/equipos/`, params).then((res) => {
                this.$refs["modal"].hide();

                this.loadData();

                this.$bvToast.toast(`Equipo Agregado con exito`, {
                    title: "Exito",
                    autoHideDelay: 5000,
                    appendToast: true,
                    solid: true,
                    variant: "success",
                    toaster: "b-toaster-bottom-full",
                });
            });
            console.log(params)
        },
        resetInfoModal() {
            this.infoModal.title = "";
            this.infoModal.content = {};
            this.infoModal.string = "";
            this.equipo.marca = "";
            this.equipo.modelo = "";
            this.equipo.precio = "";
            this.equipo.costo = "";
        },
        chekForm(id) {
            if (
                this.equipo.marca &&
                this.equipo.modelo
                // this.user.role &&
                // this.user.sucursal
            ) {
                if (this.editMode == true) {
                    this.updateEquipo(id);
                } else if (this.editMode == false) {
                    this.storeEquipo();
                } else {
                }
            }
        },
        newEquipo() {
            this.editMode = false;
            this.$refs["modal"].show();
            console.log(`editmode ${this.editMode}`);
        },
    },
    created() {
        this.loadData();
    },
    computed: {
        options: function () {
            var options = [];
            options = this.equipos;
            return options;
        },
    },
};
</script>
