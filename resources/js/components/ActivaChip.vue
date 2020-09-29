<template>
    <div class="jumbotron">
        <div class="col-md-6 mx-auto">
            <b-form>
                <h1>Ingresa el numero de telefono</h1>
                <b-form-group class="mt-4">
                    <b-input
                        v-model="dn"
                        type="number"
                        placeholder="Ingresa numero de telefono"
                    >
                    </b-input>
                </b-form-group>
                <b-button type="button" block @click="recargarChip">
                    Recargar</b-button
                >
                <b-form-group> </b-form-group>
            </b-form>

            <div class="row">
                <div class="col-md-12">
                    <b-alert :variant="response.success == true ? 'success':'danger'" show v-if="response">{{response.message}}</b-alert>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: function () {
        return {
            dn: null,

            response: null,
        };
    },
    methods: {
        recargarChip() {
            axios.post("/recarga-chip", { dn: this.dn }).then((response) => {
                console.log(response.data);

                this.response = response.data;
            });
        },
    },
};
</script>

<style></style>
