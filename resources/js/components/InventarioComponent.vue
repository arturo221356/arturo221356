<template>
    <div>
        <b-overlay :show="isLoading" rounded="sm">
            <div class="jumbotron">
                <div class="col-md-4 mx-auto">
                    <div v-show="editMode == false">
                        <h1 v-if="can('siajdosij')">Reporte de Inventario</h1>
                        <b-form>
                            <b-form-group>
                                <b-form-radio-group
                                    class="align-middle"
                                    id="radio-producto"
                                    v-model="producto"
                                    :options="productoOptions"
                                    buttons
                                    name="radio-producto"
                                    @change="tableItems = []"
                                ></b-form-radio-group>
                            </b-form-group>

                            <b-form-group label="Inventario:" label-size="lg">
                                <select-general
                                    url="/admin/inventarios"
                                    pholder="Seleccionar Inventario"
                                    v-model="inventario"
                                    :empty="true"
                                    :todas="true"
                                >
                                </select-general>
                                {{ inventario }}
                            </b-form-group>
                            <b-form-group
                                label="Inventario estatus:"
                                label-size="lg"
                            >
                                <select-general
                                    url="/admin/inventarios"
                                    pholder="Seleccionar Inventario"
                                    v-model="inventarioStatus"
                                    :multiple="true"
                                    :empty="true"
                                    :todas="true"
                                >
                                </select-general>
                                {{ inventarioStatus }}
                            </b-form-group>

                            <b-form-group
                                label="Linea estatus:"
                                label-size="lg"
                            >
                                <select-general
                                    url="/admin/inventarios"
                                    pholder="Seleccionar Inventario"
                                    v-model="lineaStatus"
                                    :multiple="true"
                                    :empty="true"
                                    :todas="true"
                                >
                                </select-general>
                                {{ lineaStatus }}
                            </b-form-group>
                            <b-button
                                block
                                variant="primary"
                                @click="loadInventario"
                                >Cargar</b-button
                            >
                        </b-form>
                    </div>
                    <div v-show="editMode == true">
                        <div class="row">
                            <div class="col-sm">
                                <h1
                                    class="float-right"
                                    :style="{ cursor: 'pointer' }"
                                >
                                    <b-icon
                                        icon="x-circle-fill"
                                        variant="danger"
                                        @click="editMode = false"
                                    ></b-icon>
                                </h1>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <h1>
                                    Editar
                                    {{ producto }}
                                </h1>
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-sm">
                                    <h3>
                                        {{ editableItem.serie }}
                                    </h3>
                                </div>
                            
                        </div>
                       
                        <b-form> 
                            <b-form-group label="Inventario" label-size="lg">
                                <select-general
                                    url="/admin/inventarios"
                                    pholder="Seleccionar Inventario"
                                    v-model="inventario"
                                    :empty="true"
                                    :todas="true"
                                >
                                </select-general>
                                {{ inventario }}
                            </b-form-group>
                            <b-form-group
                                label="estatus:"
                                label-size="lg"
                            >
                                <select-general
                                    url="/admin/inventarios"
                                    pholder="Seleccionar Inventario"
                                    v-model="lineaStatus"
                                    :multiple="true"
                                    :empty="true"
                                    :todas="true"
                                >
                                </select-general>
                                {{ lineaStatus }}
                            </b-form-group>
                            <b-form-group
                                label="Linea estatus:"
                                label-size="lg"
                            >
                                <select-general
                                    url="/admin/inventarios"
                                    pholder="Seleccionar Inventario"
                                    v-model="lineaStatus"
                                    :multiple="true"
                                    :empty="true"
                                    :todas="true"
                                >
                                </select-general>
                                {{ lineaStatus }}
                            </b-form-group>
                            <b-form-group
                                label="Compañia:"
                                label-size="lg"
                            >
                                <select-general
                                    url="/admin/inventarios"
                                    pholder="Seleccionar Inventario"
                                    v-model="lineaStatus"
                                    :multiple="true"
                                    :empty="true"
                                    :todas="true"
                                >
                                </select-general>
                                {{ lineaStatus }}
                            </b-form-group>
                            <b-form-group
                                label="Tipo:"
                                label-size="lg"
                            >
                                <select-general
                                    url="/admin/inventarios"
                                    pholder="Seleccionar Inventario"
                                    v-model="lineaStatus"
                                    :multiple="true"
                                    :empty="true"
                                    :todas="true"
                                >
                                </select-general>
                                {{ lineaStatus }}
                            </b-form-group>
                            <b-form-group label="Comentario" label-size="lg"><b-form-textarea max-rows="6" placeholder="Comentario"></b-form-textarea></b-form-group>
                            <b-form-group>
                                <b-button block variant="success">Guardar</b-button>
                            </b-form-group>
                        </b-form>
                        
                    </div>
                </div>
                <div
                    class="col-md-11 mx-auto mt-5"
                    v-if="tableItems.length > 0"
                >
                    <div class="row">
                        <div class="col-sm mt-auto">
                            <h5>Resultado: {{ countItems }}</h5>
                        </div>
                        <div class="col-sm float-right">
                            <b-form-group label="Filtrar:" label-size="sm"
                                ><b-input
                                    placeholder="Filtrar"
                                    type="search"
                                    v-model="tableFilter"
                                ></b-input>
                            </b-form-group>
                        </div>
                    </div>

                    <b-table
                        :items="tableItems"
                        :fields="computedTableFields"
                        :filter="tableFilter"
                        hover
                        responsive
                        striped
                        stacked="md"
                        head-variant="dark"
                        table-variant="light"
                        :busy="tableBusy"
                        @filtered="tableFiltered"
                    >
                        <!--busy template-->
                        <template v-slot:table-busy>
                            <div class="text-center text-primary my-2">
                                <b-spinner class="align-middle"></b-spinner>
                                <strong>Cargando...</strong>
                            </div>
                        </template>

                        <template v-slot:table-caption
                            >Resultado: - {{ countItems }}
                        </template>

                        <!--boton de editar -->
                        <template v-slot:cell(editar)="row">
                            <b-button @click="editItem(row.item, row.index)">
                                Editar
                                {{ producto }}</b-button
                            >
                        </template>

                        <template v-slot:cell(status)="row">
                            {{ row.item.status }}
                            <b-link>
                                <svg
                                    class="bi bi-chat-square"
                                    width="1em"
                                    height="1em"
                                    viewBox="0 0 16 16"
                                    fill="currentColor"
                                    xmlns="http://www.w3.org/2000/svg"
                                    v-if="row.item.comment"
                                    v-b-popover.hover.focus.top="
                                        row.item.comment.comment
                                    "
                                    :title="row.item.status"
                                    variant="primary"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M14 1H2a1 1 0 00-1 1v8a1 1 0 001 1h2.5a2 2 0 011.6.8L8 14.333 9.9 11.8a2 2 0 011.6-.8H14a1 1 0 001-1V2a1 1 0 00-1-1zM2 0a2 2 0 00-2 2v8a2 2 0 002 2h2.5a1 1 0 01.8.4l1.9 2.533a1 1 0 001.6 0l1.9-2.533a1 1 0 01.8-.4H14a2 2 0 002-2V2a2 2 0 00-2-2H2z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </b-link>
                        </template>
                    </b-table>
                </div>
            </div>
        </b-overlay>
    </div>
