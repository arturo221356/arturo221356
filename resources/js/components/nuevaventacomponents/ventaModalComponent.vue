<template>
    <div>
        <!-- modal de venta -->
        <b-modal id="venta-modal" @hide ="closeModal" hide-footer>
            <validation-observer ref="venta" v-slot="{ handleSubmit }">
                <b-form @submit.prevent="handleSubmit(newVenta)">
                    <ValidationProvider
                        name="nombre cliente"
                        v-slot="validationContext"
                    >
                        <b-form-group label="Nombre Cliente" label-size="lg">
                            <b-input
                                placeholder="Insertar nombre del cliente"
                                v-model="cliente.nombre"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            ></b-input>
                            <b-form-invalid-feedback>{{
                                validationContext.errors[0]
                            }}</b-form-invalid-feedback>
                        </b-form-group>
                    </ValidationProvider>
                    <ValidationProvider
                        name="curp"
                        v-slot="validationContext"
                        rules=""
                    >
                        <b-form-group label="Curp" label-size="lg">
                            <b-input
                                v-model="cliente.curp"
                                placeholder="Insertar Curp del cliente"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            ></b-input>
                            <b-form-invalid-feedback>{{
                                validationContext.errors[0]
                            }}</b-form-invalid-feedback>
                        </b-form-group>
                    </ValidationProvider>
                    <ValidationProvider
                        name="rfc"
                        v-slot="validationContext"
                        rules=""
                    >
                        <b-form-group label="RFC" label-size="lg">
                            <b-input
                                placeholder="Insertar RFC del cliente"
                                v-model="cliente.rfc"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            ></b-input>
                            <b-form-invalid-feedback>{{
                                validationContext.errors[0]
                            }}</b-form-invalid-feedback>
                        </b-form-group>
                    </ValidationProvider>

                    <ValidationProvider
                        name="referencia"
                        v-slot="validationContext"
                        :rules="`digits:10`"
                    >
                        <b-form-group label="Referencia" label-size="lg">
                            <b-input
                                v-model="cliente.referencia"
                                placeholder="Insertar Referencia del cliente"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            ></b-input>
                            <b-form-invalid-feedback>{{
                                validationContext.errors[0]
                            }}</b-form-invalid-feedback>
                        </b-form-group>
                    </ValidationProvider>

                    <ValidationProvider
                        name="email"
                        v-slot="validationContext"
                        :rules="`email`"
                    >
                        <b-form-group label="Email" label-size="lg">
                            <b-input
                                v-model="cliente.email"
                                placeholder="Insertar Email del cliente"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            ></b-input>
                            <b-form-invalid-feedback>{{
                                validationContext.errors[0]
                            }}</b-form-invalid-feedback>
                        </b-form-group>
                    </ValidationProvider>

                    <b-form-group label="Comentario" label-size="lg">
                        <b-input
                            v-model="comentario"
                            placeholder="Comentario venta"
                            autocomplete="off"
                        ></b-input>
                    </b-form-group>

                    <b-form-group label="Total">
                        <b-input
                            type="number"
                            placeholder="Pago"
                            :value="totalVenta"
                            readonly
                        ></b-input>
                    </b-form-group>
                    <b-form-group
                        label="Pago:"
                        label-size="lg"
                        description="Ingresa el pago del cliente para calcular el cambio"
                    >
                        <b-input
                            type="number"
                            placeholder="Pago"
                            v-model.number="pago"
                            autocomplete="off"
                        ></b-input>
                    </b-form-group>

                    <b-form-group label="Cambio" label-size="lg">
                        <b-input
                            type="number"
                            placeholder="Pago"
                            :value="pago - totalVenta"
                            readonly
                        ></b-input>
                    </b-form-group>

                    <b-button variant="success" block type="submit"
                        >Vender</b-button
                    >
                    <b-button block @click="close">Cancelar</b-button>
                </b-form>
            </validation-observer>
        </b-modal>
    </div>
</template>

<script>
export default {
    props: ["total-venta"],
    data() {
        return {
            pago: 0,
            comentario: null,
            cliente: {
                nombre: null,
                referencia: null,
                email: null,
                curp: null,
                rfc: null,
            },
        };
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        open() {
            this.pago = this.totalVenta;
            this.$root.$emit("bv::show::modal", "venta-modal");
        },
        close() {
            this.$root.$emit("bv::hide::modal", "venta-modal");
        },
        closeModal(){
            this.cliente =  {
                nombre: null,
                referencia: null,
                email: null,
                curp: null,
                rfc: null,
            };

            this.comentario = null;

            this.pago = 0;
        },
        newVenta() {
            const data = {
                comentario: this.comentario,
                cliente: this.cliente,
            };
            this.$emit("new-venta", data);
        },
    },
};
</script>

<style></style>
