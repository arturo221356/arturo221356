<template>
    <div>
        <b-overlay :show="isLoading" rounded="sm">
            <div class="jumbotron jumbotron-fluid">
                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <h1>Nueva venta</h1>

                        <b-form @submit.prevent="agregarProducto">
                            <b-form-group
                                class="mt-4"
                                label="Buscar producto:"
                                label-size="lg"
                            >
                                <b-input-group>
                                    <b-form-input
                                        v-model="searchValue"
                                        autocomplete="off"
                                        placeholder="Buscar Producto"
                                        list="search-results"
                                        @update="searchProduct"
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
                                            type="submit"
                                            variant="success"
                                            >Agregar</b-button
                                        >
                                    </b-input-group-append>
                                </b-input-group>
                            </b-form-group>
                        </b-form>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-10 mx-auto">
                        asdaodnoasdono
                    </div>
                </div>
            </div>
        </b-overlay>
    </div>
</template>

<script>
export default {
    data: function () {
        return {
            isLoading: false,

            searchValue: null,

            searchResults: [],
        };
    },

    methods: {
        agregarProducto() {
            alert("agregar");
        },
        searchProduct() {
            const self = this;
            if (this.searchValue.length >= 5) {
                axios
                    .get("/search/venta-prediction", {
                        params: { search: this.searchValue },
                    })
                    .then(function (response) {
                        console.log(response.data);

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
