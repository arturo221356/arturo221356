<template>
    <div>
        <b-navbar
            toggleable="lg"
            type="light"
            style="background-color: #dedede;"
        >
            <b-navbar-brand href="#">{{ inventarioText }}</b-navbar-brand>

            <b-form-radio-group
                v-model="inventario"
                :options="inventarioOptions"
                buttons
                @input="loadData()"
            ></b-form-radio-group>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
                <div class="ml-3">
                    <b-navbar-nav>
                        <b-link @click="newSucursal"
                            >Agregar {{ inventarioTextSingular }}</b-link
                        >
                    </b-navbar-nav>
                </div>

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
        <div>
            <b-table
                striped
                hover
                :items="items"
                :fields="fields"
                :busy="isBusy"
                :filter="filter"
                stacked="sm"
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
                    <b-button @click="editSucursal(row.item, row.index)">
                        Editar</b-button
                    >
                </template>
                <!--boton de editar -->
            </b-table>
            <!-- info modal -->

            <b-modal
                :id="infoModal.id"
                :title="`Agregar ${inventarioTextSingular}`"
                ok-only
                @hide="resetInfoModal"
                ref="modal"
            >
                <b-overlay
                    :show="modalLoading"
                    blur="1px"
                    rounded="sm"
                    variant="transparent"
                >
                    <validation-observer
                        ref="observer"
                        v-slot="{ handleSubmit }"
                    >
                        <b-form @submit.prevent="handleSubmit(onSubmit)">
                            <!-- nombre -->
                            <ValidationProvider
                                name="nombre"
                                v-slot="validationContext"
                                rules="required|min:4"
                                autocomplete="off"
                            >
                                <b-form-group label="Nombre">
                                    <b-form-input
                                        type="text"
                                        v-model="sucursal.name"
                                        :placeholder="
                                            inventario === 'grupos'
                                                ? 'Grupo Cambaceo'
                                                : 'Nombre'
                                        "
                                        :state="
                                            getValidationState(
                                                validationContext
                                            )
                                        "
                                    >
                                    </b-form-input>
                                    <b-form-invalid-feedback>{{
                                        validationContext.errors[0]
                                    }}</b-form-invalid-feedback>
                                </b-form-group>
                            </ValidationProvider>

                            <!-- direccion -->
                            <ValidationProvider
                                name="address"
                                v-slot="validationContext"
                                rules="required"
                            >
                                <b-form-group label="Direccion">
                                    <b-form-input
                                        type="text"
                                        v-model="sucursal.address"
                                        placeholder="Direccion"
                                        autocomplete="off"
                                        :state="
                                            getValidationState(
                                                validationContext
                                            )
                                        "
                                    >
                                    </b-form-input>
                                    <b-form-invalid-feedback>{{
                                        validationContext.errors[0]
                                    }}</b-form-invalid-feedback>
                                </b-form-group>
                            </ValidationProvider>
                            <ValidationProvider
                                name="permisos"
                                v-slot="validationContext"
                                autocomplete="off"
                                v-if="can('update permissions')"
                            >
                                <b-form-group
                                    label="Permisos:"
                                    v-if="inventario == 'grupos'"
                                >
                                    <b-form-checkbox-group
                                        v-model="sucursal.permissions"
                                        :options="permisosOptionsComputed"
                                        switches
                                        stacked
                                    ></b-form-checkbox-group>
                                </b-form-group>
                                <b-form-invalid-feedback>{{
                                    validationContext.errors[0]
                                }}</b-form-invalid-feedback>
                            </ValidationProvider>

                            <b-button size="sm" variant="primary" type="submit">
                                Guardar
                            </b-button>

                            <!-- Button with custom close trigger value -->
                            <!-- <b-button
                                size="sm"
                                variant="outline-danger"
                                @click="deleteSucursal(infoModal.content.id)"
                            >
                                Eliminar
                            </b-button> -->
                        </b-form>
                    </validation-observer>
                </b-overlay>

                <!-- Footer del modal Botones -->

                <template v-slot:modal-footer="{ ok, cancel }">
                    <b-button size="sm" @click="cancel()">
                        Cancelar
                    </b-button>
                </template>
            </b-modal>
            <!-- info modal -->
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            items: [],

            inventario: "sucursales",

            inventarioOptions: [
                { text: "Sucursales", value: "sucursales" },
                { text: "Grupos", value: "grupos" },
            ],

            countItems: 0,

            modalLoading: false,

            editMode: null,

            isBusy: false,

            filter: null,

            sucursal: {
                name: "",
                address: "",
                permissions: [],
            },
            fields: [
                {
                    key: "id",
                    label: "#",
                    sortable: true,
                    sortDirection: "desc",
                },
                {
                    key: "name",
                    label: "Nombre",
                    sortable: true,
                    sortDirection: "desc",
                },
                {
                    key: "address",
                    label: "Direccion",
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
    computed: {
        inventarioText: function () {
            if (typeof this.inventario !== "string") return "";
            return (
                this.inventario.charAt(0).toUpperCase() +
                this.inventario.slice(1)
            );
        },
        inventarioTextSingular: function () {
            if (typeof this.inventario !== "string") return "";
            return this.inventario === "sucursales" ? "Sucursal" : "Grupo";
        },

        permisosOptionsComputed: function () {
            let options = [];
            
                switch (this.inventario) {
                    case "grupos":
                        options = [
                            {
                                text: "Activar chips por sistema activa chip",
                                value: "activar chip",
                            },
                        ];

                        break;
                    default:
                        options = [];
                }
            return options;
            
        },
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        loadData() {
            this.isBusy = true;
            axios
                .get(`/${this.inventario}`)
                .then((response) => {
                    console.log(response.data.data);
                    this.items = response.data.data;
                    this.countItems = this.items.length;

                    this.isBusy = false;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
        },
        onSubmit() {
            this.modalLoading = true;

            if (this.editMode == true) {
                this.updateSucursal();
            } else if (this.editMode == false) {
                this.storeSucursal();
            }
        },
        editSucursal(item, index, button) {
            this.editMode = true;
            this.infoModal.string = JSON.stringify(item, null, 2);
            this.infoModal.content.id = item.id;
            this.infoModal.title = `Row index: ${this.infoModal.content.id}`;
            this.sucursal.name = item.name;
            this.sucursal.address = item.address;
            this.sucursal.permissions = item.permissions;
            this.$root.$emit("bv::show::modal", this.infoModal.id, button);
            console.log(this.infoModal.content);
        },
        resetInfoModal() {
            this.infoModal.title = "";
            this.infoModal.content = {};
            this.infoModal.string = "";
            this.sucursal.name = "";
            this.sucursal.address = "";
            this.modalLoading = false;
        },
        // deleteSucursal(id) {
        //     axios.delete(`/admin/sucursales/${id}`).then((res) => {
        //         this.$refs["modal"].hide();
        //         this.loadData();
        //         console.log(res.data);

        //         this.$bvToast.toast(`${res.data.message}`, {
        //             title: res.data.title,
        //             autoHideDelay: 5000,
        //             appendToast: true,
        //             solid: true,
        //             variant: res.data.variant,
        //             toaster: "b-toaster-bottom-full",
        //         });
        //     });
        // },
        updateSucursal() {
            const id = this.infoModal.content.id;
            const params = {
                name: this.sucursal.name,
                address: this.sucursal.address,
                permisos: this.sucursal.permissions,
            };
            axios.put(`/${this.inventario}/${id}`, params).then((res) => {
                alert("Editado");

                this.$refs["modal"].hide();

                this.loadData();
            });
        },
        storeSucursal() {
            const params = {
                name: this.sucursal.name,
                address: this.sucursal.address,
                permisos: this.sucursal.permissions,
            };
            axios.post(`/${this.inventario}`, params).then((res) => {
                this.$refs["modal"].hide();

                this.loadData();

                this.$bvToast.toast(
                    `${this.inventarioTextSingular} Agregada con exito`,
                    {
                        title: "Exito",
                        autoHideDelay: 5000,
                        appendToast: true,
                        solid: true,
                        variant: "success",
                        toaster: "b-toaster-bottom-full",
                    }
                );
            });
        },

        newSucursal() {
            this.editMode = false;
            this.$refs["modal"].show();
            console.log(`editmode ${this.editMode}`);
        },
    },
    created() {
        this.loadData();
    },
};
</script>
