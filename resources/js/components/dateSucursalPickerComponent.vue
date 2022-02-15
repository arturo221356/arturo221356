<template>
    <div class="container">
        <div class="col-lg-8 mx-auto">
            <div class="row">
                <h1>{{ titeName }}</h1>
            </div>

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
                <b-overlay
                    :show="buttonBusy"
                    rounded
                    opacity="0.6"
                    spinner-small
                    spinner-variant="primary"
                >
                    <b-button
                        block
                        @click="loadData"
                        :disabled="inventario == null ? true : false"
                        >Cargar</b-button
                    >
                </b-overlay>
            </b-form>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        postUrl: { type: String, required: true },

        titeName: { type: String, required: true },
    },

    data() {
        var tzoffset = new Date().getTimezoneOffset() * 60000;
        //offset in milliseconds
        var localISOTime = new Date(Date.now() - tzoffset)
            .toISOString();

       const  today = localISOTime .substr(0, 10);

        return {
            inventario: null,

            buttonBusy: false,

            initialDate: today,

            finalDate: today,
        };
    },
    methods: {
        loadData() {
            this.buttonBusy = true;
            this.$emit("is-loading", true);
            axios
                .post(this.postUrl, {
                    initialDate: this.initialDate,
                    finalDate: this.finalDate,
                    inventario_id: this.inventario.id,
                })
                .then((response) => {
                    this.$emit("data-loaded", response.data);
                    this.buttonBusy = false;
                    this.$emit("is-loading", false);
                })
                .catch((error) => {
                    alert(error);
                    this.buttonBusy = false;
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
