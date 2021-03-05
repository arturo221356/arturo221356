<template>
    <div>
        <b-overlay :show="isLoading" rounded="sm">
            <div class="jumbotron jumbotron-fluid">
                <div class="col-lg-12">
                    <h1>Cuentas</h1>
                </div>

                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="container">
                            <b-form-group
                                label="Periodo de tiempo"
                                label-size="lg"
                            >
                                <b-form-radio-group
                                    id="btn-radios-1"
                                    v-model="periodoTiempoPersonalizado"
                                    buttons
                                    @input="
                                        periodoTiempoPersonalizado == false
                                            ? loadCajaData(selectedListItem)
                                            : ''
                                    "
                                >
                                    <b-form-radio :value="false"
                                        >Ultimo Corte</b-form-radio
                                    >
                                    <b-form-radio :value="true"
                                        >Personalizado</b-form-radio
                                    >
                                </b-form-radio-group>
                            </b-form-group>
                        </div>

                        <div v-if="periodoTiempoPersonalizado == true">
                            <div class="col-lg-12">
                                <b-form-group label="Fecha Inicial">
                                    <b-form-datepicker
                                        locale="es"
                                        v-model="initialDate"
                                        :max="maxDate"
                                    ></b-form-datepicker>
                                </b-form-group>
                            </div>
                            <div class="col-lg-12">
                                <b-form-group label="Fecha Final">
                                    <b-form-datepicker
                                        locale="es"
                                        v-model="finalDate"
                                        :max="maxDate"
                                    ></b-form-datepicker>
                                </b-form-group>
                                <b-form-group>
                                    <b-button
                                        block
                                        variant="primary"
                                        @click="loadCajaData(selectedListItem)"
                                        >Cargar</b-button
                                    >
                                </b-form-group>
                            </div>
                        </div>

                        <b-list-group>
                            <b-list-group-item
                                button
                                v-for="caja in cajas"
                                :key="caja.id"
                                :active="selectedListItem.id == caja.id"
                                @click="selectCaja(caja)"
                            >
                                <div
                                    class="d-flex w-100 justify-content-between"
                                >
                                    <h5>
                                        <B>{{ caja.name }}</B>
                                    </h5>

                                    <small
                                        ><B>Ultimo Corte:</B>
                                        {{ caja.lastcorteDate }}</small
                                    >
                                </div>

                                <div class="float-right">
                                    Total: ${{ caja.total }}
                                </div>
                            </b-list-group-item>
                        </b-list-group>
                    </div>

                    <div class="col-md-8">
                        <b-overlay :show="cardsLoading" rounded="sm">
                            <b-card no-body>
                                <b-tabs v-if="selectedListItem.id" card>
                                    <b-tab title="Resumen" active v-if="periodoTiempoPersonalizado == false">
                                        <b-button-toolbar>
                                            <!-- <b-button
                                                size="sm"
                                                variant="primary"
                                                v-b-modal.ajustar-caja
                                                >Ajustar</b-button
                                            > -->
                                        </b-button-toolbar>

                                        <div class="col-12 mt-4">
                                            <h5>
                                                <B>Ingresos:</B> ${{
                                                    computedIngresosTotal
                                                }}
                                            </h5>
                                            <br />
                                            <h5>
                                                <B>Gastos:</B> ${{
                                                    computedGastosTotal
                                                }}
                                            </h5>
                                            <br />
                                            
                                            <h5>
                                                <B>Restante en Caja ultimo Corte:</B> ${{
                                                    selectedListItem.restante
                                                }}
                                            </h5>
                                            <br />
                                            <h5>
                                                <B>Total en caja:</B> ${{
                                                    selectedListItem.total
                                                }}
                                            </h5>
                                        </div>
                                    </b-tab>
                                    <b-tab title="Ingresos">
                                        <b-button-toolbar>
                                            <b-button
                                                size="sm"
                                                variant="primary"
                                                v-b-modal.agregar-ingreso
                                                >Agregar Ingreso</b-button
                                            >
                                        </b-button-toolbar>

                                        <div class="float-right">
                                            Total: ${{ computedIngresosTotal }}
                                        </div>
                                        <div class="mt-4" v-if="ingresos.length >0">
                                            <h5><B>Otros:</B></h5>
                                        </div>

                                        <div class="mt-3">
                                            <b-table
                                                striped
                                                hover
                                                :items="ingresos"
                                            ></b-table>
                                        </div>

                                        <div class="mt-2" v-if="totalsPerDay.length >0">
                                            <h5><B>Ventas:</B></h5>
                                        </div>

                                        <b-table
                                            striped
                                            hover
                                            :items="totalsPerDay"
                                        ></b-table
                                    ></b-tab>
                                    <b-tab title="Gastos">
                                        <b-button-toolbar>
                                            <b-button
                                                size="sm"
                                                variant="primary"
                                                v-b-modal.agregar-gasto
                                                >Agregar Gasto</b-button
                                            >
                                        </b-button-toolbar>
                                        <div class="float-right">
                                            Total: ${{ computedGastosTotal }}
                                        </div>
                                        <div class="mt-3">
                                            <b-table
                                                striped
                                                hover
                                                :items="gastos"
                                            ></b-table>
                                        </div>
                                    </b-tab>
                                    <b-tab title="Cortes">
                                        <b-button-toolbar>
                                            <b-button
                                                size="sm"
                                                variant="primary"
                                                v-b-toggle.corte-collapse
                                                >Hacer Corte</b-button
                                            >
                                        </b-button-toolbar>
                                        <b-collapse
                                            id="corte-collapse"
                                            @hide="hideCorteCollapse"
                                            v-model="corteCollapse"
                                        >
                                            <validation-observer
                                                ref="corte"
                                                v-slot="{ handleSubmit }"
                                            >
                                                <b-form
                                                    @submit.prevent="
                                                        handleSubmit(newCorte)
                                                    "
                                                >
                                                    <div class="mt-3">
                                                        <div class="col-4">
                                                            <ValidationProvider
                                                                name="monto"
                                                                v-slot="
                                                                    validationContext
                                                                "
                                                                :rules="`required|numeric|caja:${selectedListItem.total}`"
                                                            >
                                                                <b-form-group
                                                                    description="Ingresa la cantidad de dinero a recoger"
                                                                    label-for="input-corte"
                                                                    :invalid-feedback="
                                                                        validationContext
                                                                            .errors[0]
                                                                    "
                                                                    label="Cantidad:"
                                                                    :state="
                                                                        getValidationState(
                                                                            validationContext
                                                                        )
                                                                    "
                                                                >
                                                                    <b-input
                                                                        id="input-corte"
                                                                        v-model.number="
                                                                            corteMonto
                                                                        "
                                                                        placeholder="$"
                                                                        :state="
                                                                            getValidationState(
                                                                                validationContext
                                                                            )
                                                                        "
                                                                        autocomplete="off"
                                                                        type="number"
                                                                    ></b-input>
                                                                </b-form-group>
                                                            </ValidationProvider>
                                                        </div>
                                                        <div class="col-4">
                                                            <b-button-group
                                                                size="sm"
                                                            >
                                                                <b-button
                                                                    variant="success"
                                                                    type="submit"
                                                                    >Aceptar</b-button
                                                                >
                                                                <b-button
                                                                    @click="
                                                                        hideCorteCollapse()
                                                                    "
                                                                    >Cancelar</b-button
                                                                >
                                                            </b-button-group>
                                                        </div>
                                                    </div>
                                                </b-form>
                                            </validation-observer>
                                        </b-collapse>
                                        <div class="mt-3">
                                            <b-table
                                                striped
                                                hover
                                                :items="cortes"
                                            ></b-table>
                                        </div>
                                    </b-tab>
                                </b-tabs>
                            </b-card>
                        </b-overlay>
                    </div>
                </div>
            </div>
        </b-overlay>

        <!-- modal de ajustar caja  -->

        <b-modal
            id="ajustar-caja"
            :title="`Ajustar caja ${selectedListItem.name}`"
        >
            <p class="my-4">Hello from modal!</p>
        </b-modal>

        <!-- agregar ingreso  -->
        <b-modal
            id="agregar-ingreso"
            title="Agregar Ingreso"
            hide-footer
            @hide="hideIngresoModal"
        >
            <b-overlay :show="ingresoModalLoading" rounded="sm">
                <validation-observer ref="gasto" v-slot="{ handleSubmit }">
                    <b-form @submit.prevent="handleSubmit(addIngreso)">
                        <ValidationProvider
                            name="nombre"
                            v-slot="validationContext"
                            rules="required"
                        >
                            <b-form-group label="Nombre" label-size="lg">
                                <b-input
                                    placeholder="Nombre"
                                    v-model="ingreso.name"
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
                            name="Descripcion"
                            v-slot="validationContext"
                            rules="required"
                        >
                            <b-form-group label="Descripcion" label-size="lg">
                                <b-input
                                    placeholder="Descripcion"
                                    v-model="ingreso.description"
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
                            name="monto"
                            v-slot="validationContext"
                            :rules="`required|numeric`"
                        >
                            <b-form-group label="Monto" label-size="lg">
                                <b-input
                                    placeholder="Monto"
                                    type="number"
                                    v-model.number="ingreso.monto"
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

                        <b-form-group>
                            <b-button variant="success" type="submit"
                                >Agregar</b-button
                            >
                            <b-button @click="hideIngresoModal"
                                >Cancelar</b-button
                            >
                        </b-form-group>
                    </b-form>
                </validation-observer>
            </b-overlay>
        </b-modal>

        <!-- agregar gasto  -->

        <b-modal
            id="agregar-gasto"
            title="Agregar Gasto"
            hide-footer
            @hide="hideGastoModal"
        >
            <b-overlay :show="gastoModalLoading" rounded="sm">
                <validation-observer ref="gasto" v-slot="{ handleSubmit }">
                    <b-form @submit.prevent="handleSubmit(addGasto)">
                        <ValidationProvider
                            name="nombre"
                            v-slot="validationContext"
                            rules="required"
                        >
                            <b-form-group label="Nombre" label-size="lg">
                                <b-input
                                    placeholder="Nombre"
                                    v-model="gasto.name"
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
                            name="Descripcion"
                            v-slot="validationContext"
                            rules="required"
                        >
                            <b-form-group label="Descripcion" label-size="lg">
                                <b-input
                                    placeholder="Descripcion"
                                    v-model="gasto.description"
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
                            name="monto"
                            v-slot="validationContext"
                            :rules="`required|numeric|caja:${selectedListItem.total}`"
                        >
                            <b-form-group label="Monto" label-size="lg">
                                <b-input
                                    placeholder="Monto"
                                    type="number"
                                    v-model.number="gasto.monto"
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

                        <b-form-group>
                            <b-button variant="success" type="submit"
                                >Agregar</b-button
                            >
                            <b-button @click="hideGastoModal"
                                >Cancelar</b-button
                            >
                        </b-form-group>
                    </b-form>
                </validation-observer>
            </b-overlay>
        </b-modal>
    </div>
