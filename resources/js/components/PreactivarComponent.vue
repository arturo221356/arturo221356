<template>
    <div>
        <b-overlay :show="isLoading" rounded="sm">
            <b-navbar
                toggleable="lg"
                type="light"
                style="background-color: #dedede;"
            >
                <b-navbar-brand href="#">Preactivar</b-navbar-brand>

                <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

                <b-collapse id="nav-collapse" is-nav>
                    <b-navbar-nav> </b-navbar-nav>

                    <!-- Right aligned nav items -->
                    <b-navbar-nav class="ml-auto"> </b-navbar-nav>
                    <div></div>
                </b-collapse>
            </b-navbar>
            <div class="jumbotron jumbotron-fluid">
                <div class="col-md-8 mx-auto">
                    <h1>Preactivar lineas:</h1>

                    <validation-observer
                        ref="observer"
                        v-slot="{ handleSubmit }"
                        v-show="lineaDetail == false"
                    >
                        <b-form @submit.prevent="handleSubmit(verificarIcc)">
                            <ValidationProvider
                                name="icc"
                                v-slot="validationContext"
                                rules="required|Icc|numeric"
                            >
                                <b-form-group
                                    label="Icc:"
                                    label-size="lg"
                                    class="mt-4"
                                >
                                    <b-input-group prepend="Icc">
                                        <b-form-input
                                            v-model.lazy="icc"
                                            type="number"
                                            list="search-results"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                            placeholder="Agregar Icc"
                                        ></b-form-input>
                                        <datalist id="search-results">
                                            <option
                                                v-for="(list,
                                                index) in searchResults"
                                                :key="index"
                                                >{{ list.title }}</option
                                            >
                                        </datalist>

                                        <b-input-group-append>
                                            <b-button
                                                variant="success"
                                                type="submit"
                                                >Agregar</b-button
                                            >
                                        </b-input-group-append>
                                        <b-form-invalid-feedback>{{
                                            validationContext.errors[0]
                                        }}</b-form-invalid-feedback>
                                    </b-input-group>
                                </b-form-group>
                            </ValidationProvider>
                        </b-form>
                    </validation-observer>
                    <validation-observer v-if="lineaDetail == true">
                        <b-form >
                        <b-input v-model="currentIcc.dn"></b-input>

                        <a>{{currentIcc.company}}</a>

                        <a>{{currentIcc.type}}</a>

                        <a>{{currentIcc.icc}}</a>

                        <b-button @click="preactivarIcc">Preactivar</b-button>
                    </b-form>
                    </validation-observer>
                    
                </div>
            </div>
        </b-overlay>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            icc: null,
            searchResults: [],
            iccs: [],
            lineaDetail: false,
            dn: null,
            currentIcc: {
                icc: null,
                company: null,
                type: null,
                dn: null
            },
            
        };
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        verificarIcc() {
            axios
                .post("/linea/verificar-icc", { icc: this.icc })
                .then((response) => {
                    console.log(response.data);

                    if (response.data.success == true) {

                        this.lineaDetail = true;

                        this.currentIcc.icc = response.data.data.icc;

                        this.currentIcc.company =
                            response.data.data.company.name;

                        this.currentIcc.type = response.data.data.type.name;

                        this.icc = null;
                    }else{
                        alert(response.data.message);
                    }
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
        },
        preactivarIcc(){
                        axios
                .post("/preactivar-prepago", { 
                    icc: this.currentIcc.icc,
                    
                    dn: this.currentIcc.dn
                    
                    })
                .then((response) => {
                    console.log(response.data);

                    if (response.data.success == true) {

                        this.lineaDetail = true;

                        this.currentIcc.icc = response.data.data.icc;

                        this.currentIcc.company =
                            response.data.data.company.name;

                        this.currentIcc.type = response.data.data.type.name;

                        this.icc = null;
                    }else{
                        alert(response.data.message);
                    }
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
        }
    },
    watch: {
        icc: function (val) {
            self = this;
            if (val >= 5) {
                axios
                    .get("/search/traspaso-prediction", {
                        params: { search: val },
                    })
                    .then(function (response) {
                        self.searchResults = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            } else {
                self.searchResults = [];
            }
        },
    },
};
</script>

<style></style>
