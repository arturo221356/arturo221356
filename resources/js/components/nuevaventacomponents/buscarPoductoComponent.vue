<template>
    <div class="col-xl-12">
        <b-form>
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
                        @keyup.stop="handleSearch"
                    ></b-form-input>

                    <datalist id="search-results">
                        <option
                            v-for="(list, index) in searchResults"
                            :key="index"
                            >{{ list.title }}</option
                        >
                    </datalist>

                    <b-input-group-append>
                        <b-button variant="success" @click="agregarProducto"
                            >Agregar</b-button
                        >
                    </b-input-group-append>
                </b-input-group>
            </b-form-group>
        </b-form>
        
    </div>
</template>

<script>


export default {

    props:['productos'],
    data() {
        return {
            searchValue: null,

            searchResults: [],
        };
    },
    methods: {
        handleSearch: _.debounce(function () {
            this.searchProduct();
        }, 300),

        clearSearchValue() {
            this.searchValue = null;
        },

        searchProduct() {
            if (this.searchValue.length >= 5) {
                axios
                    .get("/search/venta-prediction", {
                        params: { search: this.searchValue },
                    })
                    .then((response) => {
                        this.searchResults = response.data;
                    })
                    .catch((thrown) => {
                        if (axios.isCancel(thrown)) {
                            console.log("Request canceled", thrown.message);
                        } else {
                            // handle error
                        }
                    });
            } else {
                this.searchResults = [];
            }
        },
        agregarProducto() {
            const CancelToken = axios.CancelToken;

            const source = CancelToken.source();

            if (
                !this.productos.find((item) => item.serie == this.searchValue)
            ) {
                axios
                    .get(
                        "/search/venta-exact",
                        {
                            params: { search: this.searchValue },
                        },
                        {
                            cancelToken: source.token,
                        }
                    )
                    .then((response) => {
                        if (response.data.length > 0) {
                            const item = response.data[0].searchable;

                            switch (response.data[0].type) {
                                case "imeis":
                                    const imei = {
                                        id: item.id,
                                        serie: item.imei,
                                        status: item.status,
                                        descripcion: `${item.equipo.marca}  ${item.equipo.modelo}`,
                                        inventario:
                                            item.inventario.inventarioable.name,
                                        precio: item.equipo.precio,
                                        type: "imeis",
                                    };

                                    this.$emit("add-producto", { ...imei });

                                    break;
                                case "iccs":
                                    this.$emit('new-icc',response.data[0].searchable);

                                    break;
                            }
                            this.clearSearchValue();
                        } else {
                            this.$bvToast.toast("No encontrado", {
                                title: `${this.searchValue}`,
                                variant: "warning",
                                solid: true,
                            });
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            } else {
                alert("duplicado");
            }
        },
    },
};
</script>

<style></style>
