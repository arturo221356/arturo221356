<template>
    <div>
        <b-navbar
            toggleable="lg"
            type="light"
            style="background-color: #dedede;"
        >
            <b-navbar-brand href="#">Equipos</b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav>
                    <b-link @click="loadData">Agregar Equipo</b-link>
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
                <b-button @click="editUser(row.item, row.index)">
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
            isLoading: false,
            items: [],
            countItems: 0,
            isBusy:false,
            fields: [
                {
                    key: "id",
                    label: "#",
                    sortable: true,
                    sortDirection: "desc",
                },
                {
                    key: "marca",
                    label: "Marca",
                    sortable: true,
                    sortDirection: "desc",
                },
                {
                    key: "modelo",
                    label: "Modelo",
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
        };
    },
    methods: {
        newEquipo() {
            alert("nuevo equipo");
        },

        loadData() {
            this.isBusy = true;
            axios
                .get("/get/equipos")
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
    },
    created(){
        this.loadData();
    },
    computed: {
        options: function () {
            var options = [];
            options = this.equipos;
            return options;
        },
    }
};
</script>
