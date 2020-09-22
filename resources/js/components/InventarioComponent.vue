<template>
    <div>
        <b-overlay :show="isLoading" rounded="sm">
            <div class="jumbotron">
                <div class="col-md-4 mx-auto">
                    <div v-show="editMode == false">
                        <h1>Reporte de Inventario</h1>
                        <validation-observer
                            ref="observer"
                            v-slot="{ handleSubmit }"
                        >
                            <b-form
                                @submit.prevent="handleSubmit(loadInventario)"
                            >
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
                                <ValidationProvider
                                    v-slot="validationContext"
                                    rules="required"
                                >
                                    <b-form-group
                                        label="Inventario:"
                                        label-size="lg"
                                    >
                                        <select-general
                                            url="/inventario"
                                            pholder="Seleccionar Inventario"
                                            v-model="inventario"
                                            :empty="true"
                                            :todas="
                                                can('get inventarios')
                                                    ? true
                                                    : false
                                            "
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                        >
                                        </select-general>
                                        <b-form-invalid-feedback
                                            ><b-form-invalid-feedback>{{
                                                validationContext.errors[0]
                                            }}</b-form-invalid-feedback></b-form-invalid-feedback
                                        >
                                    </b-form-group>
                                </ValidationProvider>
                                <b-button block variant="primary" type="submit"
                                    >Cargar</b-button
                                >
                            </b-form>
                        </validation-observer>
                    </div>
                    <div v-show="editMode == true" v-if="can('update stock')">
                        <div class="row">
                            <div class="col-sm">
                                <h1
                                    class="float-right"
                                    :style="{ cursor: 'pointer' }"
                                >
                                    <b-icon
                                        icon="x-circle-fill"
                                        variant="danger"
                                        @click="function(){editMode = false; resetEditableItem()}"
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
                            <b-form-group
                                label="Inventario"
                                label-size="lg"
                                v-if="can('full update stock')"
                            >
                                <select-general
                                    url="/inventario"
                                    pholder="Seleccionar Inventario"
                                    v-model="editableItem.inventario"
                                >
                                </select-general>
                            </b-form-group>
                            <b-form-group label="Estatus:" label-size="lg">
                                <select-statuses
                                    estatusable="inventario"
                                    v-model="editableItem.status"
                                ></select-statuses>
                            </b-form-group>

                            <b-form-group
                                label="Compañia:"
                                label-size="lg"
                                v-if="renderCompany"
                            >
                                <select-general
                                    url="/get/companies"
                                    pholder="Seleccionar Compañia"
                                    v-model="editableItem.company"
                                >
                                </select-general>
                            </b-form-group>
                            <b-form-group
                                label="Tipo:"
                                label-size="lg"
                                v-if="renderSimType"
                            >
                                <select-general
                                    url="/get/icctypes"
                                    pholder="Seleccionar tipo de sim"
                                    v-model="editableItem.iccType"
                                    :query="editableItem.company.id"
                                >
                                </select-general>
                            </b-form-group>

                            <b-form-group
                                label="Equipo:"
                                label-size="lg"
                                v-if="renderEquipo"
                            >
                                <select-general
                                    url="/get/equipos"
                                    pholder="Seleccionar Equipo"
                                    v-model="editableItem.equipo"
                                    :equipo="true"
                                >
                                </select-general>
                            </b-form-group>

                            <b-form-group label="Comentario" label-size="lg"
                                ><b-form-textarea
                                    v-model="editableItem.comment"
                                    max-rows="6"
                                    placeholder="Comentario"
                                ></b-form-textarea
                            ></b-form-group>

                            <b-form-group>
                                <b-button block variant="success"
                                    >Guardar</b-button
                                >
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
            tableBusy: false,

            tableFilter: null,

            isLoading: false,

            editMode: false,

            editableItem: {
                inventario: null,
                status: null,
                iccType: null,
                company: null,
                marca: null,
                modelo: null,
                comment: null,
            },

            productoOptions: [
                { text: "Sims", value: "Icc" },
                { text: "Equipos", value: "Imei" },
            ],
            inventario: [],

            inventarioStatus: [],

            item: {},

            lineaStatus: [],

            producto: "Icc",

            tableItems: [],

            countItems: 0,
        };
    },

    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        resetEditableItem() {
            this.editableItem.inventario = null;

            this.editableItem.serie = null;

            this.editableItem.comment = null;

            this.editableItem.company = null;

            this.editableItem.status = null;

            this.editableItem.equipo = null;

            this.editableItem.iccType = null;
        },
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
            
            this.resetEditableItem();
            console.log(item);

            this.editMode = true;

            this.editableItem.inventario = {
                id: item.inventario_id,
                name: item.inventario_name,
            };

            this.editableItem.serie = item.serie;

            if(item.comment){this.editableItem.comment = item.comment.comment;}

            this.editableItem.company = item.company;

            this.editableItem.status = { id: item.status, name: item.status };

            this.editableItem.equipo = item.equipo;

            this.editableItem.iccType = item.type;

            window.scrollTo(0, 0);
        },
        tableFiltered(filteredItems) {
            this.countItems = filteredItems.length;
        },
    },
    computed: {
        renderSimType: function () {
            if (this.can("full update stock") && this.editableItem.company) {
                return true;
            }
        },
        renderCompany: function () {
            if (this.can("full update stock") && this.producto == "Icc") {
                return true;
            }
        },
        renderEquipo: function () {
            if (this.can("full update stock") && this.producto == "Imei") {
                return true;
            }
        },
        computedTableFields: function () {
            var $response = [];
            switch (this.producto) {
                case "Imei":
                    if (this.can("update stock")) {
                        $response = [
                            { key: "serie", label: "Imei" },
                            {
                                key: "inventario_name",
                                label: "Inventario",
                                sortable: true,
                            },
                            { key: "status", label: "Estatus", sortable: true },
                            {
                                key: "equipo.marca",
                                label: "Marca",
                                sortable: true,
                            },
                            {
                                key: "equipo.modelo",
                                label: "Modelo",
                                sortable: true,
                            },
                            {
                                key: "equipo.precio",
                                label: "Precio",
                                sortable: true,
                            },
                            {
                                key: "equipo.costo",
                                label: "Costo",
                                sortable: true,
                            },
                            {
                                key: "created_at",
                                label: "Creado",
                                sortable: true,
                            },
                            {
                                key: "updated_at",
                                label: "Modificado",
                                sortable: true,
                            },
                            { key: "editar", label: "Editar" },
                        ];
                    } else {
                        $response = [
                            { key: "serie", label: "Imei" },
                            { key: "inventario_name", label: "Inventario" },
                            { key: "status", label: "Estatus" },
                            { key: "equipo.marca", label: "Marca" },
                            { key: "equipo.modelo", label: "Modelo" },
                            { key: "precio", label: "Precio" },
                            { key: "created_at", label: "Creado" },
                            { key: "updated_at", label: "Modificado" },
                        ];
                    }

                    return $response;

                    break;

                case "Icc":
                    $response = [];

                    if (this.can("update stock")) {
                        $response = [
                            { key: "serie", label: "Icc" },
                            {
                                key: "inventario_name",
                                label: "Inventario",
                                sortable: true,
                            },
                            { key: "status", label: "Estatus", sortable: true },
                            {
                                key: "company.name",
                                label: "Compañia",
                                sortable: true,
                            },
                            { key: "type.name", label: "Tipo", sortable: true },
                            {
                                key: "created_at",
                                label: "Creado",
                                sortable: true,
                            },
                            {
                                key: "updated_at",
                                label: "Modificado",
                                sortable: true,
                            },
                            { key: "editar", label: "Editar" },
                        ];
                    } else {
                        $response = [
                            { key: "serie", label: "Icc" },
                            {
                                key: "inventario_name",
                                label: "Inventario",
                                sortable: true,
                            },
                            { key: "status", label: "Estatus", sortable: true },
                            {
                                key: "company.name",
                                label: "Compañia",
                                sortable: true,
                            },
                            { key: "type.name", label: "Tipo", sortable: true },
                            {
                                key: "created_at",
                                label: "Creado",
                                sortable: true,
                            },
                            {
                                key: "updated_at",
                                label: "Modificado",
                                sortable: true,
                            },
                        ];
                    }

                    return $response;

                    break;
            }
        },
    },
};
</script>

<style></style>
