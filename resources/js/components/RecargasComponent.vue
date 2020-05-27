<template>
    <div>
        <b-navbar
            toggleable="lg"
            type="light"
            style="background-color: #dedede;"
        >
            <b-navbar-brand href="#">Recargas</b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav>
                    <b-link @click="newRecarga">Agregar Recarga</b-link>
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
                    <b-button @click="editRecarga(row.item, row.index)">
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
                                rules="required"
                                autocomplete="off"
                            >
                                <b-form-group label="Nombre">
                                    <b-form-input
                                        type="text"
                                        v-model="recarga.name"
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

                            <!-- monto -->
                            <ValidationProvider
                                name="monto"
                                v-slot="validationContext"
                                rules="required|integer|max_value:9999"
                            >
                                <b-form-group label="Monto">
                                    <b-form-input
                                        type="number"
                                        v-model="recarga.monto"
                                        placeholder="Monto"
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
                            <b-button
                                size="sm"
                                variant="outline-danger"
                                @click="deleteRecarga(infoModal.content.id)"
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

            editMode: null,

            isBusy: false,

            filter: null,

            countItems:0,

            recarga: {
                name: null,
                monto: null,
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
                    key: "monto",
                    label: "Monto",
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
                .get("/get/recargas")
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
                this.updateRecarga();
            } else if (this.editMode == false) {
                this.storeRecarga();
            }
        },
        editRecarga(item, index, button) {
            this.editMode = true;
            this.infoModal.string = JSON.stringify(item, null, 2);
            this.infoModal.content.id = item.id;
            this.infoModal.title = `Row index: ${this.infoModal.content.id}`;
            this.recarga.name = item.name;
            this.recarga.monto = item.monto;
            this.$root.$emit("bv::show::modal", this.infoModal.id, button);
            console.log(this.infoModal.content);
        },
        resetInfoModal() {
            this.infoModal.title = "";
            this.infoModal.content = {};
            this.infoModal.string = "";
            this.recarga.name = "";
            this.recarga.monto = "";
            this.modalLoading = false;
        },
        deleteRecarga(id) {
            axios.delete(`/admin/productos/recargas/${id}`).then((res) => {
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
        updateRecarga() {
            const id = this.infoModal.content.id;
            const params = {
                name: this.recarga.name,
                monto: this.recarga.monto,
            };
            axios.put(`/admin/productos/recargas/${id}`, params).then((res) => {
                alert("Editado");

                this.$refs["modal"].hide();

                this.loadData();
            });
        },
        storeRecarga() {
            const params = {
                name: this.recarga.name,
                monto: this.recarga.monto,
            };
            axios.post(`/admin/productos/recargas/`, params).then((res) => {
                this.$refs["modal"].hide();

                this.loadData();

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

        newRecarga() {
            this.editMode = false;
            this.recarga.name = 'Recarga Tiempo Aire';
            this.$refs["modal"].show();
            console.log(`editmode ${this.editMode}`);
        },
    },
    created(){
        this.loadData();
    }
};
</script>

<style></style>
