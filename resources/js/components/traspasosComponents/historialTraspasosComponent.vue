<template>
    <div v-if="is('super-admin') || can('ver traspasos')">
        <div class="mb-4">
          <detalle-traspaso ref="detalle"></detalle-traspaso>
        </div>
        
        <h1>Historial de traspasos</h1>
        <validation-observer ref="observer" v-slot="{ handleSubmit }">
            <b-form @submit.prevent="handleSubmit(retrieveHistorial)">
                <!-- fecha inicial -->
                <ValidationProvider
                    name="fecha-inicio"
                    v-slot="validationContext"
                    rules="required"
                >
                    <b-form-group label="Fecha inicio:" class="mt-4">
                        <b-form-datepicker
                            id="fecha-inicio"
                            v-model="initialDate"
                            class="mb-2"
                            :max="maxDate"
                            :state="getValidationState(validationContext)"
                        ></b-form-datepicker>
                        <b-form-invalid-feedback>{{
                            validationContext.errors[0]
                        }}</b-form-invalid-feedback>
                    </b-form-group>
                </ValidationProvider>

                <!-- fecha final -->
                <ValidationProvider
                    name="fecha-final"
                    v-slot="validationContext"
                    rules="required"
                >
                    <b-form-group label="Fecha final:" class="mt-4">
                        <b-form-datepicker
                            id="fecha-final"
                            v-model="finalDate"
                            class="mb-2"
                            :max="maxDate"
                            :state="getValidationState(validationContext)"
                        ></b-form-datepicker>
                        <b-form-invalid-feedback>{{
                            validationContext.errors[0]
                        }}</b-form-invalid-feedback>
                    </b-form-group>
                </ValidationProvider>

                <b-form-group>
                    <b-form-radio-group
                        v-model="traspasosAccepted"
                        :options="[
                            {
                                text: 'Aceptados',
                                value: true,
                            },
                            {
                                text: 'Pendientes',
                                value: false,
                            },
                        ]"
                        buttons
                        button-variant="outline-primary"
                        size="lg"
                        name="radio-btn-outline"
                        @input="retrieveHistorial"
                    ></b-form-radio-group>
                </b-form-group>
                <b-button block type="submit" variant="primary"
                    >Buscar</b-button
                >
            </b-form>
        </validation-observer>
        <b-table
            :busy="tableLoading"
            hover
            responsive
            striped
            head-variant="dark"
            table-variant="light"
            :items="historialItems"
            :fields="historialTableFields"
            class="mt-5"
        >
            <template v-slot:cell(detalles)="row">
                <b-button size="sm" @click="$refs.detalle.traspasoLoadDetails(row.item)">
                    Detalles
                </b-button>
            </template>
            <template v-slot:cell(accepted)="row">
                <div v-if="row.item.accepted">
                    {{ row.item.updated_at }}
                </div>
            </template>

            <template v-slot:table-busy>
                <div class="text-center text-primary my-2">
                    <b-spinner class="align-middle"></b-spinner>
                    <strong>Loading...</strong>
                </div>
            </template>
        </b-table>
    </div>
</template>

<script>
import detalleTraspaso from './detalleTraspasoComponent.vue';

export default {
  components:{
    detalleTraspaso,
  },
  data() {
        return {
          traspasosAccepted: true,

            historialTableFields: [
                {
                    key: "id",
                    sortable: true,
                    label: "Folio",
                },
                {
                    key: "inventario_name",
                    sortable: true,
                    label: "Destino",
                },
                {
                    key: "created_at",
                    sortable: true,
                    label: "Fecha de Creacion",
                },
                {
                    key: "accepted",
                    sortable: true,
                    label: "Aceptado",
                },
                {
                    key: "user_name",
                    sortable: true,
                    label: "Aceptado por",
                },
                {
                    key: "detalles",
                    label: "Detalles",
                },
            ],
            historialItems: [],

            tableLoading: false,
            finalDate: new Date().toISOString().substr(0, 10),

            initialDate: new Date().toISOString().substr(0, 10),
        };
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },


        retrieveHistorial() {
            this.tableLoading = true;
            axios
                .get("/inventario/traspasos", {
                    params: {
                        initial_date: this.initialDate,
                        final_date: this.finalDate,
                        accepted: this.traspasosAccepted,
                    },
                })
                .then((response) => {
                    this.historialItems = response.data.data;

                    this.tableLoading = false;
                })
                .catch((error) => {
                    alert(error);
                    this.tableLoading = false;
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
