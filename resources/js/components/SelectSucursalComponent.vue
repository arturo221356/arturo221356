<template>
    <b-form-select
        v-model="selected"
        :options="sucursales"
        @input="emitToParent"
        value-field="id"
        text-field="nombre_sucursal"
    >
        <template v-slot:first>
            <b-form-select-option :value="null">
                Seleccionar sucursal
            </b-form-select-option>
            <b-form-select-option value="all" v-if="todas == true">
                Todas
            </b-form-select-option>
        </template>
    </b-form-select>
</template>

<script>
export default {
    props: {
        todas: Boolean,

        seleccionado: "",
    },
    mounted() {},
    data() {
        return {
            sucursal: {
                id: 0,
                text: "",
            },
            sucursales: [],

            selected: null,
        };
    },
    methods: {
        getSucursales: function () {
            axios.get("/get/sucursales").then(
                function (response) {
                    this.selected = this.seleccionado;

                    this.sucursales = response.data;
                }.bind(this)
            );
        },

        emitToParent(event) {
            this.sucursal.id = this.selected;

            if (this.sucursal.id == "all") {
                this.sucursal.text = "Todas";
            } else {
                var i = event - 1;

                this.sucursal.text = this.sucursales[i].nombre_sucursal;
            }

            this.$emit("sucursal", this.sucursal);
        },
    },
    mounted: function () {},

    created: function () {
        this.getSucursales();
    },
};
</script>
