<template>
    <div>
        <validation-observer ref="observer" v-slot="{ handleSubmit }">
            <b-form @submit.prevent="handleSubmit(addItem)">
                <!-- grupo para inventario -->
                <ValidationProvider v-slot="validationContext" rules="required">
                    <b-form-group
                        label="Inventario"
                        label-for="select-inventario"
                        label-size="lg"
                    >
                        <select-general
                            url="/inventario"
                            pholder="Seleccionar Inventario"
                            v-model="item.inventario"
                            :state="getValidationState(validationContext)"
                        >
                        </select-general>
                        <b-form-invalid-feedback
                            ><b-form-invalid-feedback>{{
                                validationContext.errors[0]
                            }}</b-form-invalid-feedback></b-form-invalid-feedback
                        >
                    </b-form-group>
                </ValidationProvider>
                <ValidationProvider v-slot="validationContext" rules="required">
                    <b-form-group label="Compañia" label-size="lg">
                        <select-general
                            url="/get/companies"
                            pholder="Seleccionar Compañia"
                            v-model="item.company"
                            :state="getValidationState(validationContext)"
                        ></select-general>

                        <b-form-invalid-feedback
                            ><b-form-invalid-feedback>{{
                                validationContext.errors[0]
                            }}</b-form-invalid-feedback></b-form-invalid-feedback
                        >
                    </b-form-group>
                </ValidationProvider>

                <!-- grupo para tipo de icc -->
                <ValidationProvider
                    v-slot="validationContext"
                    rules="required"
                    v-if="item.company"
                >
                    <b-form-group label="Tipo de Tarjeta sim" label-size="lg">
                        <select-general
                            url="/get/icctypes"
                            pholder="Seleccionar tipo de sim"
                            v-model="item.simType"
                            :query="item.company.id"
                            :state="getValidationState(validationContext)"
                        >
                        </select-general>
                        <b-form-invalid-feedback
                            ><b-form-invalid-feedback>{{
                                validationContext.errors[0]
                            }}</b-form-invalid-feedback></b-form-invalid-feedback
                        >
                    </b-form-group>
                </ValidationProvider>

                <!-- valida la entrada de serie -->
                <ValidationProvider
                    v-slot="validationContext"
                    rules="required|numeric|Icc"
                    name="Icc"
                >
                    <b-form-group
                        label="Icc"
                        label-for="serie"
                        label-size="lg"
                        :invalid-feedback="validationContext.errors[0]"
                        :state="getValidationState(validationContext)"
                    >
                        <b-input-group>
                            <b-input
                                :placeholder="`Ingresa 1 Icc`"
                                name="serie"
                                v-model="item.serie"
                                autocomplete="off"
                                type="number"
                                :state="getValidationState(validationContext)"
                            ></b-input>

                            <b-input-group-append>
                                <b-button variant="success" type="submit"
                                    >Agregar</b-button
                                >
                            </b-input-group-append>
                        </b-input-group>
                    </b-form-group>
                </ValidationProvider>
            </b-form>
        </validation-observer>
        <!-- agregar excel -->
        <b-form-group label="Excel" label-size="lg">
            <b-form-file
                v-model="file"
                :state="Boolean(file)"
                placeholder="Agregar archivo Excel"
                drop-placeholder="Arrastra el archivo aqui"
                browse-text="Excel"
                accept=".xlsx, .csv"
            ></b-form-file>
        </b-form-group>

        <b-form-group :description="`Cantidad: ${items.length}`">
            <b-button block variant="primary" @click="sendData()" type="submit" :disabled="agregarButtonDisabled"
                >Agregar a Inventario</b-button
            >
        </b-form-group>

        <b-list-group class="d-flex justify-content-between">
            <b-list-group-item v-for="(articulo, index) in items" :key="index">
                {{ index + 1 }} :
                <strong>{{ articulo.serie }}</strong>
                <small>{{ articulo.inventario.name }}</small>
                <small
                    >{{ articulo.company.name }}
                    {{ articulo.simType.name }}</small
                >
                <b-button
                    size="sm"
                    class="float-right"
                    variant="danger"
                    @click="eliminarSerie(articulo, index)"
                    >Eliminar</b-button
                >
            </b-list-group-item>
        </b-list-group>
    </div>
</template>

<script>
export default {
    data() {
        return {
            item: {
                company: null,
                iccType: null,
                serie: null,
                inventario: null,
            },
            items: [],

            file: null,
        };
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        addItem() {
            if (this.items.some((e) => e.serie === this.item.serie)) {
                alert("duplicado");
            } else {
                this.items.unshift({ ...this.item });

                this.item.serie = "";
            }
        },
        //elimina la serie del array items
        eliminarSerie(item, index) {
            this.items.splice(index, 1);
        },
        sendData() {
            this.$emit("is-loading", true);

            const settings = {
                headers: {
                    "content-type": "multipart/form-data",
                },
            };

            const data = new FormData();
            data.append("data", JSON.stringify(this.items));

            data.append("file", this.file);

            data.set("icc_type_id", this.item.simType.id);

            data.set("company_id", this.item.company.id);

            data.set("inventario_id", this.item.inventario.id);

            axios
                .post("/icc", data, settings)
                .then((response) => {
                    this.$emit("errores", response.data.errors);

                    this.$emit("exitosos", response.data.success);

                    this.$emit("is-loading", false);
                })
                .catch((error) => {
                    alert(error);
                    this.$emit("is-loading", false);
                });

            this.items = [];

            this.file = null;
        },
    },
    computed: {
        agregarButtonDisabled() {
            if (
                this.item.company == null || this.item.simType == null||
                this.item.inventario == null ||
                (this.items.length == 0 && this.file == null )
            ) {
                return true;
            } else {
                return false;                
            }
        },
    },
};
</script>

<style></style>
