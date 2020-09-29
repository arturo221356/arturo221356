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
                <b-button type="button" block @click="recargarChip"> Recargar</b-button>
                <b-form-group> </b-form-group>
            </b-form>
        </div>
    </div>
</template>

<script>
export default {
    data: function(){
        return{

            dn: null


        }
    },
    methods:{
        recargarChip(){
            axios
                    .post("/activa-chip", { icc: this.icc })
                    .then((response) => {
                        console.log(response.data);

                        if (response.data.success == true) {
                            this.lineaDetail = true;

                            this.currentIcc.icc = response.data.data.icc;

                            this.currentIcc.company =
                                response.data.data.company.name;

                            this.currentIcc.type = response.data.data.type.name;

                            this.icc = null;
                        } else {
                            alert(response.data.message);
                        }
                    })
                   
        }
    }

};
</script>

<style></style>
