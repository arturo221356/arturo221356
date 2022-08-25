<template>
    <div class="jumbotron">
        <div class="col-md-8 mx-auto">
            <div>
                <h1>Portabilidad usuarios externos</h1>
                <b-card no-body class="mt-3">
                    <b-tabs card>
                        <b-tab title="Portabilidad Telcel" active>
                            <div v-if="telcelPortaWorking == true">
                                <porta-telcel-externos-component></porta-telcel-externos-component>
                            </div>
                            <div v-else>
                                Temporalmente deshabilitado
                            </div>
                        </b-tab>
                        <b-tab title="Portabilidad Movistar" disabled
                            ><p>I'm a disabled tab!</p></b-tab
                        >
                        <b-tab title="Registrar Portabilidad"
                            ><registro-portabilidades-component></registro-portabilidades-component
                        ></b-tab>
                    </b-tabs>
                </b-card>
            </div>
        </div>
    </div>
</template>

<script>
import PortaTelcelExternosComponent from "./portaExternos/PortaTelcelExternosComponent.vue";
import RegistroPortabilidadesComponent from "./portaExternos/RegistroPortabilidadesComponent.vue";

export default {
    components: {
        PortaTelcelExternosComponent,
        RegistroPortabilidadesComponent,
    },

    data() {
        return {
            telcelPortaWorking: true,
        };
    },
    created(){
        axios.post("/telcel-porta-working").then(response => {
            this.telcelPortaWorking = response.data;
            console.log(response.data);
        }).catch(error =>{
            this.telcelPortaWorking = false;
        })
    }
};
</script>

<style></style>
