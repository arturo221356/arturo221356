<template>
    <div>
        <multiselect
            v-model="selected"
            :options="options"
            placeholder="Seleccionar Equipo"
            label="marca"
            track-by="id"
            :allow-empty="false"
            :loading="isLoading"
            :custom-label="marcaWithModelo"
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
            equipo: {
                id: 0,
                text: "",
            },
            equipos: [],

            selected: null,
        };
    },
    methods: {
        emitToParent() {
            this.equipo.id = this.selected.id;

            this.equipo.text = `${this.selected.marca}--${this.selected.modelo}`;

            this.$emit("equipo", this.equipo);
        },
        marcaWithModelo({ marca, modelo }) {
            return `${marca}--${modelo}`;
        },
    },
    watch: {
        selected: function () {
            this.emitToParent();
        },
    },

    created: function () {
        this.isLoading = true;
        axios.get("/get/equipos").then(
            function (response) {
                this.equipos = response.data;

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
            options = this.equipos;
            return options;
        },
    },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
