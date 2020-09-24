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
        };
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        verificarIcc() {
            axios
                .post("/linea/verificar-icc", { icc: this.icc })
                .then(function (response) {
                    console.log(response.data);
                });
        },

    },
    watch:{
        icc: function(val){
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
        }
    }
};
</script>

<style></style>
