<template>
    <div>
        <b-modal
            id="show-venta"
            title="Venta"
            :ok-only="true"
            v-if="currentVenta"
        >
            <show-venta :venta-id="currentVenta"></show-venta>
        </b-modal>
        <b-overlay :show="isLoading" rounded="sm">
            <div class="jumbotron jumbotron-fluid">
                <div>
                    <div class="col-xl-6 mx-auto">
                        <div v-if="iccDetails == false">
                            <div class="col-xl-12">
                                <h1>Nueva venta</h1>
                            </div>

                            <div class="col-xl-auto ml-auto">
                                <b-button
                                    class="mr-3"
                                    @click="openRecargaModal"
                                    v-if="can('create transaction')"
                                    >Agregar Recarga</b-button
                                >

                                <b-button @click="openGeneralModal">
                                    Agregar venta general
                                </b-button>

                                <b-modal
                                    id="recarga-modal"
                                    hide-footer
                                    @hide="hideRecargaModal"
                                >
                                    <validation-observer
                                        ref="observer"
                                        v-slot="{ handleSubmit }"
                                    >
                                        <b-form
                                            @submit.prevent="
                                                handleSubmit(addRecarga)
                                            "
                                        >
                                            <ValidationProvider
                                                name="numero"
                                                v-slot="validationContext"
                                                rules="required|digits:10|numeric"
                                            >
                                                <b-form-group
                                                    label="Numero"
                                                    label-size="lg"
                                                >
                                                    <b-input
                                                        placeholder="Insertar Numero"
                                                        v-model="newRecarga.dn"
                                                        type="number"
                                                        autocomplete="off"
                                                        :state="
                                                            getValidationState(
                                                                validationContext
                                                            )
                                                        "
                                                    ></b-input>
                                                    <b-form-invalid-feedback>{{
                                                        validationContext
                                                            .errors[0]
                                                    }}</b-form-invalid-feedback>
                                                </b-form-group>
                                            </ValidationProvider>

                                            <ValidationProvider
                                                name="compañia"
                                                v-slot="validationContext"
                                                rules="required"
                                            >
                                                <b-form-group
                                                    label="Compañia"
                                                    label-size="lg"
                                                >
                                                    <select-general
                                                        url="/get/companies"
                                                        v-model="
                                                            newRecarga.company
                                                        "
                                                        :state="
                                                            getValidationState(
                                                                validationContext
                                                            )
                                                        "
                                                    >
                                                    </select-general>
                                                    <b-form-invalid-feedback>{{
                                                        validationContext
                                                            .errors[0]
                                                    }}</b-form-invalid-feedback>
                                                </b-form-group>
                                            </ValidationProvider>

                                            <ValidationProvider
                                                name="recarga"
                                                v-slot="validationContext"
                                                rules="required"
                                            >
                                                <b-form-group
                                                    v-if="newRecarga.company"
                                                    label="Recarga"
                                                    label-size="lg"
                                                >
                                                    <select-general
                                                        url="/get/recargas"
                                                        :query="
                                                            newRecarga.company
                                                                .id
                                                        "
                                                        v-model="
                                                            newRecarga.recarga
                                                        "
                                                        :state="
                                                            getValidationState(
                                                                validationContext
                                                            )
                                                        "
                                                    >
                                                    </select-general>
                                                    <b-form-invalid-feedback>{{
                                                        validationContext
                                                            .errors[0]
                                                    }}</b-form-invalid-feedback>
                                                </b-form-group>
                                            </ValidationProvider>
                                            <b-button
                                                variant="success"
                                                type="submit"
                                                >Agregar</b-button
                                            >
                                            <b-button @click="hideRecargaModal"
                                                >Cancelar</b-button
                                            >
                                        </b-form>
                                    </validation-observer>
                                </b-modal>

                                <!-- modal de venta -->
                                <b-modal
                                    id="venta-modal"
                                    hide-footer
                                    @hide="hideVentaModal"
                                >
                                    <validation-observer
                                        ref="venta"
                                        v-slot="{ handleSubmit }"
                                    >
                                        <b-form
                                            @submit.prevent="
                                                handleSubmit(newVenta)
                                            "
                                        >
                                            <ValidationProvider
                                                name="nombre cliente"
                                                v-slot="validationContext"
                                                :rules="nombreClienteRequired"
                                            >
                                                <b-form-group
                                                    label="Nombre Cliente"
                                                    label-size="lg"
                                                >
                                                    <b-input
                                                        placeholder="Insertar nombre del cliente"
                                                        v-model="cliente.nombre"
                                                        autocomplete="off"
                                                        :state="
                                                            getValidationState(
                                                                validationContext
                                                            )
                                                        "
                                                    ></b-input>
                                                    <b-form-invalid-feedback>{{
                                                        validationContext
                                                            .errors[0]
                                                    }}</b-form-invalid-feedback>
                                                </b-form-group>
                                            </ValidationProvider>
                                            <ValidationProvider
                                                name="curp"
                                                v-slot="validationContext"
                                                rules=""
                                            >
                                                <b-form-group
                                                    label="Curp"
                                                    label-size="lg"
                                                >
                                                    <b-input
                                                        v-model="cliente.curp"
                                                        placeholder="Insertar Curp del cliente"
                                                        autocomplete="off"
                                                        :state="
                                                            getValidationState(
                                                                validationContext
                                                            )
                                                        "
                                                    ></b-input>
                                                    <b-form-invalid-feedback>{{
                                                        validationContext
                                                            .errors[0]
                                                    }}</b-form-invalid-feedback>
                                                </b-form-group>
                                            </ValidationProvider>
                                            <ValidationProvider
                                                name="rfc"
                                                v-slot="validationContext"
                                                rules=""
                                            >
                                                <b-form-group
                                                    label="RFC"
                                                    label-size="lg"
                                                >
                                                    <b-input
                                                        placeholder="Insertar RFC del cliente"
                                                        v-model="cliente.rfc"
                                                        autocomplete="off"
                                                        :state="
                                                            getValidationState(
                                                                validationContext
                                                            )
                                                        "
                                                    ></b-input>
                                                    <b-form-invalid-feedback>{{
                                                        validationContext
                                                            .errors[0]
                                                    }}</b-form-invalid-feedback>
                                                </b-form-group>
                                            </ValidationProvider>

                                            <ValidationProvider
                                                name="referencia"
                                                v-slot="validationContext"
                                                :rules="`digits:10|${nombreClienteRequired}`"
                                            >
                                                <b-form-group
                                                    label="Referencia"
                                                    label-size="lg"
                                                >
                                                    <b-input
                                                        v-model="
                                                            cliente.referencia
                                                        "
                                                        placeholder="Insertar Referencia del cliente"
                                                        autocomplete="off"
                                                        :state="
                                                            getValidationState(
                                                                validationContext
                                                            )
                                                        "
                                                    ></b-input>
                                                    <b-form-invalid-feedback>{{
                                                        validationContext
                                                            .errors[0]
                                                    }}</b-form-invalid-feedback>
                                                </b-form-group>
                                            </ValidationProvider>

                                            <ValidationProvider
                                                name="email"
                                                v-slot="validationContext"
                                                :rules="`email|${nombreClienteRequired}`"
                                            >
                                                <b-form-group
                                                    label="Email"
                                                    label-size="lg"
                                                >
                                                    <b-input
                                                        v-model="cliente.email"
                                                        placeholder="Insertar Email del cliente"
                                                        autocomplete="off"
                                                        :state="
                                                            getValidationState(
                                                                validationContext
                                                            )
                                                        "
                                                    ></b-input>
                                                    <b-form-invalid-feedback>{{
                                                        validationContext
                                                            .errors[0]
                                                    }}</b-form-invalid-feedback>
                                                </b-form-group>
                                            </ValidationProvider>

                                            <b-form-group
                                                label="Comentario"
                                                label-size="lg"
                                            >
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

                                            <b-form-group
                                                label="Cambio"
                                                label-size="lg"
                                            >
                                                <b-input
                                                    type="number"
                                                    placeholder="Pago"
                                                    :value="pago - totalVenta"
                                                    readonly
                                                ></b-input>
                                            </b-form-group>

                                            <b-button
                                                variant="success"
                                                block
                                                type="submit"
                                                >Vender</b-button
                                            >
                                            <b-button
                                                block
                                                @click="hideVentaModal"
                                                >Cancelar</b-button
                                            >
                                        </b-form>
                                    </validation-observer>
                                </b-modal>
                                <!-- modal de venta general -->
                                <b-modal
                                    id="general-modal"
                                    hide-footer
                                    title="Venta general"
                                    @hide="hideGeneralModal"
                                >
                                    <validation-observer
                                        ref="general"
                                        v-slot="{ handleSubmit }"
                                    >
                                        <b-form
                                            @submit.prevent="
                                                handleSubmit(addGeneral)
                                            "
                                        >
                                            <ValidationProvider
                                                name="nombre"
                                                v-slot="validationContext"
                                                rules="required"
                                            >
                                                <b-form-group
                                                    label="Nombre"
                                                    label-size="lg"
                                                >
                                                    <b-input
                                                        placeholder="Nombre"
                                                        v-model="
                                                            ventaGeneral.nombre
                                                        "
                                                        autocomplete="off"
                                                        :state="
                                                            getValidationState(
                                                                validationContext
                                                            )
                                                        "
                                                    ></b-input>
                                                    <b-form-invalid-feedback>{{
                                                        validationContext
                                                            .errors[0]
                                                    }}</b-form-invalid-feedback>
                                                </b-form-group>
                                            </ValidationProvider>
                                            <ValidationProvider
                                                name="Descripcion"
                                                v-slot="validationContext"
                                                rules="required"
                                            >
                                                <b-form-group
                                                    label="Descripcion"
                                                    label-size="lg"
                                                >
                                                    <b-input
                                                        placeholder="Descripcion"
                                                        v-model="
                                                            ventaGeneral.descripcion
                                                        "
                                                        autocomplete="off"
                                                        :state="
                                                            getValidationState(
                                                                validationContext
                                                            )
                                                        "
                                                    ></b-input>
                                                    <b-form-invalid-feedback>{{
                                                        validationContext
                                                            .errors[0]
                                                    }}</b-form-invalid-feedback>
                                                </b-form-group>
                                            </ValidationProvider>
                                            <ValidationProvider
                                                name="precio"
                                                v-slot="validationContext"
                                                rules="required|numeric"
                                            >
                                                <b-form-group
                                                    label="Precio"
                                                    label-size="lg"
                                                >
                                                    <b-input
                                                        placeholder="Precio"
                                                        type="number"
                                                        v-model.number="
                                                            ventaGeneral.precio
                                                        "
                                                        autocomplete="off"
                                                        :state="
                                                            getValidationState(
                                                                validationContext
                                                            )
                                                        "
                                                    ></b-input>
                                                    <b-form-invalid-feedback>{{
                                                        validationContext
                                                            .errors[0]
                                                    }}</b-form-invalid-feedback>
                                                </b-form-group>
                                            </ValidationProvider>

                                            <b-form-group>
                                                <b-button
                                                    variant="success"
                                                    type="submit"
                                                    >Agregar</b-button
                                                >
                                                <b-button
                                                    @click="hideGeneralModal"
                                                    >Cancelar</b-button
                                                >
                                            </b-form-group>
                                        </b-form>
                                    </validation-observer>
                                </b-modal>
                            </div>

                            <div class="col-xl-12">
                                <b-form @submit.prevent="agregarProducto">
                                    <b-form-group
                                        class="mt-4"
                                        label="Buscar producto:"
                                        label-size="lg"
                                    >
                                        <b-input-group>
                                            <b-form-input
                                                v-model="searchValue"
                                                autocomplete="off"
                                                placeholder="Buscar Producto"
                                                list="search-results"
                                                @keyup.stop="handleSearch"
                                            ></b-form-input>

                                            <datalist id="search-results">
                                                <option
                                                    v-for="(list,
                                                    index) in searchResults"
                                                    :key="index"
                                                    >{{ list.title }}</option
                                                >
                                            </datalist>

                                            <b-input-group-append>
                                                <b-button
                                                    type="submit"
                                                    variant="success"
                                                    >Agregar</b-button
                                                >
                                            </b-input-group-append>
                                        </b-input-group>
                                    </b-form-group>
                                </b-form>
                            </div>
                        </div>
                        <div v-if="iccDetails == true">
                            <div class="row">
                                <div class="col-sm">
                                    <h1
                                        class="float-right"
                                        :style="{ cursor: 'pointer' }"
                                    >
                                        <b-icon
                                            icon="x-circle-fill"
                                            variant="danger"
                                            @click="closeIccDetails"
                                        ></b-icon>
                                    </h1>
                                </div>
                            </div>

                            <validation-observer
                                ref="general"
                                v-slot="{ handleSubmit }"
                            >
                                <b-form
                                    @submit.prevent="handleSubmit(buildIcc)"
                                >
                                    <h3>Icc: {{ currentIcc.icc }}</h3>

                                    <h5>
                                        Compañia: {{ currentIcc.company.name }}
                                    </h5>

                                    <h5>
                                        Tipo sim: {{ currentIcc.type.name }}
                                    </h5>

                                    <h5>Estatus: {{ currentIcc.status }}</h5>

                                    <div v-if="currentIcc.linea">
                                        <h5>
                                            Estatus Linea:
                                            {{ currentIcc.linea.status }}
                                            {{ currentIcc.linea.reason }}
                                        </h5>
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
                                                :state="
                                                    getValidationState(
                                                        validationContext
                                                    )
                                                "
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
                                                v-model.number="
                                                    iccData.iccProduct
                                                "
                                                :disabled="disableLineaInputs"
                                                v-on:input="productUpdated"
                                                :state="
                                                    getValidationState(
                                                        validationContext
                                                    )
                                                "
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
                                                url="/get/icc-subproducts"
                                                :query="iccData.iccProduct.id"
                                                :query2="currentIcc.company.id"
                                                pholder="Seleccionar Subproducto"
                                                v-model="iccData.iccSubProduct"
                                                v-on:input="subproductUpdated"
                                                :state="
                                                    getValidationState(
                                                        validationContext
                                                    )
                                                "
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
                                                url="/get/recargas"
                                                :query="currentIcc.company.id"
                                                pholder="Seleccionar Recarga"
                                                v-model="iccData.recarga"
                                                :disabled="disableRecargaInputs"
                                                :state="
                                                    getValidationState(
                                                        validationContext
                                                    )
                                                "
                                            >
                                            </select-general>
                                            <b-form-invalid-feedback>{{
                                                validationContext.errors[0]
                                            }}</b-form-invalid-feedback>
                                        </b-form-group>
                                    </ValidationProvider>
                                    <a>{{ simPrice }}</a>
                                    <!-- inputs de portabilidad -->
                                    <div
                                        v-if="
                                            iccData.iccProduct &&
                                            iccData.iccProduct.id == 2
                                        "
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
                                                        getValidationState(
                                                            validationContext
                                                        )
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
                                                    v-model="
                                                        iccData.porta.trafico
                                                    "
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
                                                        getValidationState(
                                                            validationContext
                                                        )
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
                                                        getValidationState(
                                                            validationContext
                                                        )
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
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="col-xl-6 mx-auto">
                        <div
                            v-for="(producto, index) in productos"
                            :key="index"
                        >
                            <b-card :title="producto.serie">
                                <div class="row">
                                    <div class="col-xl-10">
                                        <b-card-text>
                                            {{ producto.descripcion }}
                                        </b-card-text>
                                    </div>
                                    <div class="col-xl-2">
                                        <b-button
                                            href="#"
                                            variant="danger"
                                            @click="eliminarItem(index)"
                                            >Eliminar</b-button
                                        >
                                    </div>
                                </div>

                                <div class="col-xl-2 ml-auto mt-3">
                                    <h5 class="ml-auto">
                                        Precio: ${{ producto.precio }}
                                    </h5>
                                </div>
                            </b-card>
                        </div>
                        <b-card
                            align="right"
                            border-variant="primary"
                            v-if="productos.length > 0"
                        >
                            <b-button
                                block
                                variant="success"
                                @click="openVentaModal"
                            >
                                Vender</b-button
                            >
                            <h4 class="ml-auto mt-4">
                                Total: ${{ totalVenta }}.00
                            </h4>
                        </b-card>
                    </div>
                </div>
            </div>
        </b-overlay>
    </div>
