<template>
    <div>
        <b-overlay :show="isLoading" rounded="sm">
            <div class="jumbotron">
                <div class="col-md-8 mx-auto">
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
                                <b-form-group
                                    v-if="can('update stock')"
                                    label="Eliminados"
                                    label-size="lg"
                                >
                                    <b-form-radio-group
                                        buttons
                                        v-model="onlyTrash"
                                        button-variant="outline-danger"
                                        :options="[
                                            { text: 'Si', value: true },
                                            { text: 'No', value: false },
                                        ]"
                                    >
                                    </b-form-radio-group>
                                </b-form-group>
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
                                        @click="cancelEdit"
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

                        <validation-observer
                            ref="observer"
                            v-slot="{ handleSubmit }"
                            v-if="editMode == true"
                        >
                            <b-form @submit.prevent="handleSubmit(updateItem)">
                                <ValidationProvider
                                    name="inventario"
                                    v-slot="validationContext"
                                    rules="required"
                                    v-if="can('full update stock')"
                                >
                                    <b-form-group
                                        label="Inventario"
                                        label-size="lg"
                                    >
                                        <select-general
                                            url="/inventario"
                                            pholder="Seleccionar Inventario"
                                            v-model="editableItem.inventario"
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
                                    name="estatus"
                                    v-slot="validationContext"
                                    rules="required"
                                >
                                    <b-form-group
                                        label="Estatus:"
                                        label-size="lg"
                                    >
                                        <select-statuses
                                            estatusable="inventario"
                                            v-model="editableItem.status"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                        ></select-statuses>
                                        <b-form-invalid-feedback>{{
                                            validationContext.errors[0]
                                        }}</b-form-invalid-feedback>
                                    </b-form-group>
                                </ValidationProvider>

                                <ValidationProvider
                                    name="compañia"
                                    v-slot="validationContext"
                                    rules="required"
                                    v-if="fullUpdateIcc"
                                >
                                    <b-form-group
                                        label="Compañia:"
                                        label-size="lg"
                                    >
                                        <select-general
                                            url="/get/companies"
                                            pholder="Seleccionar Compañia"
                                            v-model="editableItem.company"
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
                                    name="tipo sim"
                                    v-slot="validationContext"
                                    rules="required"
                                    v-if="renderSimType"
                                >
                                    <b-form-group label="Tipo:" label-size="lg">
                                        <select-general
                                            url="/get/icctypes"
                                            pholder="Seleccionar tipo de sim"
                                            v-model="editableItem.iccType"
                                            :query="editableItem.company.id"
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
                                    name="dn"
                                    v-slot="validationContext"
                                    rules="numeric|required|length:10"
                                    v-if="renderDn"
                                >
                                    <b-form-group label="DN:" label-size="lg">
                                        <b-input-group>
                                            <b-input
                                                type="number"
                                                placeholder="Inserta DN"
                                                v-model="editableItem.lineaDn"
                                                :state="
                                                    getValidationState(
                                                        validationContext
                                                    )
                                                "
                                            ></b-input>
                                            <b-input-group-append>
                                                <b-button
                                                    v-if="can('destroy stock')"
                                                    variant="outline-danger"
                                                    @click="deleteLinea"
                                                    >Eliminar linea</b-button
                                                >
                                            </b-input-group-append>
                                        </b-input-group>
                                        <b-form-invalid-feedback>{{
                                            validationContext.errors[0]
                                        }}</b-form-invalid-feedback>
                                    </b-form-group>
                                </ValidationProvider>
                                <ValidationProvider
                                    name="estatus linea"
                                    v-slot="validationContext"
                                    rules="required"
                                    v-if="renderDn"
                                >
                                    <b-form-group
                                        label="Estatus linea:"
                                        label-size="lg"
                                    >
                                        <select-statuses
                                            estatusable="linea"
                                            v-model="
                                                editableItem.lineaStatus.name
                                            "
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                        ></select-statuses>
                                        <b-form-invalid-feedback>{{
                                            validationContext.errors[0]
                                        }}</b-form-invalid-feedback>
                                    </b-form-group>
                                </ValidationProvider>

                                <ValidationProvider
                                    name="recarga"
                                    v-slot="validationContext"
                                    rules="required"
                                    v-if="renderRecarga"
                                >
                                    <b-form-group
                                        label="Recarga:"
                                        label-size="lg"
                                    >
                                        <b-form-select
                                            v-model="
                                                editableItem.lineaStatus.reason
                                            "
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                            :options="recargaOptions"
                                        ></b-form-select>
                                        <b-form-invalid-feedback>{{
                                            validationContext.errors[0]
                                        }}</b-form-invalid-feedback>
                                    </b-form-group>
                                </ValidationProvider>

                                <ValidationProvider
                                    name="equipo"
                                    v-slot="validationContext"
                                    rules="required"
                                    v-if="renderEquipo"
                                >
                                    <b-form-group
                                        label="Equipo:"
                                        label-size="lg"
                                    >
                                        <select-general
                                            url="/get/equipos"
                                            pholder="Seleccionar Equipo"
                                            v-model="editableItem.equipo"
                                            :equipo="true"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                        >
                                        </select-general>
                                    </b-form-group>
                                </ValidationProvider>

                                <b-form-group label="Comentario" label-size="lg"
                                    ><b-form-textarea
                                        v-model="editableItem.comment"
                                        max-rows="6"
                                        placeholder="Comentario"
                                    ></b-form-textarea
                                ></b-form-group>

                                <b-form-group>
                                    <b-button
                                        block
                                        variant="success"
                                        type="submit"
                                        >Guardar</b-button
                                    >
                                </b-form-group>

                                <b-form-group v-if="can('destroy stock')">
                                    <b-button
                                        block
                                        variant="danger"
                                        @click="deleteItem"
                                        >Eliminar</b-button
                                    >
                                </b-form-group>
                            </b-form>
                        </validation-observer>
                    </div>
                </div>
                <div class="col-md-11 mx-auto mt-5">
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
                        stacked="sm"
                        head-variant="dark"
                        :table-variant="onlyTrash == true ? 'danger' : 'light'"
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
                            <b-button
                                @click="editItem(row.item, row.index)"
                                variant="info"
                            >
                                Editar
                                {{ producto }}</b-button
                            >
                        </template>
                        <!--boton de editar -->
                        <template v-slot:cell(restaurar)="row">
                            <b-button
                                @click="restoreItem(row.item.id)"
                                variant="warning"
                            >
                                Restaurar
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

            onlyTrash: false,

            tableFilter: null,

            isLoading: false,

            editMode: false,

            recargaOptions: [
                { value: 50, text: "Recarga de $50" },

                { value: 100, text: "Recarga de $100" },
            ],

            editableItem: {
                inventario: null,
                status: null,
                iccType: null,
                company: null,
                equipo: { marca: null, modelo: null, id: null },
                comment: null,
                // editableItem: null,
                lineaDn: null,
                lineaId: null,
                lineaStatus: { name: { name: null, id: null }, reason: null },
                id: null,
            },

            productoOptions: [
                { text: "Sims", value: "Icc" },
                { text: "Equipos", value: "Imei" },
            ],
            inventario: null,

            item: {},

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
            this.editableItem = {
                inventario: null,
                status: null,
                iccType: null,
                equipo: { marca: null, modelo: null, id: null },
                modelo: null,
                comment: null,
                // editableItem: null,
                lineaDn: null,
                lineaId: null,
                lineaStatus: { name: { name: null, id: null }, reason: null },
                id: null,
            };
        },
        loadInventario() {
            this.tableBusy = true;

            axios
                .get(`/inventario/${this.inventario.id}`, {
                    params: {
                        producto: this.producto,

                        onlyTrash: this.onlyTrash,
                    },
                })
                .then(
                    function (response) {
                        this.tableItems = response.data.data;

                        this.countItems = this.tableItems.length;

                        this.tableBusy = false;
                    }.bind(this)
                )
                .catch(function (error) {
                    console.log(error);
                });
        },
        editItem(item, row) {
            this.resetEditableItem();

            this.editMode = true;

            this.editableItem.inventario = {
                id: item.inventario_id,
                name: item.inventario_name,
            };

            this.editableItem.id = item.id;

            this.editableItem.serie = item.serie;

            this.editableItem.status = {
                id: item.status,
                name: item.status,
            };

            if (item.comment) {
                this.editableItem.comment = item.comment.comment;
            }

            switch (this.producto) {
                case "Icc":
                    this.editableItem.lineaDn = item.linea_dn;

                    this.editableItem.lineaId = item.linea_id;

                    this.editableItem.company = item.company;

                    this.editableItem.lineaStatus.name = {
                        name: item.linea_status.name,
                        id: item.linea_status.name,
                    };

                    this.editableItem.lineaStatus.reason =
                        item.linea_status.reason;

                    this.editableItem.iccType = item.type;

                    break;

                case "Imei":
                    this.editableItem.equipo = item.equipo;
                    break;
            }

            window.scrollTo(0, 0);
        },
        updateItem() {
            this.isLoading = true;
            axios
                .put(
                    `/${this.producto.toLowerCase()}/${this.editableItem.id}`,
                    this.updateItemData
                )
                .then(
                    function (response) {
                        this.isLoading = false;

                        this.$bvToast.toast("Actualizado con exito", {
                            title: `${this.producto} ${this.editableItem.serie}`,
                            variant: "success",
                            solid: true,
                        });

                        this.reloadTable();

                        
                    }.bind(this)
                )
                .catch(function (error) {
                    console.log(error);
                });
        },
        deleteItem() {
            this.isLoading = true;
            axios

                .delete(
                    `/${this.producto.toLowerCase()}/${this.editableItem.id}`,
                    {}
                )
                .then(
                    function (response) {
                        this.isLoading = false;

                         this.$bvToast.toast("Eliminado con exito", {
                            title: `${this.producto} ${this.editableItem.serie}`,
                            variant: "danger",
                            solid: true,
                        });

                        this.reloadTable();
                        
                       
                        
                    }.bind(this)
                )
                .catch(function (error) {
                    console.log(error);
                });
        },
        restoreItem(id) {
            this.isLoading = true;
            axios
                .post(`/${this.producto.toLowerCase()}/restore`, { id })
                .then(
                    function (response) {
                        this.isLoading = false;

                        this.$bvToast.toast("Restaurado con exito", {
                            title: `${this.producto}`,
                            variant: "warning",
                            solid: true,
                        });

                        this.reloadTable();
                    }.bind(this)
                )
                .catch(function (error) {
                    console.log(error);
                });
        },
        
        cancelEdit() {
            this.editMode = false;
            this.resetEditableItem();
        },
        deleteLinea() {
            this.isLoading = true;
            axios
                .delete(`/linea/${this.editableItem.lineaId}`)
                .then(
                    function (response) {
                        this.$bvToast.toast("Linea eliminada con exito", {
                            title: `${this.editableItem.lineaDn}`,
                            variant: "danger",
                            solid: true,
                        });
                        this.isLoading = false;
                        this.reloadTable();
                    }.bind(this)
                )
                .catch(function (error) {
                    console.log(error);
                });
        },
        tableFiltered(filteredItems) {
            this.countItems = filteredItems.length;
        },
        cancelEdit() {
            this.editMode = false;
            this.resetEditableItem();
        },
        reloadTable() {
            this.editMode = false;
            this.resetEditableItem();
            this.loadInventario();
        },
    },
    computed: {
        updateItemData: function () {
            var response = {
                comment: this.editableItem.comment,

                inventario_id: this.editableItem.inventario.id,

                status: this.editableItem.status.name,
            };

            switch (this.producto) {
                case "Icc":
                    response.company_id = this.editableItem.company.id;

                    response.icc_type_id = this.editableItem.iccType.id;

                    response.lineaDn = this.editableItem.lineaDn;

                    response.lineaStatus = this.editableItem.lineaStatus.name.name;

                    response.montoRecarga = this.editableItem.lineaStatus.reason;

                    break;

                case "Imei":
                    response.equipo_id = this.editableItem.equipo.id;
                    break;
            }

            return response;
        },

        renderSimType: function () {
            if (this.can("full update stock") && this.editableItem.company) {
                return true;
            }
        },

        renderDn: function () {
            if (this.can("full update stock") && this.editableItem.lineaId) {
                return true;
            }
        },
        renderRecarga: function () {
            if (
                this.can("full update stock") &&
                this.editableItem.lineaStatus.name &&
                this.editableItem.lineaStatus.name.id == "Recargable"
            ) {
                return true;
            }
        },
        fullUpdateIcc: function () {
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
                    if (this.can("full update stock")) {
                        $response.splice(6, 0, {
                            key: "equipo.costo",
                            label: "Costo",
                            sortable: true,
                        });
                    }

                    if (this.can("update stock")) {
                        if (
                            this.onlyTrash == true &&
                            this.can("full update stock")
                        ) {
                            $response.push({
                                key: "restaurar",
                                label: "Restaurar",
                            });
                        } else if (this.onlyTrash == false) {
                            $response.push({
                                key: "editar",
                                label: "Editar",
                            });
                        }
                    }

                    return $response;

                    break;

                case "Icc":
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
                        {
                            key: `linea_status.name`,
                            label: "Estatus Linea",
                            sortable: true,
                        },
                        {
                            key: `linea_status.reason`,
                            label: "Rercarga asignada",
                            sortable: true,
                        },
                        {
                            key: "preactivated_at",
                            label: "Preactivado",
                            sortable: true,
                        },
                        {
                            key: "linea_dn",
                            label: "DN",
                            sortable: true,
                        },
                    ];

                    if (this.can("update stock")) {
                        if (
                            this.onlyTrash == true &&
                            this.can("full update stock")
                        ) {
                            $response.push({
                                key: "restaurar",
                                label: "Restaurar",
                            });
                        } else if (this.onlyTrash == false) {
                            $response.push({ key: "editar", label: "Editar" });
                        }
                    }

                    return $response;

                    break;
            }
        },
    },
    watch: {
        onlyTrash: function () {
            if (this.inventario) {
                this.loadInventario();
            }
        },
    },
};
</script>

<style></style>
