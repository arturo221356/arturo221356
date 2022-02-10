<template>
    <div>
        <form class="form-inline my-2 my-lg-0" @submit.prevent="handleSearch()">
            <input
                class="form-control mr-sm-2"
                type="search"
                placeholder="Buscar Icc, Imei o Linea"
                v-model="searchInput"
            />
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">
                Buscar
            </button>
        </form>
        <search-icc-modal-component-vue  ref="icc"></search-icc-modal-component-vue>
        <search-imei-modal-component-vue ref="imei"></search-imei-modal-component-vue>
    </div>
</template>

<script>

import searchIccModalComponentVue from './searchIccModalComponent.vue';
import searchImeiModalComponentVue from './searchImeiModalComponent.vue';

export default {
    components:{
        searchIccModalComponentVue,
        searchImeiModalComponentVue
    },
    data() {
        return {
            searchInput: null,
        };
    },
    methods: {
        handleSearch() {
            const params = {
                searchInput: this.searchInput,
            };
            axios
                .post("/search/navbar-search", params)
                .then((response) => {
                    if (response.data.length > 0) {
                        this.loadItem(
                            response.data[0].url,
                            response.data[0].type
                        );
                    } else {
                        this.$bvToast.toast("No encontrado", {
                            title: `${this.searchInput}`,
                            variant: "warning",
                            solid: true,
                        });
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        loadItem($url, $type) {
            axios
                .get($url)
                .then((response) => {
                    console.log($type);

                    switch($type){
                        case 'iccs':
                            this.$refs.icc.load(response.data.data);
                        break;
                        case 'imeis':
                                this.$refs.imei.load(response.data.data);
                        break;
                        case 'lineas':
                            this.$refs.icc.load(response.data.data);
                        break;
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
};
</script>

<style></style>
