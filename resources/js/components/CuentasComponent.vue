<template>
    <div>
        <b-overlay :show="isLoading" rounded="sm">
            <div class="jumbotron jumbotron-fluid">
                <div class="col-xl-6 mx-auto">
                    <h1>Cuentas</h1>

                    <div class="mt-8">
                        <h3>Sucursales:</h3>
                        <b-list-group class="mt-3">
                            <b-list-group-item
                                button
                                v-for="inventario in inventarios"
                                :key="inventario.id"
                                :active="selectedListItem == inventario.id"
                                @click="selectCaja(inventario.id, inventario.caja.id)"
                            >
                                <div
                                    class="d-flex w-100 justify-content-between"
                                >
                                    <h5>
                                        <B>{{
                                            inventario.inventarioable.name
                                        }}</B>
                                    </h5>

                                    <small
                                        ><B>Ultimo Corte:</B> 12/12/12
                                        13:00</small
                                    >
                                </div>

                                <div class="float-right">
                                    Total: ${{ inventario.caja.total }}
                                </div>
                            </b-list-group-item>
                        </b-list-group>
                    </div>
                    <div class="mt-3">
                        <div>
                            <b-tabs content-class="mt-3" v-if="selectedListItem">
                                <b-tab title="Resumen" active
                                pills 
                                    ><p>Resumen</p></b-tab
                                >
                                <b-tab title="Gastos"
                                    ><p>Gastos</p></b-tab
                                >
                                <b-tab title="Cortes"
                                    ><p>Cortes</p></b-tab
                                >
                            </b-tabs>
                        </div>
                    </div>
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

            inventarios: [],

            selectedListItem: null,
        };
    },
    methods: {
        loadCajas() {
            axios
                .get("/get/cajas")

                .then((response) => {
                    this.inventarios = response.data;
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        selectCaja(inventarioId,cajaId){
            this.selectedListItem = inventarioId;
        }
    },
    created() {
        this.loadCajas();
    },
};
</script>

<style></style>
