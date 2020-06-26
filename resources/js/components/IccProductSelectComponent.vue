<template>
    <div>
        <multiselect
            v-model="selected"
            :options="options"
            placeholder="Seleccionar Producto"
            label="name"
            track-by="id"
            :allow-empty="false"
            :loading="isLoading"
           
        ></multiselect>
    </div>
</template>

<script>
export default {
    props: {
        

        seleccionado: 1,
    },
    mounted() {},
    data() {
        return {
            isLoading: false,
            producto: {
                id: 0,
                text: "",
            },
            productos: [],

            selected: null,
        };
    },
    methods: {
        emitToParent() {
            this.producto.id = this.selected.id;

            this.producto.text = `${this.selected.name} `;

            this.$emit("action", this.producto);
        },
    },
    watch: {
        selected: function () {
            this.emitToParent();
        },
    },

    created: function () {
        this.isLoading = true;
        axios.get("/get/icc-products").then(
            function (response) {
                this.productos = response.data;

                this.isLoading = false;
                if (this.seleccionado) {
                    this.selected = response.data.find(
                        (option) => option.id === this.seleccionado
                    );
                }
            }.bind(this)
        );
    },
    computed: {
        options: function () {
            var options = [];
            options = this.productos;
            return options;
        },
    },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>