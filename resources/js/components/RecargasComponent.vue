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
                        v-model="filterTable"
                        placeholder="Buscar"
                    ></b-form-input>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>
        <div>
            <b-table
                striped
                hover
                :items="tableItems"
                :fields="tableFields"
                :busy="tableLoading"
                :filterTable="filterTable"
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
                            <ValidationProvider
                                name="codigo"
                                v-slot="validationContext"
                                rules="required|min:5"
                                autocomplete="off"
                                spellcheck="false"
                            >
                                <b-form-group label="Codigo">
                                    <b-form-input
                                        type="text"
                                        v-model="recarga.codigo"
                                        placeholder="Codigo"
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
                                        :state="getValidationState(
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
                                name="company"
                                v-slot="validationContext"
                                rules="required"
                            >
                                <b-form-group label="Compañia">
                                    <select-general
                                        
                                        v-model="recarga.company"
                                        url="/get/companies"
                                        pholder="Seleccionar Compañia"
                                        :state="getValidationState(
                                                validationContext
                                            )
                                        "
                                    >
                                    </select-general>
                                    
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
                                @click="deleteRecarga(infoModal.content.id)"
                                v-if="editMode === true"
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
           

            tableItems: [],

            modalLoading: false,

            editMode: null,

            tableLoading: false,

            filterTable: null,

            recarga: {
                name: null,
                monto: null,
                company: null,
                codigo: null,
            },
            tableFields: [
                {
                    key: "id",
                    label: "#",
                    sortable: true,
                    sortDirection: "desc",
                },
                {
                    key: "codigo",
                    label: "Codigo",
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
                    key: "company.name",
                    label: "Compañia",
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
            },
        };
    },
    computed:{
        countItems: function(){
            var count = 0;
                if(this.tableItems.length > 0 ){
                    count = this.tableItems.length;
                }
            return count;
        }
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            
            return dirty || validated ? valid : null;
        },
        loadData() {
            this.tableLoading = true;
            axios
                .get("/get/recargas")
                .then((response) => {
                   
                    this.tableItems = response.data.data;
                    this.tableLoading = false;
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

            this.infoModal.content.id = item.id;

            this.infoModal.title = `Row index: ${this.infoModal.content.id}`;

            this.recarga.codigo = item.codigo;

            this.recarga.name = item.name;

            this.recarga.monto = item.monto;

            this.recarga.company = item.company;

            this.$root.$emit("bv::show::modal", this.infoModal.id, button);

        },
        resetInfoModal() {
            this.infoModal.title = "";

            this.infoModal.content = {};

            Object.keys(this.recarga).forEach(k => delete this.recarga[k]);

            this.modalLoading = false;
        },
        deleteRecarga(id) {
            axios.delete(`/admin/productos/recargas/${id}`).then((res) => {
                this.$refs["modal"].hide();
                this.loadData();
                console.log(res.data);

                // notificacion
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
        //Actualiza los datos de la recarga
        updateRecarga() {
            const id = this.infoModal.content.id;
            const params = {
                codigo: this.recarga.codigo,
                name: this.recarga.name,
                monto: this.recarga.monto,
                company_id: this.recarga.company.id,
            };
            axios.put(`/admin/productos/recargas/${id}`, params).then((res) => {
                this.$refs["modal"].hide();

                this.loadData();

                // notifiacion
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
        //guarda una nueva recarga en la base de datos 
        storeRecarga() {
            const params = {
                codigo: this.recarga.codigo,
                name: this.recarga.name,
                monto: this.recarga.monto,
                company_id: this.recarga.company.id,
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
            this.recarga.name = "Recarga Tiempo Aire ";
            this.$refs["modal"].show();
        },

    },
    created() {
        this.loadData();
    },
};
</script>

<style></style>
