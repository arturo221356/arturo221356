<template>
    <div>
        <multiselect
            v-model="selected"
            :options="options"
            placeholder="Seleccionar Estatus"
            :multiple="multiple"
            label="name"
            track-by="id"
            :allow-empty="empty"
            :loading="isLoading"
            :class="stateClass"
            @input="emitToParent"
            :disabled="disabled"
        ></multiselect>
        <label class="typo__label form__label invalid" v-show="state === false"
            >Elige un valor</label
        >
    </div>
</template>

<script>
export default {
    props: {
        todas: {
            type: Boolean,
            required: false,
            default: false,
        },
        multiple: {
            type: Boolean,
            required: false,
            default: false,
        },
        disabled: {
            type: Boolean,
            required: false,
            default: false,
        },
        empty: {
            type: Boolean,
            required: false,
            default: false,
        },

        estatusable: {
            type: String,
            required: true,
        },

        pholder: {
            type: String,
            required: false,
            detault: "Seleccionar Estatus",
        },

        value: {},

        state: {
            type: Boolean,
            required: false,
            default: null,
        },
    },
    mounted() {},
    data() {
        return {
            isLoading: false,

            selected: null,
        };
    },
    methods: {
        emitToParent() {
            this.$emit("input", this.selected);
        },
    },

    created: function () {
        this.selected = this.value;
    },
    watch: {
        value: function () {
            this.selected = this.value;
        },
    },
    computed: {
        options: function () {
            var optionsArray = [];

            switch (this.estatusable) {
                case "inventario":
                    var options = [
                        "Disponible",
                        "Incompleto",
                        "Perdido",
                        "Robado",
                    ];

                    break;
                case "linea":
                    var options = [
                        "Preactiva",
                        "Exportada",
                    ];
                    if(this.can('asignar recarga')){
                        options.push('Recargable');
                    }

                break;
            }
            options.forEach((element) => {
                optionsArray.push({ id: element, name: element });
            });

            return optionsArray;
        },

        stateClass: function () {
            var response;

            switch (this.state) {
                case true:
                    response = "valid";
                    break;

                case false:
                    response = "invalid";
                    break;
                case null:
                    response = null;
                    break;
            }
            return response;
        },
    },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
.typo__label {
    color: red;
}
.multiselect.invalid .multiselect__tags {
    border: 1px solid #f86c6b !important;
}
.multiselect.valid .multiselect__tags {
    border: 1px solid #00d134 !important;
}

.multiselect__option--highlight {
    background: #428bca !important;
}

.multiselect__option--highlight:after {
    background: #428bca !important;
}

.multiselect__tag-icon:focus,
.multiselect__tag-icon:hover {
    background: #f0f0f0 !important;
}

.multiselect__tag-icon:focus:after,
.multiselect__tag-icon:hover:after {
    color: red !important;
}
</style>
