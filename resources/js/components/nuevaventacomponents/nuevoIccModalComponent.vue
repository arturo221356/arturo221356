<template>
    <div>
        <b-modal id="icc-modal" hide-footer @hide="closeIccModal">
            <validation-observer ref="general" v-slot="{ handleSubmit }">
                <b-form @submit.prevent="handleSubmit(buildIcc)">
                    <div v-if="currentIcc.icc">
                        <h3>Icc: {{ currentIcc.icc }}</h3>

                        <h5>Compañia: {{ currentIcc.company.name }}</h5>

                        <h5>Tipo sim: {{ currentIcc.type.name }}</h5>

                        <h5>Estatus: {{ currentIcc.status }}</h5>

                        <div v-if="currentIcc.linea">
                            <h5>
                                Estatus Linea:
                                {{ currentIcc.linea.status }}
                                {{ currentIcc.linea.reason }}
                            </h5>
                        </div>
                    </div>
                    <ValidationProvider
                        name="numero"
                        v-slot="validationContext"
                        rules="required|digits:10"
                    >
                        <b-form-group
                            label="Numero"
                            label-size="lg"
                            class="mt-3"
                        >
                            <b-input
                                type="number"
                                v-model="iccData.dn"
                                placeholder="Ingresa numero de telefono"
                                :disabled="disableLineaInputs"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            ></b-input>
                            <b-form-invalid-feedback>{{
                                validationContext.errors[0]
                            }}</b-form-invalid-feedback>
                        </b-form-group>
                    </ValidationProvider>

                    <ValidationProvider
                        name="producto"
                        v-slot="validationContext"
                        rules="required"
                    >
                        <b-form-group
                            label="Producto"
                            label-size="lg"
                            class="mt-3"
                        >
                            <select-general
                                url="/get/icc-products"
                                pholder="Seleccionar Producto"
                                v-model.number="iccData.iccProduct"
                                :disabled="disableLineaInputs"
                                v-on:input="productUpdated"
                                :state="getValidationState(validationContext)"
                            >
                            </select-general>
                            <b-form-invalid-feedback>{{
                                validationContext.errors[0]
                            }}</b-form-invalid-feedback>
                        </b-form-group>
                    </ValidationProvider>
                    <ValidationProvider
                        name="subproducto"
                        v-slot="validationContext"
                        rules="required"
                        v-if="showSubProductoSelect"
                    >
                        <b-form-group
                            label="SubProducto"
                            label-size="lg"
                            class="mt-3"
                        >
                            <select-general
                                ref="subProductSelect"
                                url="/get/icc-subproducts"
                                :query="iccData.iccProduct.id"
                                :query2="currentIcc.company.id"
                                pholder="Seleccionar Subproducto"
                                v-model="iccData.iccSubProduct"
                                v-on:input="subproductUpdated"
                                :state="getValidationState(validationContext)"
                            >
                            </select-general>

                            <b-form-invalid-feedback>{{
                                validationContext.errors[0]
                            }}</b-form-invalid-feedback>
                        </b-form-group>
                    </ValidationProvider>
                    <!-- inputs de linea nueva -->

                    <ValidationProvider
                        name="recarga"
                        v-slot="validationContext"
                        rules="required"
                        v-if="showRecargaInputs"
                    >
                        <b-form-group
                            label="Recarga"
                            label-size="lg"
                            class="mt-3"
                        >
                            <select-general
                                ref="recargaSelect"
                                url="/get/recargas"
                                :query="currentIcc.company.id"
                                pholder="Seleccionar Recarga"
                                v-model="iccData.recarga"
                                :disabled="disableRecargaInputs"
                                :state="getValidationState(validationContext)"
                            >
                            </select-general>
                            <b-form-invalid-feedback>{{
                                validationContext.errors[0]
                            }}</b-form-invalid-feedback>
                        </b-form-group>
                    </ValidationProvider>
                    <b-form-group label="Precio" label-size="lg" class="mt-3">
                         <b-form-input :value="simPrice" readonly></b-form-input>
                    </b-form-group>
                    <!-- inputs de portabilidad -->
                    <div
                        v-if="iccData.iccProduct && iccData.iccProduct.id == 2"
                    >
                        <ValidationProvider
                            name="nip"
                            v-slot="validationContext"
                            rules="required|digits:4"
                        >
                            <b-form-group
                                label="Nip"
                                label-size="lg"
                                class="mt-3"
                            >
                                <b-input
                                    placeholder="Nip"
                                    type="number"
                                    v-model="iccData.porta.nip"
                                    autocomplete="off"
                                    :state="
                                        getValidationState(validationContext)
                                    "
                                ></b-input>
                                <b-form-invalid-feedback>{{
                                    validationContext.errors[0]
                                }}</b-form-invalid-feedback>
                            </b-form-group>
                        </ValidationProvider>
                        <ValidationProvider
                            name="trafico"
                            v-slot="validationContext"
                            rules="required"
                        >
                            <b-form-group
                                label="Trafico"
                                label-size="lg"
                                class="mt-3"
                            >
                                <b-form-radio-group
                                    v-model="iccData.porta.trafico"
                                    buttons
                                    button-variant="primary"
                                    :options="[
                                        {
                                            text: 'Si',
                                            value: true,
                                        },
                                        {
                                            text: 'No',
                                            value: false,
                                        },
                                    ]"
                                    :state="
                                        getValidationState(validationContext)
                                    "
                                ></b-form-radio-group>
                                <b-form-invalid-feedback>{{
                                    validationContext.errors[0]
                                }}</b-form-invalid-feedback>
                            </b-form-group>
                        </ValidationProvider>

                        <ValidationProvider
                            name="fvc"
                            v-slot="validationContext"
                            rules="required"
                        >
                            <b-form-group
                                label="Fvc"
                                label-size="lg"
                                class="mt-3"
                            >
                                <b-form-datepicker
                                    :max="fvc.max"
                                    :min="fvc.min"
                                    placeholder="Fecha ventana de cambio"
                                    v-model="iccData.porta.fvc"
                                    :state="
                                        getValidationState(validationContext)
                                    "
                                ></b-form-datepicker>
                                <b-form-invalid-feedback>{{
                                    validationContext.errors[0]
                                }}</b-form-invalid-feedback>
                            </b-form-group>
                        </ValidationProvider>
                    </div>

                    <b-form-group class="mt-3">
                        <b-button block type="submit">
                            Agregar
                        </b-button>
                    </b-form-group>
                </b-form>
            </validation-observer>
        </b-modal>
    </div>