</template>

<script>
export default {
    data: function () {
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
            pago: null,

            iccDetails: false,

            cliente: {
                nombre: null,
                referencia: null,
                email: null,
                curp: null,
                rfc: null,
            },
            comentario: null,

            isLoading: false,

            searchValue: null,

            searchResults: [],

            currentIcc: null,

            currentVenta: 1,

            ventaGeneral: {
                nombre: null,
                descripcion: null,
                precio: null,
                type: "generales",
            },
            newRecarga: {
                dn: null,
                recarga: null,
                company: null,
                type: "recargas",
            },

            fvc: {
                min: minDate,

                max: maxDate,
            },

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

            productos: [],
        };
    },

    methods: {
        handleSearch: _.debounce(function () {
            this.searchProduct();
        }, 300),

        clearSearchValue() {
            this.searchValue = null;
        },

        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },

        addGeneral() {
            const general = {
                serie: this.ventaGeneral.nombre,
                precio: this.ventaGeneral.precio,
                descripcion: this.ventaGeneral.descripcion,
                type: "generales",
            };

            this.productos.unshift({ ...general });

            this.hideGeneralModal();
        },
        openGeneralModal() {
            this.$root.$emit("bv::show::modal", "general-modal");
        },
        openVentaModal() {
            this.$root.$emit("bv::show::modal", "venta-modal");

            this.pago = this.totalVenta;
        },
        openRecargaModal() {
            this.$root.$emit("bv::show::modal", "recarga-modal");
        },
        newVenta() {
            this.isLoading = true;

            const params = {
                productos: this.productos,
                comentario: this.comentario,
                cliente: this.cliente,
            };

            this.hideVentaModal();

            axios
                .post("/ventas", params)

                .then((response) => {
                    console.log(response.data);

                    this.currentVenta = response.data;

                    this.$bvModal.show("show-venta");

                    this.productos = [];

                    this.isLoading = false;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        addRecarga() {
            const recarga = {
                serie: this.newRecarga.recarga.name,
                precio: this.newRecarga.recarga.monto,
                recargaId: this.newRecarga.recarga.id,
                dn: this.newRecarga.dn,
                descripcion: `${this.newRecarga.recarga.name} Numero: ${this.newRecarga.dn}`,
                type: "recargas",
            };

            this.productos.unshift({ ...recarga });

            this.hideRecargaModal();
        },
        hideRecargaModal() {
            this.$root.$emit("bv::hide::modal", "recarga-modal");

            this.newRecarga = {
                dn: null,
                recarga: null,
                company: null,
                type: "recargas",
            };
        },
        hideGeneralModal() {
            this.$root.$emit("bv::hide::modal", "general-modal");

            this.ventaGeneral = {
                nombre: null,
                descripcion: null,
                precio: null,
                type: "generales",
            };
        },
        hideVentaModal() {
            this.$root.$emit("bv::hide::modal", "venta-modal");

            this.cliente = {
                nombre: null,
                referencia: null,
                email: null,
                curp: null,
                rfc: null,
            };
            this.comentario = null;
        },

        subproductUpdated() {
            if (
                this.iccData.iccSubProduct &&
                this.iccData.iccSubProduct.recarga_id
            ) {
                this.iccData.recarga = this.iccData.iccSubProduct.recarga_id;
            } else {
                this.iccData.recarga = null;
            }
        },
        productUpdated() {
            this.iccSubProduct = null;
        },

        buildIcc() {
            var icc = {
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

            if (icc.recarga) {
                this.iccData.recarga;
            }
            if (this.iccData.iccProduct.id == 2) {
                icc.descripcion = `Producto: ${icc.iccProduct.name}   Numero: ${icc.dn}   Compañia: ${icc.companyName}`;
            } else {
                icc.descripcion = `Producto: ${icc.iccProduct.name}   ${icc.iccSubProduct.name}   Compañia: ${icc.companyName}   Numero: ${icc.dn}`;
            }

            this.productos.unshift({ ...icc });

            this.closeIccDetails();
        },

        newIcc(icc) {
            this.iccDetails = true;

            this.currentIcc = icc;

            if (icc.linea) {
                this.iccData.dn = icc.linea.dn;

                this.iccData.iccProduct = icc.linea.icc_product_id;
            }
        },
        eliminarItem(index) {
            this.productos.splice(index, 1);
        },
        closeIccDetails() {
            this.iccDetails = false;

            this.currentIcc = null;

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

        agregarProducto() {
            const CancelToken = axios.CancelToken;

            const source = CancelToken.source();

            if (
                !this.productos.find((item) => item.serie == this.searchValue)
            ) {
                axios
                    .get(
                        "/search/venta-exact",
                        {
                            params: { search: this.searchValue },
                        },
                        {
                            cancelToken: source.token,
                        }
                    )
                    .then((response) => {
                        if (response.data.length > 0) {
                            const item = response.data[0].searchable;

                            switch (response.data[0].type) {
                                case "imeis":
                                    const imei = {
                                        id: item.id,
                                        serie: item.imei,
                                        status: item.status,
                                        descripcion: `${item.equipo.marca}  ${item.equipo.modelo}`,
                                        inventario:
                                            item.inventario.inventarioable.name,
                                        precio: item.equipo.precio,
                                        type: "imeis",
                                    };

                                    this.productos.unshift({ ...imei });

                                    break;
                                case "iccs":
                                    this.newIcc(response.data[0].searchable);

                                    break;

                                case "recargas":
                                    const recarga = {
                                        id: item.id,
                                        precio: item.monto,
                                        descripcion: item.name,
                                    };

                                    this.productos.unshift({ ...recarga });

                                    break;
                            }
                            this.clearSearchValue();
                        } else {
                            this.$bvToast.toast("No encontrado", {
                                title: `${this.searchValue}`,
                                variant: "warning",
                                solid: true,
                            });
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            } else {
                alert("duplicado");
            }
        },
        searchProduct() {
            const self = this;
            if (this.searchValue.length >= 5) {
                axios
                    .get("/search/venta-prediction", {
                        params: { search: this.searchValue },
                    })
                    .then(function (response) {
                        self.searchResults = response.data;
                    })
                    .catch(function (thrown) {
                        if (axios.isCancel(thrown)) {
                            console.log("Request canceled", thrown.message);
                        } else {
                            // handle error
                        }
                    });
            } else {
                self.searchResults = [];
            }
        },
    },
    watch: {},
    computed: {
        nombreClienteRequired(){
            // const products = ['iccs','imeis'];
            // if(this.productos.find(element => products.includes(element.type))){
            //     return 'required';
            // }else{
            //     return '';
            // }
            return '';
        },
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
                        this.iccData.iccSubProduct.precio +
                        this.iccData.recarga.monto;

                    if (isNaN(price)) price = 0;
                }

                return price;
            } else return 0;
        },

        totalVenta: function () {
            var total = 0;

            this.productos.forEach((element) => {
                total += element.precio;
            });

            return total;
        },
    },
};
</script>

<style></style>
