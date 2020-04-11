<template>
    <b-container fluid>
        <b-navbar
            toggleable="lg"
            type="light"
            style="background-color: #dedede;"
        >
            <b-navbar-brand href="#"
                >{{ navbarName }} {{ navbarBrand }}</b-navbar-brand
            >

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav>
                    <b-nav-form>
                        <radio-producto
                            :busy="isBusy"
                            :user-role="userRole"
                            v-on:producto="productoChange"
                            v-on:fields="loadfields"
                            class="mb-2 mr-sm-2 mb-sm-0"
                        >
                        </radio-producto>

                        <checkbox-status
                            :producto="producto"
                            v-on:status="statusChange"
                            class="mb-2 mr-sm-2 mb-sm-0"
                        >
                        </checkbox-status>

                        <select-sucursal
                            v-if="
                                userRole == 'supervisor' || userRole == 'admin'
                            "
                            v-on:sucursal="sucursalChange"
                            :todas="true"
                            :seleccionado="null"
                            class="mb-2 mr-sm-2 mb-sm-0"
                        ></select-sucursal>
                    </b-nav-form>
                </b-navbar-nav>

                <!-- Right aligned nav items -->
                <b-navbar-nav class="ml-auto">
                    <b-nav-form>
                        <b-form-input
                            class="mr-sm-2"
                            placeholder="Buscar"
                            id="filterInpt"
                            v-model="filter"
                        ></b-form-input>

                        <export-excel
                            :sucursal-id="sucursalid"
                            :status-id="status"
                            :producto="producto"
                        ></export-excel>
                    </b-nav-form>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>

        <!-- modal de edicion -->
        <edit-modal ref="modal"> </edit-modal>

        <!-- Main table element -->
        <b-table
            id="my-table"
            show-empty
            responsive
            striped
            hover
            stacked="md"
            :items="items"
            :fields="fields"
            :filter="filter"
            :filterIncludedFields="filterOn"
            :sort-by.sync="sortBy"
            :sort-desc.sync="sortDesc"
            :sort-direction="sortDirection"
            @filtered="onFiltered"
            :busy="isBusy"
            selectable
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
                <b-button @click="info(row.item, row.index, producto)">
                    Editar</b-button
                >
            </template>
            <!--boton de editar -->

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
                        v-b-popover.hover.focus.top="row.item.comment.comment"
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
    </b-container>
</template>

<script>
export default {
    props: {
        userRole: { type: String, required: true },
        userSucursal: { type: String },
        navbarName: { type: String, required: true },
    },

    data() {
        return {
            producto: "",
            status: [],
            fields: [],
            fetchUrl: "",
            sucursalid: 0,
            countItems: 0,
            items: [],
            totalRows: 0,
            sortBy: "",
            sortDesc: false,
            sortDirection: "asc",
            filter: null,
            filterOn: [],
            navbarBrand: "",
            isBusy: false,
        };
    },

    computed: {
        sortOptions() {
            // Create an options list from our fields
            return this.fields
                .filter((f) => f.sortable)
                .map((f) => {
                    return { text: f.label, value: f.key };
                });
        },
        rows() {
            return this.items.length;
        },
    },
    watch: {},

    mounted() {
        if (this.userSucursal) {
            console.log("loading datadfdf");
            this.sucursalid = this.userSucursal;
            this.loadData();
        }
    },
    methods: {
        info(item, index, producto) {
            this.$refs.modal.info(item, index, producto);
        },

        makeToast(variant = null) {
            this.$bvToast.show("Toast body content", {
                title: `Variant ${variant || "default"}`,
                variant: variant,
                solid: true,
            });
        },

        //carga la informacion de la base de datos dependiendo de la Utl que es la variable fetchurl
        loadData() {
            console.log(this.sucursal);

            this.isBusy = true;

            axios
                .post(this.fetchUrl, {
                    sucursal_id: this.sucursalid,

                    status: this.status,
                })
                .then((response) => {
                    this.items = response.data.data;

                    this.countItems = this.items.length;

                    this.isBusy = false;

                    console.log(this.totalRows);
                });
        },

        //detecta el cambio de sucursal
        sucursalChange(value) {
            this.sucursal = value;

            this.navbarBrand = this.sucursal.text;

            this.sucursalid = this.sucursal.id;

            this.loadData();
        },

        //carga los datos si la la sucursal esta en blanco
        statusChange(value) {
            this.status = value;

            if (this.navbarBrand != "") {
                this.loadData();
            }
        },

        //detecta el cambio de producto
        productoChange(value) {
            this.producto = value;

            if (value == "equipos") {
                this.fetchUrl = "/post/imeis/";
            } else {
                this.fetchUrl = "/post/iccs/";
            }
            if (this.actualSucursal != "") {
                this.loadData();
            }
        },

        loadfields(value) {
            this.fields = value;
        },

        onFiltered(filteredItems) {
            this.countItems = filteredItems.length;
        },
    },

    created() {},
};
</script>
