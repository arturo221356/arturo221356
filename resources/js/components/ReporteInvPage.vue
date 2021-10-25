<template>
    <div>
        <b-overlay :show="isLoading" rounded="sm">
            <div class="jumbotron">
                <div class="col-md-8 mx-auto">
                    <h1>Reporte de Inventario</h1>
                    <validation-observer
                        ref="observer"
                        v-slot="{ handleSubmit }"
                    >
                        <b-form @submit.prevent="handleSubmit(loadInventario)">
                            <b-form-group>
                                <b-form-radio-group
                                    class="align-middle"
                                    id="radio-producto"
                                    v-model="producto"
                                    :options="productoOptions"
                                    buttons
                                    name="radio-producto"
                                    @change="tableItems = []"
                                ></b-form-radio-group>
                            </b-form-group>
                            <ValidationProvider
                                v-slot="validationContext"
                                rules="required"
                            >
                                <b-form-group
                                    label="Inventario:"
                                    label-size="lg"
                                >
                                    <select-general
                                        url="/inventario"
                                        pholder="Seleccionar Inventario"
                                        v-model="inventario"
                                        :empty="true"
                                        :todas="
                                            can('get inventarios')
                                                ? true
                                                : false
                                        "
                                        :state="
                                            getValidationState(
                                                validationContext
                                            )
                                        "
                                    >
                                    </select-general>
                                    <b-form-invalid-feedback
                                        ><b-form-invalid-feedback>{{
                                            validationContext.errors[0]
                                        }}</b-form-invalid-feedback></b-form-invalid-feedback
                                    >
                                </b-form-group>
                            </ValidationProvider>
                            <b-form-group
                                v-if="can('update stock')"
                                label="Eliminados"
                                label-size="lg"
                            >
                                <b-form-radio-group
                                    buttons
                                    v-model="onlyTrash"
                                    button-variant="outline-danger"
                                    :options="[
                                        { text: 'Si', value: true },
                                        { text: 'No', value: false },
                                    ]"
                                >
                                </b-form-radio-group>
                            </b-form-group>
                            <b-button block variant="primary" type="submit"
                                >Cargar</b-button
                            >
                        </b-form>
                    </validation-observer>
                    <div class="row">
                        <div class="col-sm-6">

                        </div>
                        <div class="col-sm-6 mt-2 float-right">
                            <b-form-group label="Filtrar:" label-size="sm"
                                ><b-input
                                    placeholder="Filtrar"
                                    type="search"
                                    v-model="tableFilter"
                                ></b-input>
                            </b-form-group>
                        </div>
                    </div>
                </div>
                <div v-if="producto == 'iccReporte'">
                    <icc-reporte
                        ref="iccReporte"
                        :onlyTrash="onlyTrash"
                        :inventario_id="inventario_id"
                        :tableFilter="tableFilter"
                    ></icc-reporte>
                </div>
                <div v-else-if="producto == 'imeiReporte'">
                    <imei-reporte
                        ref="imeiReporte"
                        :onlyTrash="onlyTrash"
                        :inventario_id="inventario_id"
                        :tableFilter="tableFilter"
                    ></imei-reporte>
                </div>
                <div v-else-if="producto == 'accesorioReporte'">
                    <accesorio-reporte
                        ref="accesorioReporte"
                        :onlyTrash="onlyTrash"
                        :inventario_id="inventario_id"
                        :tableFilter="tableFilter"
                    ></accesorio-reporte>
                </div>
            
            </div>
        </b-overlay>
    </div>
</template>

<script>
import IccReporte from "./reporteinvcomponents/IccReporteComponent.vue";
import ImeiReporte from "./reporteinvcomponents/ImeiReporteComponent.vue";
import AccesorioReporte from "./reporteinvcomponents/accesorioReporte.vue";

export default {
    components: {
        IccReporte,
        ImeiReporte,
        AccesorioReporte,
    },
    data() {
        return {
            isLoading: false,

            editMode: false,

            onlyTrash: false,

            producto: "iccReporte",

            tableFilter: null,

            productoOptions: [
                { text: "Sims", value: "iccReporte" },
                { text: "Equipos", value: "imeiReporte" },
                { text: "Accesorios", value: "accesorioReporte" },
            ],
            inventario: {},
        };
    },

    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        loadInventario() {
            this.$refs[this.producto].loadData();
        },
    },
    computed: {
        inventario_id: function () {
            if (this.inventario != null) {
                return this.inventario.id;
            } else {
                return null;
            }
        },
    },
};
</script>

<style></style>