</template>

<script>
export default {
    data() {
        const now = new Date();

        const today = new Date(
            now.getFullYear(),
            now.getMonth(),
            now.getDate()
        );

        const todayWithHour = new Date(now.getHours());

        var minDate = new Date(today);

        if (todayWithHour.getHours() < 16) {
            minDate.setDate(minDate.getDate() + 1);

            if (minDate.getDay() === 0) {
                minDate.setDate(minDate.getDate() + 1);
            }
        } else {
            minDate.setDate(minDate.getDate() + 2);

            if (minDate.getDay() === 0) {
                minDate.setDate(minDate.getDate() + 1);
            }
        }

        const maxDate = new Date(today);
        maxDate.setDate(maxDate.getDate() + 8);
        return {
            fvc: {
                min: minDate,

                max: maxDate,
            },
            currentIcc: null,
            iccData: {
                iccProduct: null,
                iccSubProduct: null,
                dn: null,
                recarga: null,
                porta: {
                    nip: null,
                    fvc: minDate,
                    trafico: null,
                },
            },
        };
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        newIcc(icc) {
            this.$root.$emit("bv::show::modal", "icc-modal");

            this.currentIcc = icc;

            if (icc.linea) {
                this.iccData.dn = icc.linea.dn;

                this.iccData.iccProduct = icc.linea.icc_product_id;
            }
        },
        subproductUpdated() {
            
            if (
                this.iccData.iccSubProduct &&
                this.iccData.iccSubProduct.recarga_id
            ) {
                this.iccData.recarga = this.iccData.iccSubProduct.recarga_id;

                this.$refs['recargaSelect'].loadData();
            } else {
                      this.iccData.recarga = null;
            }
          

        },
        productUpdated() {
            this.iccSubProduct = null;
        },
        closeIccModal() {
            this.$root.$emit("bv::hide::modal", "icc-modal");

            // this.currentIcc = null;

            this.iccData = {
                iccProduct: null,
                iccSubProduct: null,
                dn: null,
                recarga: null,
                porta: {
                    nip: null,
                    fvc: this.minDate,
                    trafico: null,
                },
            };
        },

        buildIcc() {
            var asd = {
                id: this.currentIcc.id,
                serie: this.currentIcc.icc,
                status: this.currentIcc.status,
                inventario: this.currentIcc.inventario.inventarioable.name,
                companyName: this.currentIcc.company.name,
                dn: this.iccData.dn,
                iccProduct: this.iccData.iccProduct,
                iccSubProduct: this.iccData.iccSubProduct,
                recarga: this.iccData.recarga,
                porta: this.iccData.porta,
                type: "iccs",
                precio: this.simPrice,
            };

            if (asd.recarga) {
                this.iccData.recarga;
            }
            if (this.iccData.iccProduct.id == 2) {
                asd.descripcion = `Producto: ${asd.iccProduct.name}   Numero: ${asd.dn}   Compañia: ${asd.companyName}`;
            } else {
                asd.descripcion = `Producto: ${asd.iccProduct.name}   ${asd.iccSubProduct.name}   Compañia: ${asd.companyName}   Numero: ${asd.dn}`;
            }

            this.$emit("add-icc", { ...asd });

            this.closeIccModal();
        },
    },
    computed: {
        showSubProductoSelect: function () {
            if (this.iccData.iccProduct) {
                return true;
            } else {
                return false;
            }
        },
        disableLineaInputs: function () {
            if (this.currentIcc.linea) {
                return true;
            } else {
                return false;
            }
        },
        disableRecargaInputs: function () {
            if (this.iccData.iccSubProduct.recarga_id) {
                return true;
            } else {
                return false;
            }
        },
        showRecargaInputs: function () {
            if (
                this.iccData.iccSubProduct &&
                this.iccData.iccSubProduct.recarga_required == true
            ) {
                return true;
            } else {
                return false;
            }
        },

        simPrice: function () {
            if (this.iccData.iccSubProduct) {
                var price = this.iccData.iccSubProduct.precio;

                if (this.iccData.recarga) {
                    price =
                        Number(this.iccData.iccSubProduct.precio) +
                        Number(this.iccData.recarga.monto);

                    if (isNaN(price)) price = 0;
                }

                return price;
            } else return 0;
        },
    },
};
</script>

<style></style>
