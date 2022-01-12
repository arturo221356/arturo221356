<template>
    <!-- detalle de traspaso-->
    <b-overlay :show="isLoading" rounded="sm" >

           
            <div v-show="showDetails == true">
                <div class="row">
                    <div class="col-sm">
                        <h1 class="float-right" :style="{ cursor: 'pointer' }">
                            <b-icon
                                icon="x-circle-fill"
                                variant="danger"
                                @click="showDetails = false"
                            ></b-icon>
                        </h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                        <h3>
                            <b>Traspaso folio:</b>
                            {{ detailTraspaso.id }}
                        </h3>
                    </div>
                    <div class="col-sm">
                        <h5>
                            <b>Destino:</b>
                            {{ detailTraspaso.destino_name }}
                        </h5>
                    </div>

                    <div class="col-sm">
                        <h5>
                            <b>Fecha:</b>
                            {{ detailTraspaso.created_at }}
                        </h5>
                    </div>

                    <div class="col-sm" v-if="detailTraspaso.accepted == true">
                        <h5>
                            <b>Aceptado:</b>
                            {{ detailTraspaso.updated_at }}
                        </h5>
                    </div>

                    <div class="col-sm" v-if="detailTraspaso.accepted == true">
                        <h5>
                            <b>Aceptado por:</b>
                            {{ detailTraspaso.user_name }}
                        </h5>
                    </div>
                    <div
                        class="col-sm"
                        v-if="
                            detailTraspaso.iccs &&
                            detailTraspaso.iccs.length > 0
                        "
                    >
                        <h5>
                            <b>SIMs:</b>
                            {{ detailTraspaso.iccs.length }}
                        </h5>
                    </div>
                    <div
                        class="col-sm"
                        v-if="
                            detailTraspaso.imeis &&
                            detailTraspaso.imeis.length > 0
                        "
                    >
                        <h5>
                            <b>Equipos:</b>
                            {{ detailTraspaso.imeis.length }}
                        </h5>
                    </div>
                </div>
                <div v-if="detailTraspaso.series">
                    <h5>Cantidad: {{ detailTraspaso.series.length }}</h5>
                </div>
                <div class="row mt-2" v-if="detailTraspaso.accepted == false">
                    <div class="col-sm">
                        <b-form-group class="mt-2">
                            <b-button
                                variant="danger"
                                @click="cancelarTraspaso(detailTraspaso.id)"
                                v-if="
                                    is('super-admin') ||
                                    can('cancelar traspaso')
                                "
                                block
                            >
                                Cancelar Traspaso
                            </b-button>
                        </b-form-group>
                        <b-form-group class="mt-2">
                            <b-button
                                variant="success"
                                @click="aceptarTraspaso(detailTraspaso.id)"
                                v-if="
                                    is('super-admin') || can('aceptar traspaso')
                                "
                                block
                            >
                                Aceptar Traspaso
                            </b-button>
                        </b-form-group>
                    </div>
                </div>

                <!-- lista de chips -->
                <b-list-group class="mt-5">
                    <b-list-group-item
                        v-for="(item, index) in detailTraspaso.series"
                        :key="index"
                    >
                        <div class="row">
                            <div class="col-sm" v-if="item.imei">
                                <h5>{{ item.imei }}</h5>
                            </div>
                            <div class="col-sm float-left" v-if="item.imei">
                                <B>Equipo: </B>
                                {{ item.equipo.marca }}
                                {{ item.equipo.modelo }}
                            </div>

                            <div class="col-sm" v-if="item.icc">
                                <h5>{{ item.icc }}</h5>
                            </div>
                            <div class="col-sm float-left" v-if="item.icc">
                                <B>Compañia: </B>
                                {{ item.company.name }}
                                {{ item.type.name }}
                            </div>

                            <div class="col-sm float-left">
                                <B>Origen:</B>
                                {{ item.pivot.old_inventario_name }}
                            </div>
                        </div>
                    </b-list-group-item>
                </b-list-group>
            </div>
       
    </b-overlay>
</template>

<script>
export default {
    data() {
        return {
            showDetails: false,
            detailTraspaso: {},
            isLoading: false,
        };
    },
    methods: {
        traspasoLoadDetails(item) {
            this.isLoading = true;
            this.showDetails = true;
            axios
                .get(`/inventario/traspasos/${item.id}`)
                .then((response) => {
                    this.detailTraspaso = response.data.data;
                    
                    this.isLoading = false;
                })
                .catch((error) => {
                    alert(error);
                    this.isLoading = false;
                });
        },
    },
};
</script>

<style></style>