</template>

<script>
export default {
    data() {
        return {
            corteCollapse: false,

            gastoModalLoading: false,

            ingresoModalLoading: false,

            isLoading: false,

            cardsLoading: false,

            corteMonto: null,

            gasto: {
                name: null,
                description: null,
                monto: null,
            },
            ingreso: {
                name: null,
                description: null,
                monto: null,
            },
            gastos: [],

            ingresos:[],

            totalsPerDay: [],

            cajas: [],

            cortes: [],

            periodoTiempoPersonalizado: false,

            selectedListItem: {
                id: null,
                total: 0,
            },
            initialDate: new Date().toISOString().substr(0, 10),

            finalDate: new Date().toISOString().substr(0, 10),
        };
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        hideCorteCollapse() {
            this.corteCollapse = false;

            this.corteMonto = null;
        },
        newCorte() {
            this.cardsLoading = true;

            const params = {
                monto_corte: this.corteMonto,
                caja_id: this.selectedListItem.id,
            };

            axios
                .post("/corte", params)

                .then((response) => {
                    this.$bvToast.toast(`${response.data.message}`, {
                        title: `${response.data.title}`,
                        variant:
                            response.data.success == true
                                ? "success"
                                : "danger",
                        solid: true,
                    });
                    console.log(response);

                    if (response.data.success == true) {
                        this.selectedListItem.total -= this.corteMonto;

                        // this.selectedListItem.lastcorteDate =
                        //     response.data.lastcorteDate;

                        this.loadCajaData(this.selectedListItem);

                        this.hideCorteCollapse();

                        this.loadCajas();
                    } else {
                        this.cardsLoading = false;
                    }
                })
                .catch(function (error) {
                    alert(error);
                    this.cardsLoading = false;
                    this.gastoModalLoading = false;
                });
        },

        hideGastoModal() {
            this.$root.$emit("bv::hide::modal", "agregar-gasto");

            this.gasto = {
                name: null,
                description: null,
                price: null,
            };

            this.gastoModalLoading = false;
        },
        hideIngresoModal() {
            this.$root.$emit("bv::hide::modal", "agregar-ingreso");

            this.ingreso = {
                name: null,
                description: null,
                price: null,
            };

            this.ingresoModalLoading = false;
        },

        loadCajas() {
            this.isLoading = true;
            axios
                .post("/get/cajas")

                .then((response) => {
                    this.cajas = response.data.data;

                    console.log(response.data.data);

                    if (
                        this.cajas.length > 0 &&
                        this.selectedListItem.id == null
                    ) {
                        this.selectCaja(this.cajas[0]);
                    }
                    this.isLoading = false;
                })
                .catch(function (error) {
                    alert(error);
                    this.isLoading = false;
                });
        },
        selectCaja(caja) {
            this.selectedListItem = caja;

            this.loadCajaData(caja);
        },
        loadCajaData(caja) {
            this.cardsLoading = true;
            //carga gastos
            const params = {
                caja_id: caja.id,
                customDates: this.periodoTiempoPersonalizado,
                initial_date: this.initialDate,
                final_date: this.finalDate,
            };
            axios
                .post("/get/gastos", params)

                .then((response) => {
                    this.gastos = response.data.data;
                    console.log(response.data.data);
                })
                .catch(function (error) {
                    alert(error);
                });
            axios
                .post("/get/incomes", params)

                .then((response) => {
                    this.ingresos = response.data.data;
                    console.log(response.data.data);
                })
                .catch(function (error) {
                    alert(error);
                });
            axios
                .post("/ventas/perday", params)

                .then((response) => {
                    this.totalsPerDay = response.data;
                    console.log(response.data.data);
                })
                .catch(function (error) {
                    alert(error);
                });

            axios
                .post("/get/cortes", params)

                .then((response) => {
                    this.cortes = response.data.data;
                    console.log(response.data.data);

                    this.cardsLoading = false;
                })
                .catch(function (error) {
                    alert(error);
                });
        },
        ajustarTotalCaja() {},
        addIngreso() {
            this.ingresoModalLoading = true;
            this.cardsLoading = true;
            const params = {
                income_name: this.ingreso.name,
                income_description: this.ingreso.description,
                income_monto: this.ingreso.monto,
                caja_id: this.selectedListItem.id,
            };
            axios
                .post("/income", params)

                .then((response) => {
                    this.$bvToast.toast(`${response.data.message}`, {
                        title: `${response.data.title}`,
                        variant:
                            response.data.success == true
                                ? "success"
                                : "danger",
                        solid: true,
                    });
                    console.log(response);

                    if (response.data.success == true) {
                        this.selectedListItem.total += this.ingreso.monto;

                        this.loadCajaData(this.selectedListItem);

                        this.hideIngresoModal();

                        this.loadCajas();

                    } else {
                        this.cardsLoading = false;
                    }
                })
                .catch(function (error) {
                    alert(error);
                    this.cardsLoading = false;
                    this.gastoModalLoading = false;
                });
        },
        addGasto() {
            this.gastoModalLoading = true;
            this.cardsLoading = true;
            const params = {
                gasto_name: this.gasto.name,
                gasto_description: this.gasto.description,
                gasto_monto: this.gasto.monto,
                caja_id: this.selectedListItem.id,
            };
            axios
                .post("/gasto", params)

                .then((response) => {
                    this.$bvToast.toast(`${response.data.message}`, {
                        title: `${response.data.title}`,
                        variant:
                            response.data.success == true
                                ? "success"
                                : "danger",
                        solid: true,
                    });
                    console.log(response);

                    if (response.data.success == true) {
                        this.selectedListItem.total -= this.gasto.monto;

                        this.loadCajaData(this.selectedListItem);

                        this.loadCajas();

                        this.hideGastoModal();

                        
                    } else {
                        this.cardsLoading = false;
                    }
                })
                .catch(function (error) {
                    alert(error);
                    this.cardsLoading = false;
                    this.gastoModalLoading = false;
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
        computedGastosTotal: function () {
            var sum;

            sum = this.gastos.reduce((a, b) => +a + +b.monto, 0);

            return sum;
        },
        computedIngresosTotal: function () {
            var sum;

            var ventas;

            var ingresos;

            ventas = this.totalsPerDay.reduce((a, b) => +a + +b.total, 0);

            ingresos = this.ingresos.reduce((a, b) => +a + +b.monto, 0);
            
            sum = ventas + ingresos;

            return sum;
        },
    },

    created() {
        this.loadCajas();
    },
};
</script>

<style></style>
