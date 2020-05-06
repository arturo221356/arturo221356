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

        seleccionado: null,
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
            cosa: null,
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

        axios
            .get("/get/sucursales")
            .then((response) => {
                this.sucursales = response.data;
                if (this.seleccionado) {
                    this.selected = response.data.find(
                        (option) => option.id === this.seleccionado
                    );
                   
                   
                }
                 this.isLoading = false;
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            });
    },
    computed: {
        options: function () {
            var options = [];
            options = this.sucursales;
            if (this.todas === true) {
                options.unshift({
                    id: "all",
                    nombre_sucursal: "Todas",
                });
            }
            return options;
        },
    },

};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
