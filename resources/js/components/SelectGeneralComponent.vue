<template>
    <div>
        <multiselect
            v-model="selected"
            :options="options"
            :placeholder="pholder"
            :multiple="multiple"
            label="name"
            track-by="id"
            :allow-empty="empty"
            :loading="isLoading"
            :class="stateClass"
            @input="emitToParent"
            :custom-label="customLabel"
            :disabled="disabled"
        ></multiselect>
        <label class="typo__label form__label invalid" v-show="state === false"
            >Elige un valor</label
        >
    </div>
</template>

<script>
export default {
    prop: ["value"],

    props: {
        todas: {
            type: Boolean,
            required: false,
            default: false,
        },
        disabled: {
            type: Boolean,
            required: false,
            default: false,
        },
        multiple: {
            type: Boolean,
            required: false,
            default: false,
        },
        empty: {
            type: Boolean,
            required: false,
            default: false,
        },
        value: {},

        url: {
            type: String,
            required: true,
        },

        equipo: {
            type: Boolean,
            required: false,
            default: false,
        },
        accesorio: {
            type: Boolean,
            required: false,
            default: false,
        },

        pholder: {
            type: String,
            required: false,
            detault: "Seleccionar Opcion",
        },

        state: {
            type: Boolean,
            required: false,
            default: null,
        },

        query: {
            required: false,
            default: null,
        },
        query2: {
            type: Number,
            required: false,
            default: null,
        },
    },
    mounted() {},
    data() {
        return {
            isLoading: false,

            options: [],

            selected: null,
        };
    },
    methods: {
        emitToParent() {
            this.$emit("input", this.selected);
        },
        customLabel({ name, marca, modelo, precio, description, codigo }) {
            if (this.equipo === true) {
                return `${marca}--${modelo}--$${precio}`;
            } else if(this.accesorio === true){
                 return `${codigo} ${name}-$${precio}-${description}`;
            }else{
                return name;
            }
        },

        loadData() {
            this.isLoading = true;

            axios
                .get(`${this.url}`, {
                    params: {
                        param: this.query,

                        param2: this.query2,
                    },
                })
                .then(
                    function (response) {
                        this.options = response.data;

                        if (this.todas) {
                            this.options.unshift({ id: "all", name: "Todos" });
                        }

                        if (this.value) {
                            if (Array.isArray(this.value)) {
                                var selectedArray = [];

                                this.value.forEach((element) => {
                                    var opcion = this.options.find(
                                        (option) => option.id == element
                                    );

                                    selectedArray.push(opcion);
                                });

                                this.selected = selectedArray;
                            } else if (this.value.hasOwnProperty("id")) {
                                this.selected = this.value;
                                
                            } else {
                                this.selected = this.options.find(
                                    (option) => option.id == this.value
                                );
                            }
                            
                        }

                        this.isLoading = false;
                    }.bind(this)
                );
        },
    },

    created: function () {
        this.loadData();
    },
    watch: {
        url: function () {
            this.loadData();
        },
        selected: function(){
             this.emitToParent();
        },

        query: function () {
            this.loadData();
            this.selected = null;
            this.emitToParent();
        },
        value: function (newVal, oldVal) {
            if (newVal != oldVal) {
                if (!newVal) {
                    this.selected = null;
                }
            }
        },
    },
    computed: {
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
