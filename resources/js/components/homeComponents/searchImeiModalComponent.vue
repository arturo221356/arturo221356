<template>
    <div>
        <b-modal
            id="imei-modal"
            :title="`Imei: ${editableItem.imei}`"
            size="lg"
        >
            <validation-observer ref="observer" v-slot="{ handleSubmit }">
                <b-form @submit.prevent="handleSubmit(updateItem)">
                    <ValidationProvider
                        name="equipo"
                        v-slot="validationContext"
                        rules="required"
                    >
                        <b-form-group label="Equipo:" label-size="lg">
                            <select-general
                                url="/get/equipos"
                                pholder="Seleccionar Equipo"
                                v-model="editableItem.equipo"
                                :state="getValidationState(validationContext)"
                                :disabled="true"
                                :equipo="true"
                            >
                            </select-general>
                            <b-form-invalid-feedback>{{
                                validationContext.errors[0]
                            }}</b-form-invalid-feedback>
                        </b-form-group>
                    </ValidationProvider>
                    <ValidationProvider
                        name="inventario"
                        v-slot="validationContext"
                        rules="required"
                    >
                        <b-form-group label="Inventario" label-size="lg">
                            <select-general
                                url="/inventario"
                                pholder="Seleccionar Inventario"
                                query="App\Sucursal"
                                v-model="editableItem.inventario"
                                :disabled="true"
                                :state="getValidationState(validationContext)"
                            >
                            </select-general>
                            <b-form-invalid-feedback>{{
                                validationContext.errors[0]
                            }}</b-form-invalid-feedback>
                        </b-form-group>
                    </ValidationProvider>
                    <ValidationProvider
                        name="estatus"
                        v-slot="validationContext"
                        rules="required"
                    >
                        <b-form-group label="Estatus:" label-size="lg">
                            <select-statuses
                                estatusable="inventario"
                                v-model="editableItem.status"
                                :disabled="true"
                                :state="getValidationState(validationContext)"
                            ></select-statuses>
                            <b-form-invalid-feedback>{{
                                validationContext.errors[0]
                            }}</b-form-invalid-feedback>
                        </b-form-group>
                    </ValidationProvider>
                    <b-form-group label="Comentario" label-size="lg"
                        ><b-form-textarea
                            :disabled="true"
                            v-model="editableItem.comment"
                            max-rows="6"
                            placeholder="Comentario"
                        ></b-form-textarea
                    ></b-form-group>
                </b-form>
                <b-form-group
                    label="Comision Equipo en RED"
                    label-size="sm"
                    v-if="
                        editableItem.comisionCer > 0 &&
                        is('super-admin|administrador')
                    "
                >
                    <b-form-input
                        type="number"
                        readonly
                        :value="editableItem.comisionCer"
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    label="Historial de Traspasos"
                    label-size="lg"
                    v-if="editableItem.traspasos.length > 0"
                >
                    <b-list-group>
                        <b-list-group-item
                            v-for="traspaso in editableItem.traspasos"
                            :key="traspaso.id"
                            ><B>Origen:</B> {{ traspaso.origen }}
                            <B>Destino:</B> {{ traspaso.destino }}
                            <B>Fecha:</B>
                            {{ traspaso.created_at }}</b-list-group-item
                        >
                    </b-list-group>
                </b-form-group>
                <b-form-group
                    label="Historial de Ventas"
                    label-size="lg"
                    v-if="editableItem.ventas.length > 0"
                >
                    <b-list-group>
                        <b-list-group-item
                            v-for="venta in editableItem.ventas"
                            :key="venta.id"
                            ><B>Folio:</B> {{ venta.id }} <B>Inventario:</B>
                            {{ venta.inventario }} <B>Usuario:</B>
                            {{ venta.usuario }} <B>Precio vendido</B>${{
                                venta.precio_vendido
                            }}
                            <B>Fecha:</B> {{ venta.created_at }}
                        </b-list-group-item>
                    </b-list-group>
                </b-form-group>
            </validation-observer>
        </b-modal>
    </div>
</template>

<script>
export default {
    data() {
        return {
            editableItem: {
                id: null,

                imei: null,

                comisionCer: null,

                inventario: null,

                equipo: { marca: null, modelo: null, id: null },

                status: null,

                traspasos: [],

                ventas: [],

                comment: null,
            },
        };
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        load(item) {
            console.log(item);

            this.editableItem.id = item.id;

            this.editableItem.imei = item.serie;

            this.editableItem.comment = item.comment;

            this.editableItem.equipo = item.equipo;

            this.editableItem.traspasos = item.traspasos;

            this.editableItem.ventas = item.ventas;

            this.editableItem.comisionCer = item.comision_cer;

            this.editableItem.status = {
                id: item.status,
                name: item.status,
            };
            this.editableItem.inventario = {
                id: item.inventario_id,
                name: item.inventario_name,
            };
            this.$bvModal.show("imei-modal");
        },
        handleSubmit(updateItem) {
            alert(updateItem);
        },
    },
};
</script>

<style></style>
