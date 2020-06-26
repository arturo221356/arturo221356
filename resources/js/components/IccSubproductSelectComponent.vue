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
        parentProduct: {
            type: Number,
            required: true,
        },
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
        loadData() {

            this.isLoading = true;

            const params = this.parentProduct;

            axios
                .get("/get/icc-subproducts", {
                    params: { icc_product_id: this.parentProduct },
                })
                .then(
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
        emitToParent() {
            
            if(this.selected != null){
            this.producto.id = this.selected.id;

            this.producto.text = `${this.selected.name}`;

            this.$emit("iccsubproducto", this.producto);
            }else{
                this.$emit("iccsubproducto", null);
            }
        },
    },
    watch: {
        selected: function () {
            this.emitToParent();
        },
        parentProduct: function () {
            this.selected = null;
            this.loadData();
        },
    },

    created: function () {
        this.loadData();
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
