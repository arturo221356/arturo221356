<template>
    <div>
        <b-navbar
            toggleable="lg"
            type="light"
            style="background-color: #dedede;"
        >
            <b-navbar-brand href="#">Sucursales</b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav>
                    <b-link @click="newSucursal">Agregar Sucursal</b-link>
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
                                        v-model="sucursal.name"
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

            countItems: 0,

            modalLoading: false,

            editMode: null,

            isBusy: false,

            filter: null,

            sucursal: {
                name: "",
                address: "",
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
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        loadData() {
            this.isBusy = true;
            axios
                .get("/sucursales")
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
            };
            axios.put(`/sucursales/${id}`, params).then((res) => {
                alert("Editado");

                this.$refs["modal"].hide();

                this.loadData();
            });
        },
        storeSucursal() {
            const params = {
                name: this.sucursal.name,
                address: this.sucursal.address,
            };
            axios.post(`/sucursales`, params).then((res) => {
                this.$refs["modal"].hide();

                this.loadData();

                this.$bvToast.toast(`Sucursal Agregada con exito`, {
                    title: "Exito",
                    autoHideDelay: 5000,
                    appendToast: true,
                    solid: true,
                    variant: "success",
                    toaster: "b-toaster-bottom-full",
                });
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


