<template>
    <div>
        <b-navbar
            toggleable="lg"
            type="light"
            style="background-color: #dedede;"
        >
            <b-navbar-brand href="#">Usuarios</b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav>
                    <b-link @click="loadData">Agregar usuario</b-link>
                </b-navbar-nav>

                <!-- Right aligned nav items -->
                <b-navbar-nav class="ml-auto">
                    <b-form-input type="search"></b-form-input>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>
        <div>
            <b-table striped hover :items="items" :fields="fields"></b-table>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            items: [],
            fields: [],
        };
    },
    methods: {
        loadData() {
            axios
                .get("/admin/users")
                .then((response) =>{
                    console.log(response.data);
                    this.items = response.data;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
        },
    },
};
</script>
