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
                    <b-link @click="newUser">Agregar usuario</b-link>
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
                <template v-slot:cell(editar)="row">
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
                                autocomplete="off"
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
                                autocomplete="off"
                                 v-if="editMode == false"
                            >
                                <b-form-group
                                    label="Contraseña"
                                   
                                >
                                    <b-form-input
                                        type="text"
                                        v-model="user.password"
                                        placeholder="Contraseña"
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
                                name="sucursal"
                                v-slot="validationContext"
                                rules="required"
                                autocomplete="off"
                            >
                                <b-form-group label="Sucursal">
                                    <select-general
                                        url="/get/sucursales"
                                        pholder="Seleccionar Sucursal"
                                        v-model="user.sucursal"
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
                                name="Rol"
                                v-slot="validationContext"
                                rules="required"
                                autocomplete="off"
                            >
                                <b-form-group label="Rol">
                                    <select-general
                                        url="/admin/roles"
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
                    key: "role.name",
                    label: "Rol",
                    sortable: true,
                    sortDirection: "desc",
                },
                {
                    key: "sucursal.name",
                    label: "Sucursal",
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
                .get("/admin/users")
                .then((response) => {
                    console.log(response.data);
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
            this.user.sucursal = item.sucursal;
            this.user.role = item.role;
            this.$root.$emit("bv::show::modal", this.infoModal.id, button);
            console.log(this.infoModal.content);
            console.log(item);
        },
        resetInfoModal() {
            this.infoModal.title = "";
            this.infoModal.content = {};
            this.infoModal.string = "";
            this.user.name = "";
            this.user.role = null;
            this.user.email = "";
            this.user.password = null;
            this.user.sucursal = null;
            this.modalLoading = false;
        },
        deleteUser(id) {
            axios.delete(`/admin/users/${id}`).then((res) => {
                this.$refs["modal"].hide();
                this.loadData();
                console.log(res.data);

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
            const params = {
                sucursal_id: this.user.sucursal.id,
                name: this.user.name,
                email: this.user.email,
                role_id: this.user.role.id,
            };
            axios.put(`/admin/users/${id}`, params).then((res) => {
                alert("Editado");

                this.$refs["modal"].hide();

                this.loadData();
            });
        },
        storeUser() {
            const params = {
                sucursal_id: this.user.sucursal.id,
                name: this.user.name,
                password: this.user.password,
                email: this.user.email,
                role_id: this.user.role.id,
            };
            axios.post(`/admin/users/`, params).then((res) => {
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
};
</script>
