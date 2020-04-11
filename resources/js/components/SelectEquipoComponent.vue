<template>
    <select
        v-model="selected"
        class="form-control"
        @change="emitToParent"
        required
    >
        <option value="">Seleccionar equipo</option>

        <option v-for="equipo in equipos" :key="equipo.id" :value="equipo.id"
            >{{ equipo.marca }}-{{ equipo.modelo }} ${{ equipo.precio }}</option
        >
    </select>
</template>
<script>
export default {
    props: {
        seleccionado: "",
    },

    data() {
        return {
            equipos: [],

            equipo: { id: 0, text: "" },

            selected: null,
        };
    },

    created: function () {
        axios.get("/get/equipos").then(
            function (response) {
                this.selected = this.seleccionado;

                this.equipos = response.data;
            }.bind(this)
        );
    },
    methods: {
        emitToParent(event) {
            this.equipo.id = this.selected;

            //   var i = event-1;

            //   this.equipo.text = this.equipos[i].marca+' '+this.equipos[i].modelo

            this.$emit("equipo", this.equipo);
        },
    },
};
</script>