</template>

<script>
export default {
    props: {},
    data() {
        return {
            userRole: "admin",

            tableBusy: false,

            tableFilter: null,

            isLoading: false,

            editMode: false,

            editableItem: {},

            productoOptions: [
                { text: "Sims", value: "Icc" },
                { text: "Equipos", value: "Imei" },
            ],
            inventario: [],

            inventarioStatus: [],

            lineaStatus: [],

            producto: "Icc",

            tableItems: [],

            countItems: 0,
        };
    },
    methods: {
        loadInventario() {
            this.tableBusy = true;
            this.isLoading = true;
            axios
                .get(`/inventario/${this.inventario.id}`, {
                    params: {
                        producto: this.producto,
                    },
                })
                .then(
                    function (response) {
                        this.tableItems = response.data.data;

                        this.countItems = this.tableItems.length;

                        this.tableBusy = false;

                        this.isLoading = false;
                    }.bind(this)
                )
                .catch(function (error) {
                    console.log(error);
                });
        },
        editItem(item, row) {
            this.editableItem = {};
            console.log(item);
            console.log(row);
            this.editMode = true;

            this.editableItem = item;
            window.scrollTo(0,0);
        },
        tableFiltered(filteredItems) {
            this.countItems = filteredItems.length;
        },
    },
    computed: {
        computedTableFields: function () {
            if (this.producto == "Imei") {
                switch (this.userRole) {
                    case "admin":
                        return [
                            { key: "serie", label: "Imei" },
                            { key: "inventario_name", label: "Inventario" },
                            { key: "status", label: "Estatus" },
                            { key: "marca", label: "Marca" },
                            { key: "modelo", label: "Modelo" },
                            { key: "precio", label: "Precio" },
                            { key: "costo", label: "Costo" },
                            { key: "created_at", label: "Agregado" },
                            { key: "updated_at", label: "Modificado" },
                            { key: "editar", label: "Editar" },
                        ];
                        break;

                    case "supervisor":
                        break;

                    default:
                        break;
                }
            } else if (this.producto == "Icc") {
                switch (this.userRole) {
                    case "admin":
                        return [
                            { key: "serie", label: "Icc" },
                            { key: "inventario_name", label: "Inventario" },
                            { key: "status", label: "Estatus" },
                            { key: "company", label: "Compañia" },
                            { key: "type", label: "Tipo" },
                            { key: "created_at", label: "Agregado" },
                            { key: "updated_at", label: "Modificado" },
                            { key: "editar", label: "Editar" },
                        ];
                        break;
                    case "supervisor":
                        break;
                    default:
                }
            }
        },
    },
};
</script>

<style></style>
