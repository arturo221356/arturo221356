<template>
    <div>

        <multiselect
            v-model="selected"
            :options="options"
            placeholder="Seleccionar Sucursal"
            label="nombre_sucursal"
            track-by="id"
            :allow-empty="false"
            :loading="isLoading"
        ></multiselect>
    </div>
</template>

<script>
export default {
    props: {
        todas: Boolean,

        seleccionado:null,
    },
    mounted() {},
    data() {
        return {
            isLoading: false,
            sucursal: {
                id: 0,
                text: "",
            },
            sucursales: [],

            selected: null,
        };
    },
    methods: {
        emitToParent() {
            this.sucursal.id = this.selected.id;

            this.sucursal.text = this.selected.nombre_sucursal;

            this.$emit("sucursal", this.sucursal);
        },
    },
    watch: {
        selected: function () {
            
            this.emitToParent();
        },
        sucursales: function () {},
    },

    created: function () {
        this.isLoading = true;
        axios.get("/get/sucursales").then(
            function (response) {
                this.sucursales = response.data;

                this.isLoading = false;

                if (this.seleccionado) {
                    this.selected = this.sucursales[this.seleccionado - 1];
                }
            }.bind(this)
        );
    },
    computed: {
        options: function () {
            var options = [];
            options = this.sucursales;
            if (this.todas === true) {
                options.unshift({
                    id: "all",
                    nombre_sucursal: "-----Todas-----",
                });
            }
            return options;
        },
    },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
