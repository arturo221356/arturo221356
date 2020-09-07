<template>
    <div>
        <multiselect
            v-model="selected"
            :options="options"
            :placeholder="pholder"
            label="name"
            track-by="id"
            :allow-empty="empty"
            :loading="isLoading"
            :class="stateClass"
            @input="emitToParent"
            :custom-label="customLabel"
            
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
        empty: {
            type: Boolean,
            required: false,
            default: false,
        },

        url: {
            type: String,
            required: true,
        },

        equipo:{
            type:Boolean,
            required:false,
            default: false,
        },

        pholder: {
            type: String,
            required: false,
            detault: "Seleccionar Opcion",
        },

        value: {},

        state: {
            type: Boolean,
            required: false,
            default: null,
        },
        query:{
            type: Number,
            required: false,
            default: null
        }
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
        customLabel({name, marca, modelo}){
            
            if(this.equipo === true){

                return `${marca}--${modelo}`;
            }else{
            
            return name;}
        },
        loadData() {
            this.isLoading = true;
             
            const param = this.query;

            axios.get(`${this.url}?param=${param}`).then(
                function (response) {
                    this.options = response.data;

                    this.isLoading = false;
                }.bind(this)
            );
        },
    },

    created: function () {
        this.loadData();
    },
    watch:{
        url: function(){
            this.loadData();
        },
        query: function(){
            this.loadData();
            this.selected = null;
            this.emitToParent();
        },
        value: function (){
            this.selected = this.value;
        }
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
