<template>
    <div>
        <b-navbar
            toggleable="lg"
            type="light"
            style="background-color: #dedede;"
        >
            <b-navbar-brand href="#">Accesorios</b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav>
                    <b-link @click="newAccesorio">Agregar Accesorio</b-link>
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
            @hide="resetInfoModal"
            ref="modal"
        >
            <b-overlay :show="modalLoading" blur="1px" rounded="sm" variant="transparent">
                <validation-observer ref="observer" v-slot="{ handleSubmit }">
                    <b-form @submit.prevent="handleSubmit(onSubmit)">
                        <!-- marca  -->
                        <ValidationProvider
                            name="name"
                            v-slot="validationContext"
                            rules="required"
                        >
                            <b-form-group label="Nombre">
                                <b-form-input
                                    type="text"
                                    v-model="accesorio.name"
                                    placeholder="Nombre"
                                    :state="
                                        getValidationState(validationContext)
                                    "
                                    autocomplete="off"
                                >
                                </b-form-input>
                                <b-form-invalid-feedback
                                    >{{ validationContext.errors[0] }}
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </ValidationProvider>

                        <!-- modelo -->
                        <ValidationProvider
                            name="description"
                            v-slot="validationContext"
                            rules="required"
                        >
                            <b-form-group label="Descripcion">
                                <b-form-input
                                    type="text"
                                    v-model="accesorio.description"
                                    placeholder="Descripcion"
                                    :state="
                                        getValidationState(validationContext)
                                    "
                                     autocomplete="off"
                                >
                                </b-form-input>
                                <b-form-invalid-feedback
                                    >{{ validationContext.errors[0] }}
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </ValidationProvider>

                        <!-- precio -->
                        <ValidationProvider
                            name="precio"
                            v-slot="validationContext"
                            rules="integer|required|numeric|positive|max_value:999999"
                        >
                            <b-form-group label="Precio">
                                <b-form-input
                                    type="number"
                                    v-model="accesorio.precio"
                                    placeholder="Precio de venta"
                                    :state="
                                        getValidationState(validationContext)
                                    "
                                     autocomplete="off"
                                >
                                </b-form-input>
                                <b-form-invalid-feedback>{{
                                    validationContext.errors[0]
                                }}</b-form-invalid-feedback>
                            </b-form-group>
                        </ValidationProvider>

                        <!-- costo -->
                        <ValidationProvider
                            name="costo"
                            v-slot="validationContext"
                            rules="integer|required|numeric|positive|max_value:999999"
                        >
                            <b-form-group label="Costo">
                                <b-form-input
                                    type="number"
                                    v-model="accesorio.costo"
                                    placeholder="Costo"
                                    :state="
                                        getValidationState(validationContext)
                                    "
                                     autocomplete="off"
                                >
                                </b-form-input>
                                <b-form-invalid-feedback
                                    id="input-1-live-feedback"
                                    >{{
                                        validationContext.errors[0]
                                    }}</b-form-invalid-feedback
                                >
                            </b-form-group>
                        </ValidationProvider>

                        <!-- botones de guardar -->
                        <b-button size="sm" variant="primary" type="submit">
                            Guardar
                        </b-button>

                        <b-button
                            size="sm"
                            variant="outline-danger"
                            @click="deleteAccesorio(infoModal.content.id)"
                            v-if="editMode == true"
                        >
                            Eliminar
                        </b-button>
                    </b-form>
                </validation-observer>
                
            </b-overlay>
            <!-- Footer del modal Botones -->

                <template v-slot:modal-footer="{ cancel }">
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
                >Resultado: - {{ items.length }}
            </template>

            <!--boton de editar -->
            <template v-slot:cell(editar)="row">
                <b-button @click="editAccesorio(row.item, row.index)">
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
            modalLoading: false,
            isLoading: false,
            items: [],
            
            isBusy: false,
            editMode: false,

            accesorio: {
                name: "",
                description: null,
                precio: null,
                costo: null,
            },

            fields: [
                
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
                    key: "description",
                    label: "Descripcion",
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
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },

        loadData() {
            this.isBusy = true;
            axios
                .get("/get/otros")
                .then((response) => {
                    
                    this.items = response.data;

                    this.isBusy = false;
                })
                .catch(function (error) {
                    // handle error
                    this.isBusy = false;
                    console.log(error);
                });
        },
        editAccesorio(item, index, button) {
            this.editMode = true;
            this.infoModal.string = JSON.stringify(item, null, 2);
            this.infoModal.content.id = item.id;
            this.infoModal.title = `Row index: ${this.infoModal.content.id}`;
            this.accesorio =  {...item};
            this.$root.$emit("bv::show::modal", this.infoModal.id, button);
            
        },

        deleteAccesorio(id) {
            this.modalLoading = true;

            axios.delete(`/otros/${id}`).then((res) => {
                this.$refs["modal"].hide();
                this.loadData();
                
                console.log(res.data);

                this.$bvToast.toast(`${res.data.message}`, {
                    title: res.data.title,
                    autoHideDelay: 5000,
                    appendToast: true,
                    solid: true,
                    variant: res.data.variant,
                });
            }).catch(error =>{
                this.modalLoading = false;
                alert(error);
            });
        },
        updateAccesorio() {
            const id = this.infoModal.content.id;
            const params = {
                name: this.accesorio.name,
                description: this.accesorio.description,
                precio: this.accesorio.precio,
                costo: this.accesorio.costo,
            };
            axios.put(`/otros/${id}`, params).then((res) => {
                alert("Editado");

                this.$refs["modal"].hide();

                this.loadData();

                
            }).catch(error =>{
                this.modalLoading = false;
                alert(error);
            });
        },
        storeAccesorio() {
            const params = {
                name: this.accesorio.name,
                description: this.accesorio.description,
                precio: this.accesorio.precio,
                costo: this.accesorio.costo,
            };
            axios.post(`/otros`, params).then((res) => {
                
                this.$refs["modal"].hide();

                this.loadData();

                this.$bvToast.toast(`accesorio Agregado con exito`, {
                    title: "Exito",
                    autoHideDelay: 5000,
                    appendToast: true,
                    solid: true,
                    variant: "success",
                });
            }).catch(error=>{
                alert(error);
                this.modalLoading = false; 
            });
         
        },
        resetInfoModal() {
            this.infoModal.title = "";
            this.infoModal.content = {};
            this.infoModal.string = "";
            this.accesorio.name = "";
            this.accesorio.description = "";
            this.accesorio.precio = "";
            this.accesorio.costo = "";
            this.modalLoading = false;
        },
        onSubmit() {
            
            this.modalLoading = true;

            if (this.editMode == true) {
                this.updateAccesorio();
                
            } else if (this.editMode == false) {
                this.storeAccesorio();
                
            }
            
            
        },
        newAccesorio() {
            this.editMode = false;
            this.$refs["modal"].show();
           
        },
    },
    created() {
        this.loadData();
    },

};
</script>
