<template>
    <div>
        <div class="row">
            <div class="col-sm mt-auto">
                <h5>Resultado: {{ countItems }}</h5>
            </div>
        </div>

        <b-table
            :items="tableItems"
            :fields="tableFields"
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
                <b-button @click="editAccesorio(row.item, row.index)" variant="info">
                    Editar
                </b-button>
            </template>
            <!--boton de editar -->
            <template v-slot:cell(restaurar)="row">
                <b-button @click="restoreItem(row.item.id)" variant="warning">
                    Restaurar
                </b-button>
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
                        v-b-popover.hover.focus.top="row.item.comment"
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

            <template #cell(inventarios)="data">
                <div v-for="inventario in data.value" :key="inventario.id">
                    <b>{{ inventario.inventarioable.name }}</b
                    >---<b>Cantidad:</b> {{ inventario.pivot.stock }} <br />
                </div>
            </template>
        </b-table>
        <edit-accesorio-component
            v-on:data-changed="loadData"
            ref="editAccesorio"
        ></edit-accesorio-component>
    </div>
</template>

<script>
import EditAccesorioComponent from "./editAccesorioComponent.vue";

export default {
    components: { EditAccesorioComponent },

    props: ["onlyTrash", "inventario_id", "tableFilter"],

    data() {
        return {
            countItems: 0,

            tableBusy: false,

            tableItems: [],

            tableFields: [],
        };
    },
    methods: {
        tableFiltered(filteredItems) {
            this.countItems = filteredItems.length;
        },
        editItem() {
            alert("editar");
        },
        restoreItem() {
            alert("restaurar");
        },
        editAccesorio(item, index) {
            this.$refs["editAccesorio"].editAccesorio(item, index);
        },
        loadData() {
            this.tableBusy = true;

            this.tableFields = this.computedTableFields;

            axios
                .get(`/inventario/${this.inventario_id}`, {
                    params: {
                        producto: "Accesorios",

                        onlyTrash: this.onlyTrash,
                    },
                })
                .then((response) => {
                    if (this.inventario_id == "all") {
                        this.tableItems = response.data.data;

                        this.countItems = this.tableItems.length;
                    } else {
                        this.tableItems = response.data;
                    }

                    this.tableBusy = false;
                })
                .catch((error) => {
                    alert(error);
                    this.tableBusy = false;
                });
        },
    },
    watch: {
        onlyTrash: function () {
            this.loadData();
        },
    },
    computed: {
        computedTableFields: function () {
            var $response = [];

            $response = [
                { key: "codigo", label: "Codigo" },
                {
                    key: "name",
                    label: "Nombre",
                    sortable: true,
                },
                { key: "description", label: "Descripcion", sortable: true },

                {
                    key: "precio",
                    label: "Precio",
                    sortable: true,
                },
            ];
            if (this.can("full update stock")) {
                $response.push({
                    key: "costo",
                    label: "Costo",
                    sortable: true,
                });
            }
            if (this.inventario_id == "all") {
                $response.push({
                    key: "inventarios",
                    label: "Inventarios",
                    sortable: true,
                });
            } else {
                $response.push({
                    key: "pivot.stock",
                    label: "Cantidad",
                    sortable: true,
                });
                if (this.can("update stock")) {
                    $response.push({
                        key: "editar",
                        label: "Editar",
                    });
                }
            }

            return $response;
        },
    },
};
</script>

<style></style>
