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
                            query="App\Sucursal"
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
                <!-- valida que el producto sea 1 imei  -->
                <ValidationProvider v-slot="validationContext" rules="required">
                    <b-form-group label="Equipo" label-size="lg">
                        <select-general
                            url="/get/equipos"
                            pholder="Seleccionar Equipo"
                            v-model="item.equipo"
                            :state="getValidationState(validationContext)"
                            :equipo="true"
                        ></select-general>

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
                    rules="required|numeric|Imei"
                    name="Imei"
                >
                    <b-form-group
                        label="Imei"
                        label-for="serie"
                        label-size="lg"
                        :invalid-feedback="validationContext.errors[0]"
                        :state="getValidationState(validationContext)"
                    >
                        <b-input-group>
                            <b-input
                                :placeholder="`Ingresa 1 Imei`"
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
                    >{{ articulo.equipo.marca }}
                    {{ articulo.equipo.modelo }} ${{
                        articulo.equipo.precio
                    }}</small
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
                equipo: null,
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

            data.set("equipo_id", this.item.equipo.id);

            data.set("inventario_id", this.item.inventario.id);

            axios
                .post("/imei", data, settings)
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
                this.item.equipo == null ||
                this.item.inventario == null ||
                (this.items.length == 0 && this.file == null)
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
