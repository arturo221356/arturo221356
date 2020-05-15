<template>
    <div>
        <multiselect
            v-model="selected"
            :options="options"
            placeholder="Seleccionar rol"
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
       

        seleccionado: null,
    },
    mounted() {},
    data() {
        return {
            isLoading: false,
            role: {
                id: 0,
                text: "",
            },
            roles: [],

            selected: null,
           
        };
    },
    methods: {
        emitToParent() {
            this.role.id = this.selected.id;

            this.role.text = this.selected.name;

            this.$emit("role", this.role);
        },
    },
    watch: {
        selected: function () {
            this.emitToParent();
        },
       
    },

    created: function () {
        this.isLoading = true;

        axios
            .get("/admin/roles")
            .then((response) => {
                this.roles = response.data;
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
            options = this.roles;

            return options;
        },
    },

};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
