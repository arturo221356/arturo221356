<template>
    <div>
        <b-overlay :show="isLoading" rounded="sm">
            <h3>Portabilidad Telcel</h3>
            <b-progress :value="step" :max="5" :animated="true"></b-progress>
            <div class="mt-3 mb-3 text-right" v-if="step > 1">
                <b-button variant="secondary" @click="resetFields"
                    >Limpiar</b-button
                >
                <b-button variant="danger" @click="deletePorta"
                    >Eliminar</b-button
                >
            </div>
            <div>
                <validation-observer ref="general" v-slot="{ handleSubmit }">
                    <b-form @submit.prevent="handleSubmit(checkNumber)" id="Form1">
                        <ValidationProvider
                            name="numero telefono"
                            v-slot="validationContext"
                            rules="required|digits:10"
                        >
                            <b-form-group
                                id="numero-cel"
                                description=""
                                label="Ingresa el numero de telefono a portar:"
                                label-for="numero-cel"
                                valid-feedback="Thank you!"
                                :state="getValidationState(validationContext)"
                                label-size="lg"
                                class="mt-3"
                            >
                                <b-input-group>
                                    <b-form-input
                                        id="numero-cel"
                                        autocomplete="off"
                                        v-model="numero"
                                        type="number"
                                        :state="
                                            getValidationState(
                                                validationContext
                                            )
                                        "
                                        trim
                                        :disabled="step != 1"
                                    ></b-form-input>
                                    <b-form-invalid-feedback>{{
                                        validationContext.errors[0]
                                    }}</b-form-invalid-feedback>
                                    <b-input-group-append v-if="step == 1">
                                        <b-button
                                            type="summit"
                                            variant="success"
                                            form="Form1"
                                            >Avanzar</b-button
                                        >
                                    </b-input-group-append>
                                </b-input-group>
                            </b-form-group>
                        </ValidationProvider>
                    </b-form>

                    <div v-if="step > 1">
                        <b-form
                            @submit.prevent="handleSubmit(checkPromo)"
                            id="Form2"
                        >
                            <ValidationProvider
                                name="Nombre"
                                v-slot="validationContext"
                                rules="required|alpha_spaces|min:3"
                            >
                                <b-form-group
                                    id="nombre"
                                    description=""
                                    label="Nombre del cliente:"
                                    label-for="nombre"
                                    valid-feedback="Thank you!"
                                    :state="
                                        getValidationState(validationContext)
                                    "
                                    label-size="lg"
                                    class="mt-3"
                                >
                                    <b-input-group>
                                        <b-form-input
                                            id="nombre"
                                            autocomplete="off"
                                            style="text-transform: uppercase;"
                                            v-model="nombre"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                            trim
                                            :disabled="step > 2"
                                        ></b-form-input>
                                        <b-form-invalid-feedback>{{
                                            validationContext.errors[0]
                                        }}</b-form-invalid-feedback>
                                    </b-input-group>
                                </b-form-group>
                            </ValidationProvider>
                            <ValidationProvider
                                name="Apellido paterno"
                                v-slot="validationContext"
                                rules="required|alpha_spaces|min:3"
                            >
                                <b-form-group
                                    id="apellido paterno"
                                    description=""
                                    label="Apellido paterno:"
                                    label-for="apaterno"
                                    valid-feedback="Thank you!"
                                    :state="
                                        getValidationState(validationContext)
                                    "
                                    label-size="lg"
                                    class="mt-3"
                                >
                                    <b-input-group>
                                        <b-form-input
                                            id="apaterno"
                                            style="text-transform: uppercase;"
                                            autocomplete="off"
                                            v-model="apaterno"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                            :disabled="step > 2"
                                            trim
                                        ></b-form-input>
                                        <b-form-invalid-feedback>{{
                                            validationContext.errors[0]
                                        }}</b-form-invalid-feedback>
                                    </b-input-group>
                                </b-form-group>
                            </ValidationProvider>
                            <ValidationProvider
                                name="Apellido materno"
                                v-slot="validationContext"
                                rules="required|alpha_spaces|min:3"
                            >
                                <b-form-group
                                    id="apellido materno"
                                    description=""
                                    label="Apellido materno:"
                                    label-for="amaterno"
                                    valid-feedback="Thank you!"
                                    :state="
                                        getValidationState(validationContext)
                                    "
                                    label-size="lg"
                                    class="mt-3"
                                >
                                    <b-input-group>
                                        <b-form-input
                                            id="amaterno"
                                            autocomplete="off"
                                            v-model="amaterno"
                                            style="text-transform: uppercase;"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                            :disabled="step > 2"
                                            trim
                                        ></b-form-input>
                                        <b-form-invalid-feedback>{{
                                            validationContext.errors[0]
                                        }}</b-form-invalid-feedback>
                                    </b-input-group>
                                </b-form-group>
                            </ValidationProvider>
                            <ValidationProvider
                                name="curp"
                                v-slot="validationContext"
                                rules="alpha_num|curp"
                            >
                                <b-form-group
                                    id="curp"
                                    description=""
                                    label="CURP:"
                                    label-for="curp"
                                    valid-feedback="Thank you!"
                                    :state="
                                        getValidationState(validationContext)
                                    "
                                    :disabled="step > 2"
                                    label-size="lg"
                                    class="mt-3"
                                >
                                    <b-input-group>
                                        <b-form-input
                                            id="curp"
                                            autocomplete="off"
                                            v-model="curp"
                                            style="text-transform: uppercase;"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                            :disabled="step > 2"
                                            trim
                                        ></b-form-input>
                                        <b-form-invalid-feedback>{{
                                            validationContext.errors[0]
                                        }}</b-form-invalid-feedback>
                                    </b-input-group>
                                </b-form-group>
                            </ValidationProvider>

                            <b-button
                                block
                                variant="success"
                                form="Form2"
                                v-if="step == 2"
                                type="summit"
                                :disabled="error"
                                >Avanzar 2</b-button
                            >
                        </b-form>

                        <div v-if="step > 2">
                            <b-form @submit.prevent="handleSubmit(confirmPorta)" id="Form3">
                                <ValidationProvider
                                    name="promocion"
                                    v-slot="validationContext"
                                    rules="required"
                                >
                                    <b-form-group
                                        id="promocion"
                                        description=""
                                        label="Promocion:"
                                        label-for="promocion"
                                        valid-feedback="Thank you!"
                                        :state="
                                            getValidationState(
                                                validationContext
                                            )
                                        "
                                        label-size="lg"
                                        class="mt-3"
                                    >
                                        <b-form-select v-model="promo">
                                            <template #first>
                                                <b-form-select-option
                                                    :value="null"
                                                    disabled
                                                    >-- Selecciona Promocion
                                                    --</b-form-select-option
                                                >
                                            </template>

                                            <b-form-select-option
                                                :value="promo.id"
                                                v-for="promo in promoOptions"
                                                :key="promo.id"
                                                >{{
                                                    promo.descripcion
                                                }}</b-form-select-option
                                            >
                                        </b-form-select>
                                    </b-form-group>
                                </ValidationProvider>

                                <ValidationProvider
                                    name="icc"
                                    v-slot="validationContext"
                                    rules="required|Icc|Luhn"
                                >
                                    <b-form-group
                                        id="icc"
                                        description=""
                                        label="ICC:"
                                        label-for="icc"
                                        valid-feedback="Thank you!"
                                        :state="
                                            getValidationState(
                                                validationContext
                                            )
                                        "
                                        label-size="lg"
                                        class="mt-3"
                                    >
                                        <b-input-group>
                                            <b-form-input
                                                id="icc"
                                                autocomplete="off"
                                                v-model="icc"
                                                :state="
                                                    getValidationState(
                                                        validationContext
                                                    )
                                                "
                                                trim
                                            ></b-form-input>
                                            <b-form-invalid-feedback>{{
                                                validationContext.errors[0]
                                            }}</b-form-invalid-feedback>
                                        </b-input-group>
                                    </b-form-group>
                                </ValidationProvider>
                                <ValidationProvider
                                    name="nip"
                                    v-slot="validationContext"
                                    rules="required|digits:4"
                                >
                                    <b-form-group
                                        id="nip"
                                        description=""
                                        label="NIP:"
                                        label-for="nip"
                                        valid-feedback="Thank you!"
                                        :state="
                                            getValidationState(
                                                validationContext
                                            )
                                        "
                                        label-size="lg"
                                        class="mt-3"
                                    >
                                        <b-input-group>
                                            <b-form-input
                                                id="nip"
                                                autocomplete="off"
                                                v-model="nip"
                                                :state="
                                                    getValidationState(
                                                        validationContext
                                                    )
                                                "
                                                trim
                                            ></b-form-input>
                                            <b-form-invalid-feedback>{{
                                                validationContext.errors[0]
                                            }}</b-form-invalid-feedback>
                                        </b-input-group>
                                    </b-form-group>
                                </ValidationProvider>
                                <b-button
                                    block
                                    type="summit"
                                    form="Form3"
                                    variant="success"
                                    v-if="step == 3"
                                    :disabled="error"
                                    >Avanzar</b-button
                                >
                            </b-form>
                        </div>
                    </div>
                </validation-observer>
            </div>
        </b-overlay>
    </div>
