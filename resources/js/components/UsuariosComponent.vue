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
                    <b-link @click="loadData">Agregar usuario</b-link>
                </b-navbar-nav>

                <!-- Right aligned nav items -->
                <b-navbar-nav class="ml-auto">
                    <b-form-input type="search"></b-form-input>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>
        <div>
            <b-table striped hover :items="items" :fields="fields">
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
                    <b-button @click="info(row.item, row.index)">
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
                <pre>{{ infoModal.string }}</pre>

                <b-form-group label="Nombre">
                    <b-form-input
                        type="text"
                        v-model="user.name"
                        placeholder="Nombre"
                    >
                    </b-form-input>
                </b-form-group>
                <b-form-group label="Email">
                    <b-form-input
                        type="email"
                        v-model="user.email"
                        placeholder="Email"
                    >
                    </b-form-input>
                </b-form-group>
                <b-form-group label="Sucursal">
                    <select-sucursal
                        :seleccionado="user.sucursal"
                    ></select-sucursal>
                </b-form-group>

                <b-form-group label="Rol">
                    <select-sucursal
                        :seleccionado="2"
                    ></select-sucursal>
                </b-form-group>

                <!-- Footer del modal Botones -->

                <template v-slot:modal-footer="{ ok, cancel }">
                    <b>Custom Footer</b>
                    <!-- Emulate built in modal footer ok and cancel button actions -->
                    <b-button
                        size="sm"
                        variant="success"
                        @click="updateUser(infoModal.content.id)"
                    >
                        Guardar
                    </b-button>

                    <!-- Button with custom close trigger value -->
                    <b-button
                        size="sm"
                        variant="outline-danger"
                        @click="deleteUser(infoModal.content.id)"
                    >
                        Eliminar
                    </b-button>

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

            editMode: false,

            user: {
                name: "",
                role: null,
                email: "",
                sucursal: null,
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
                    key: "role",
                    label: "Rol",
                    sortable: true,
                    sortDirection: "desc",
                },
                {
                    key: "sucursal",
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
        loadData() {
            axios
                .get("/admin/users")
                .then((response) => {
                    console.log(response.data);
                    this.items = response.data.data;
                    this.countItems = this.items.length;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
        },
        info(item, index, button) {
            this.infoModal.string = JSON.stringify(item, null, 2);
            this.infoModal.content.id = item.id;
            this.infoModal.title = `Row index: ${this.infoModal.content.id}`;
            this.user.name = item.name;
            this.user.email = item.email;
            this.user.sucursal = item.sucursal_id;
            this.user.role = item.role_id;
            this.$root.$emit("bv::show::modal", this.infoModal.id, button);
            console.log(this.infoModal.content);
        },
        resetInfoModal() {
            this.infoModal.title = "";
            this.infoModal.content = {};
            this.infoModal.string = "";
            this.user.name = "";
            this.user.role = null;
            this.user.email = "";
            this.user.sucursal = null;
        },
        deleteUser(id) {
            axios.delete(`/admin/users/${id}`).then((res) => {
                alert("eliminado");

                this.$refs["modal"].hide();


                this.loadData();
            });
        },
        updateUser(id) {
            const params = {
                sucursal_id: this.user.sucursal,
                name: this.user.name,
                email: this.user.email,
                role_id: 1,
            };
            axios.put(`/admin/users/${id}`,params).then((res) => {
                alert("Editado");

                this.$refs["modal"].hide();

                this.loadData();
            });
        },
    },
    created() {
        this.loadData();
    },
};
</script>
