<template>
    <div>
        <div class="col-lg-8 mx-auto">
            <h1>Reporte {{nameReporte}}</h1>

            <b-form>
                <div class="row mt-4">
                    <div class="col-lg-6">
                        <b-form-group label="Fecha Inicial">
                            <b-form-datepicker
                                v-model="initialDate"
                                locale="es"
                                :max="maxDate"
                            ></b-form-datepicker>
                        </b-form-group>
                    </div>
                    <div class="col-lg-6">
                        <b-form-group label="Fecha Final">
                            <b-form-datepicker
                                v-model="finalDate"
                                :max="maxDate"
                                locale="es"
                            ></b-form-datepicker>
                        </b-form-group>
                    </div>
                </div>
                <b-form-group label="Inventario:" label-size="lg">
                    <select-general
                        url="/inventario"
                        pholder="Seleccionar Inventario"
                        v-model="inventario"
                        query="App\Sucursal"
                        :empty="true"
                        :todas="can('get inventarios') ? true : false"
                    >
                    </select-general>
                </b-form-group>
                <b-button block @click="loadData">Cargar</b-button>
            </b-form>
        </div>
    </div>
</template>

<script>
export default {
    props: ["postUrl","nameReporte"],
    data() {
        return {
            inventario: null,

            initialDate: new Date().toISOString().substr(0, 10),

            finalDate: new Date().toISOString().substr(0, 10),
        };
    },
    methods: {
        loadData() {
            this.$emit("is-loading", true);
            axios
                .post(this.postUrl, {
                    initialDate: this.initialDate,
                    finalDate: this.finalDate,
                    inventario_id: this.inventario.id,
                })
                .then((response) => {
                    this.$emit("data-loaded", response.data);

                    this.$emit("is-loading", false);
                })
                .catch((error) => {
                    alert(error);
                    this.$emit("is-loading", false);
                });
        },
    },
    computed: {
        maxDate: function () {
            const now = new Date();
            const today = new Date(
                now.getFullYear(),
                now.getMonth(),
                now.getDate()
            );

            return today;
        },
    },
};
</script>

<style></style>