</template>

<script>
export default {
    data() {
        return {
            step: 1,
            portaId: null,
            numero: null,
            isLoading: false,
            nombre: null,
            apaterno: null,
            amaterno: null,
            curp: null,
            nip: null,
            icc: null,
            promo: null,
            error: false,
            idcop: null,
            promoOptions: [],
        };
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        checkNumber() {
            this.isLoading = true;
            const params = {
                numero: this.numero,
            };
            axios
                .post("/telcel/porta/check-number", params)

                .then((response) => {
                    if (response.data.finnished == true) {
                        this.$bvToast.toast(
                            "Portabilidad subida anteriormente",
                            {
                                title: "Error",
                                variant: "warning",
                                solid: true,
                            }
                        );
                    } else {
                        this.portaId = response.data.id ?? null
                        this.nombre = response.data.nombre ?? null;
                        this.apaterno = response.data.apaterno ?? null;
                        this.amaterno = response.data.amaterno ?? null;
                        this.curp = response.data.curp ?? null;
                        this.idcop = response.data.idcop ?? null;
                        this.promoOptions = response.data.promociones ?? null;
                        if (response.data.error == true) {
                            this.$bvToast.toast(response.data.message, {
                                title: "Error",
                                variant: "danger",
                                solid: true,
                            });
                            this.step = 2;
                            this.error = true;
                        } else {
                            response.data.curp
                                ? (this.step = 3)
                                : (this.step = 2);
                        }
                    }
                })
                .catch(function (error) {
                    alert(error);
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
        deletePorta() {
            this.isLoading = true;
            axios
                .delete(`/telcel/porta/${this.portaId}`, {
                    
                })
                .then((response) => {
                    console.log(response);
                    this.$bvToast.toast("Datos de portabilidad eliminados", {
                        title: "portabilidad eliminada",
                        variant: "danger",
                        solid: true,
                    });
                })
                .catch((err) => alert(err.message))
                .finally(() => {
                    this.isLoading = false;
                    this.resetFields();
                });
        },
        checkPromo() {
            this.isLoading = true;
            const params = {
                numero: this.numero,
                nombre: this.nombre,
                apaterno: this.apaterno,
                amaterno: this.amaterno,
                curp: this.curp,
            };
            axios
                .post("/telcel/porta/check-promo", params)

                .then((response) => {
                    if (response.data.respuesta.error == true) {
                        this.$bvToast.toast(response.data.respuesta.mensaje, {
                            title: "Error",
                            variant: "danger",
                            solid: true,
                        });
                    } else {
                        this.nombre = response.data.datosPorta.nombre ?? null;
                        this.apaterno =
                            response.data.datosPorta.apaterno ?? null;
                        this.amaterno =
                            response.data.datosPorta.amaterno ?? null;
                        this.curp = response.data.datosPorta.curp ?? null;
                        this.promoOptions =
                            response.data.datosPorta.promociones ?? null;
                        this.idcop = response.data.datosPorta.idcop ?? null;
                        this.step = 3;
                    }
                })
                .catch(function (error) {
                    alert(error);
                })
                .finally(() => {
                    this.isLoading = false;
                    
                });
        },

        confirmPorta() {
            this.isLoading = true;
            const params = {
                icc: this.icc,
                promo: this.promo,
                idcop: this.idcop,
                nip: this.nip,
            };

            axios
                .post("/telcel/porta/check-icc", params)

                .then((response) => {
                    console.log(response);

                    if (response.data.success == false) {
                        this.$bvToast.toast(response.data.message, {
                            title: "Error",
                            variant: "danger",
                            solid: true,
                        });
                    } else {
                        axios
                            .post("/telcel/porta/confirm-porta", params)

                            .then((response) => {
                                if (response.data.respuesta.error == true) {
                                    this.$bvToast.toast(
                                        response.data.respuesta.mensaje,
                                        {
                                            title: "Error",
                                            variant: "danger",
                                            solid: true,
                                        }
                                    );
                                    this.error = true;
                                } else {
                                    this.$bvToast.toast(
                                        `Portabilidad ${response.data.datosPorta.dn} subida con exito`,
                                        {
                                            title: "Portabilidad Exitosa",
                                            variant: "success",
                                            solid: true,
                                        }
                                    );

                                    this.resetFields();
                                }
                            })
                            .catch(function (error) {
                                alert(error);
                            })
                            .finally(() => {
                                this.isLoading = false;
                            });
                    }
                })
                .catch(function (error) {
                    console.log(error);
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },

        resetFields() {
            this.step = 1;
            this.portaId = null,
            this.numero = null;
            this.isLoading = false;
            this.nombre = null;
            this.apaterno = null;
            this.amaterno = null;
            this.curp = null;
            this.nip = null;
            this.icc = null;
            this.promo = null;
            this.error = false;
            this.idcop = null;
            this.promoOptions = [];
        },
    },
};
</script>

<style></style>
