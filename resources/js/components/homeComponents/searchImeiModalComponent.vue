<template>
    <div>
        <b-modal id="imei-modal" :title="`Imei: ${item.imei}`">
            <validation-observer ref="observer" v-slot="{ handleSubmit }">
                <b-form @submit.prevent="handleSubmit(updateItem)">
                    <ValidationProvider
                            name="equipo"
                            v-slot="validationContext"
                            rules="required"
                            v-if="can('full update stock')"
                        >
                            <b-form-group label="Equipo:" label-size="lg">
                                <select-general
                                    url="/get/equipos"
                                    pholder="Seleccionar Equipo"
                                    v-model="item.equipo"
                                    :state="
                                        getValidationState(validationContext)
                                    "
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
                        v-if="can('full update stock')"
                    >
                        <b-form-group label="Inventario" label-size="lg">
                            <select-general
                                url="/inventario"
                                pholder="Seleccionar Inventario"
                                query="App\Sucursal"
                                v-model="inventario"
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
                                v-model="status"
                                :state="getValidationState(validationContext)"
                            ></select-statuses>
                            <b-form-invalid-feedback>{{
                                validationContext.errors[0]
                            }}</b-form-invalid-feedback>
                        </b-form-group>
                    </ValidationProvider>
                    <b-form-group label="Comentario" label-size="lg"
                            ><b-form-textarea
                                v-model="item.comment"
                                max-rows="6"
                                placeholder="Comentario"
                            ></b-form-textarea
                        ></b-form-group>
                </b-form>
            </validation-observer>
        </b-modal>
    </div>
</template>

<script>
export default {
    data() {
        return {
            item: {
                id: null,
                imei: null,
                comment: null,
                created_at: null,
                deleted_at: null,
                equipo_id: null,
                status: null,
                updated_at: null,
                inventario_id: null,
                equipo: {
                    id: null,
                    costo: null,
                    precio: null,
                    marca: null,
                    modelo: null,
                },
                inventario: {
                    id: null,
                    inventarioable: {
                        name: null,
                        id: null,
                    },
                },
                traspasos: [],
                venta: [],
            },
            inventario: {id: null, name: null},
            status: {id: null, name: null,},
        };
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        load(data) {
            this.$bvModal.show("imei-modal");
            this.item = { ...data };
            this.inventario = {
                id: data.inventario_id,
                name: data.inventario.inventarioable.name
            };

            this.status = {id: data.status, name: data.status};
            console.log(data);
        },
        handleSubmit(updateItem) {
            alert(updateItem);
        },
    },
};
</script>

<style></style>
