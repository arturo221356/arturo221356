<template>
    <div>
        <b-navbar
            toggleable="lg"
            type="light"
            style="background-color: #dedede;"
        >
            <b-navbar-brand href="#">Traspaso</b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav>
                    <b-nav-form>
                        <b-form-radio-group
                            v-model.lazy="traspasoType"
                            :options="traspasoOptions"
                            buttons
                            v-if="is('super-admin') || can('traspasar stock')"
                        >
                        </b-form-radio-group>
                    </b-nav-form>
                </b-navbar-nav>

                <!-- Right aligned nav items -->
                <b-navbar-nav class="ml-auto"> </b-navbar-nav>
                <div>
                    a la derecha
                </div>
            </b-collapse>
        </b-navbar>
        <div class="jumbotron jumbotron-fluid">
            <div class="col-md-8 mx-auto">
                <div v-if="traspasoType == 'nuevo'">
                    <nuevo-traspaso></nuevo-traspaso>
                </div>
                <div v-else-if="traspasoType == 'historial'">
                     <historial-traspasos></historial-traspasos> 
                </div>
                
               
            </div>
        </div>
    </div>
</template>

<script>
import NuevoTraspaso from "./traspasosComponents/nuevoTraspasoComponent.vue";
import HistorialTraspasos from "./traspasosComponents/historialTraspasosComponent.vue";

export default {
    components: {
        NuevoTraspaso,
        HistorialTraspasos,
        
    },

    data() {
        return {
            traspasoType: "historial",
            traspasoOptions: [
                { text: "Nuevo traspaso", value: "nuevo" },
                { text: "Historial", value: "historial" },
            ],
        };
    },
};
</script>

<style></style>
