<template>
    <div>
        <b-navbar
            toggleable="lg"
            type="light"
            style="background-color: #dedede;"
        >
            <b-navbar-brand href="#">Usuarios</b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav>
                    <b-link @click="newUser" v-if="can('create user')">Agregar usuario</b-link>
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
        <div>
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
                <template v-slot:cell(editar)="row" v-if="can('update user')">
                    <b-button @click="editUser(row.item, row.index)">
                        Editar</b-button
                    >
                </template>
                <!--boton de editar -->
            </b-table>
            <!-- info modal -->
            <b-modal
                :id="infoModal.id"
                :title="infoModal.title"
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
                            >
                                <b-form-group label="Nombre">
                                    <b-form-input
                                        type="text"
                                        v-model="user.name"
                                        placeholder="Nombre"
                                        :state="
                                            getValidationState(
                                                validationContext
                                            )
                                        "
                                        autocomplete="off"
                                    >
                                    </b-form-input>
                                    <b-form-invalid-feedback>{{
                                        validationContext.errors[0]
                                    }}</b-form-invalid-feedback>
                                </b-form-group>
                            </ValidationProvider>

                            <!-- email -->
                            <ValidationProvider
                                name="email"
                                v-slot="validationContext"
                                rules="required|email"
                            >
                                <b-form-group label="Email">
                                    <b-form-input
                                        type="email"
                                        v-model="user.email"
                                        placeholder="Email"
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

                            <!-- password -->
                            <ValidationProvider
                                name="contraseña"
                                v-slot="validationContext"
                                rules="min:5|required"
                                v-if="editMode == false"
                            >
                                <b-form-group label="Contraseña">
                                    <b-form-input
                                        type="text"
                                        v-model="user.password"
                                        placeholder="Contraseña"
                                        :state="
                                            getValidationState(
                                                validationContext
                                            )
                                        "
                                        autocomplete="off"
                                    >
                                    </b-form-input>
                                    <b-form-invalid-feedback>{{
                                        validationContext.errors[0]
                                    }}</b-form-invalid-feedback>
                                </b-form-group>
                            </ValidationProvider>

                            <ValidationProvider
                                name="telefono"
                                v-slot="validationContext"
                                rules="digits:10|required"
                            >
                                <b-form-group label="Telefono">
                                    <b-form-input
                                        type="text"
                                        v-model="user.telefono"
                                        placeholder="Telefono"
                                        :state="
                                            getValidationState(
                                                validationContext
                                            )
                                        "
                                        autocomplete="off"
                                    >
                                    </b-form-input>
                                    <b-form-invalid-feedback>{{
                                        validationContext.errors[0]
                                    }}</b-form-invalid-feedback>
                                </b-form-group>
                            </ValidationProvider>

                            <ValidationProvider
                                name="Rol"
                                v-slot="validationContext"
                                rules="required"
                                autocomplete="off"
                                v-if="editMode == false"
                            >
                                <b-form-group label="Rol">
                                    <select-general
                                        url="/get/roles"
                                        pholder="Seleccionar Rol"
                                        v-model="user.role"
                                        :state="
                                            getValidationState(
                                                validationContext
                                            )
                                        "
                                    ></select-general>

                                    <b-form-invalid-feedback>{{
                                        validationContext.errors[0]
                                    }}</b-form-invalid-feedback>
                                </b-form-group>
                            </ValidationProvider>

                            <ValidationProvider
                                name="sucursal"
                                v-slot="validationContext"
                                rules="required"
                                autocomplete="off"
                                v-if="sucursalExternoComputed"
                            >
                                <b-form-group label="Sucursal">
                                    <select-general
                                        url="/inventario"
                                        :query="querySucursalSelectComputed"
                                        pholder="Seleccionar Sucursal"
                                        v-model="user.sucursal"
                                        :multiple="sucursalMultipleComputed"
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

                            <ValidationProvider
                                name="permisos"
                                v-slot="validationContext"
                                autocomplete="off"
                                v-if="can('update permissions')"
                            >
                                <b-form-group label="Permisos:" v-if="user.role">
                                    <b-form-checkbox-group
                                        v-model="user.permisos"
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
                            <b-button
                                size="sm"
                                variant="outline-danger"
                                @click="deleteUser(infoModal.content.id)"
                                v-if="user.role != 1 && editMode == true"
                            >
                                Eliminar
                            </b-button>
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

            modalLoading: false,

            formErrors: [],

            editMode: false,

            isBusy: false,

            filter: null,

            user: {
                name: "",
                role: null,
                email: "",
                sucursal: null,
                password: null,
                telefono: null,
                permisos: null,
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
                    key: "email",
                    label: "Email",
                    sortable: true,
                    sortDirection: "desc",
                },
                {
                    key: "telefono",
                    label: "Telefono",
                },
                {
                    key: "roles[0]",
                    label: "Rol",
                    sortable: true,
                    sortDirection: "desc",
                },
                {
                    key: "inventarios",
                    label: "Inventario",
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
            countItems: 0,
        };
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        loadData() {
            this.isBusy = true;
            axios
                .get("/users")
                .then((response) => {
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
                this.updateUser();
            } else if (this.editMode == false) {
                this.storeUser();
            }
        },
        editUser(item, index, button) {
            this.editMode = true;
            this.infoModal.string = JSON.stringify(item, null, 2);
            this.infoModal.content.id = item.id;
            this.infoModal.title = `Row index: ${this.infoModal.content.id}`;
            this.user.name = item.name;
            this.user.email = item.email;
            this.user.telefono = item.telefono;
            this.user.permisos = item.permisos;

            this.user.role = { name: item.roles[0] };
            this.user.sucursal = item.inventarios_ids;
            this.$root.$emit("bv::show::modal", this.infoModal.id, button);
        },
        resetInfoModal() {
            this.infoModal.title = "";
            this.infoModal.content = {};
            this.infoModal.string = "";
            this.user.name = "";
            this.user.role = null;
            this.user.email = "";
            this.user.telefono = null;
            this.user.password = null;
            this.user.sucursal = null;
            this.user.permisos = null;
            this.modalLoading = false;
        },
        deleteUser(id) {
            axios.delete(`/users/${id}`).then((res) => {
                this.$refs["modal"].hide();
                this.loadData();

                this.$bvToast.toast(`${res.data.message}`, {
                    title: res.data.title,
                    autoHideDelay: 5000,
                    appendToast: true,
                    solid: true,
                    variant: res.data.variant,
                    toaster: "b-toaster-bottom-full",
                });
            });
        },
        updateUser() {
            const id = this.infoModal.content.id;

            var inventariosIds = [];

            if (this.user.sucursal) {
                if (Array.isArray(this.user.sucursal)) {
                    this.user.sucursal.forEach((element) => {
                        inventariosIds.push(element.id);
                    });
                } else {
                    inventariosIds.push(this.user.sucursal.id);
                }
            }

            const params = {
                inventarios: inventariosIds,
                name: this.user.name,
                email: this.user.email,
                telefono: this.user.telefono,
                permisos: this.user.permisos,
            };
            axios.put(`/users/${id}`, params).then((res) => {
                alert("Editado");

                this.$refs["modal"].hide();

                this.loadData();
            });
        },
        storeUser() {
            var inventariosIds = [];

            if (this.user.sucursal) {
                if (Array.isArray(this.user.sucursal)) {
                    this.user.sucursal.forEach((element) => {
                        inventariosIds.push(element.id);
                    });
                } else {
                    inventariosIds.push(this.user.sucursal.id);
                }
            }
            const params = {
                inventarios: inventariosIds,
                name: this.user.name,
                password: this.user.password,
                email: this.user.email,
                role: this.user.role.name,
                telefono: this.user.telefono,
            };
            axios.post(`/users`, params).then((res) => {
                this.$refs["modal"].hide();

                this.loadData();

                this.$bvToast.toast(`Usuario Agregado con exito`, {
                    title: "Exito",
                    autoHideDelay: 5000,
                    appendToast: true,
                    solid: true,
                    variant: "success",
                    toaster: "b-toaster-bottom-full",
                });
            });
        },

        newUser() {
            this.editMode = false;
            this.$refs["modal"].show();
        },
    },
    created() {
        this.loadData();
    },
    computed: {
        permisosOptionsComputed: function () {
            var options = [];
            if (this.user.role) {
                switch (this.user.role.name) {
                    case 'externo':
                        options = [
                            { text: "Activar chips por sistema activa chip", value: "activar chip" },

                        ];

                        break;
                    case 'supervisor':
                        options = [
                            

                            { text: "Preactivar lineas masivas", value: "preactivar masivo" },

                            { text: "Asignar recarga sistema activa chip", value: "asignar recarga" },

                            { text: "Crear sucursales", value: "create sucursal" },

                            { text: "Editar usuarios", value: "update user" },

                            { text: "Crear usuarios vendedores", value: "create vendedor" },

                            { text: "Crear usuarios externos", value: "create externo" },

                            { text: "Modificar permisos de usuarios", value: "store stock" },

                             { text: "Cargar nuevo inventario", value: "traspasar stock" },

                            { text: "Traspasar Inventario", value: "traspasar stock" },

                            { text: "Cancelar Traspaso", value: "cancelar traspaso" },

                           
                            

                        ];
                        break;
                    case 'vendedor':

                        options = [

                            { text: "Vender recargas", value: "create transaction" },

                            { text: "Preactivar lineas masivas", value: "preactivar masivo" },

                            

                        ];
                    break;
                }
            }

            return options;
        },
        sucursalMultipleComputed: function () {
            if (this.user.role && this.user.role.name == "supervisor") {
                return true;
            } else {
                return false;
            }
        },
        sucursalExternoComputed: function () {
            if(this.user.role){
                if(this.user.role.name == 'externo'){
                    return false;
                }
                else{
                    return true;
                }
            }else{
                return false;
            }

        },
        querySucursalSelectComputed: function () {
            if (this.user.role) {
                switch (this.user.role.name) {
                    case "vendedor":
                        return "App\\Sucursal";

                        break;

                    default:
                        return null;
                        break;
                }
            } else {
                return null;
            }
        },
    },
};
</script>
