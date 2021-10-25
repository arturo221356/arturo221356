<template>
    <div>
        <b-modal
            ref="my-modal"
            hide-footer
            :title="`ICC: ${editableItem.serie}`"
            @hidden="resetItem"
        >
            <b-overlay :show="isLoading" rounded="sm">
                <validation-observer ref="observer" v-slot="{ handleSubmit }">
                    <b-form @submit.prevent="handleSubmit(updateItem)">
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
                                    v-model="editableItem.inventario"
                                    :state="
                                        getValidationState(validationContext)
                                    "
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
                                    v-model="editableItem.iccStatus"
                                    :state="
                                        getValidationState(validationContext)
                                    "
                                ></select-statuses>
                                <b-form-invalid-feedback>{{
                                    validationContext.errors[0]
                                }}</b-form-invalid-feedback>
                            </b-form-group>
                        </ValidationProvider>

                        <ValidationProvider
                            name="compañia"
                            v-slot="validationContext"
                            rules="required"
                            v-if="can('full update stock')"
                        >
                            <b-form-group label="Compañia:" label-size="lg">
                                <select-general
                                    url="/get/companies"
                                    pholder="Seleccionar Compañia"
                                    v-model="editableItem.company"
                                    :state="
                                        getValidationState(validationContext)
                                    "
                                >
                                </select-general>
                                <b-form-invalid-feedback>{{
                                    validationContext.errors[0]
                                }}</b-form-invalid-feedback>
                            </b-form-group>
                        </ValidationProvider>

                        <ValidationProvider
                            name="tipo sim"
                            v-slot="validationContext"
                            rules="required"
                            v-if="renderSimType"
                        >
                            <b-form-group label="Tipo:" label-size="lg">
                                <select-general
                                    url="/get/icctypes"
                                    pholder="Seleccionar tipo de sim"
                                    v-model="editableItem.iccType"
                                    :query="editableItem.company.id"
                                    :state="
                                        getValidationState(validationContext)
                                    "
                                    :v-if="editableItem.company"
                                >
                                </select-general>
                                <b-form-invalid-feedback>{{
                                    validationContext.errors[0]
                                }}</b-form-invalid-feedback>
                            </b-form-group>
                        </ValidationProvider>

                        <ValidationProvider
                            name="dn"
                            v-slot="validationContext"
                            rules="numeric|required|length:10"
                            v-if="renderDn"
                        >
                            <b-form-group label="DN:" label-size="lg">
                                <b-input-group>
                                    <b-input
                                        type="number"
                                        placeholder="Inserta DN"
                                        v-model="editableItem.linea.dn"
                                        :state="
                                            getValidationState(
                                                validationContext
                                            )
                                        "
                                    ></b-input>
                                    <b-input-group-append>
                                        <b-button
                                            v-if="can('destroy stock')"
                                            variant="outline-danger"
                                            @click="deleteLinea"
                                            >Eliminar linea</b-button
                                        >
                                    </b-input-group-append>
                                </b-input-group>
                                <b-form-invalid-feedback>{{
                                    validationContext.errors[0]
                                }}</b-form-invalid-feedback>
                            </b-form-group>
                        </ValidationProvider>
                        <ValidationProvider
                            name="estatus linea"
                            v-slot="validationContext"
                            rules="required"
                            v-if="renderDn"
                        >
                            <b-form-group
                                label="Estatus linea:"
                                label-size="lg"
                            >
                                <select-statuses
                                    estatusable="linea"
                                    v-model="editableItem.statusLinea.status"
                                    :state="
                                        getValidationState(validationContext)
                                    "
                                ></select-statuses>
                                <b-form-invalid-feedback>{{
                                    validationContext.errors[0]
                                }}</b-form-invalid-feedback>
                            </b-form-group>
                        </ValidationProvider>

                        <ValidationProvider
                            name="recarga"
                            v-slot="validationContext"
                            rules="required"
                            v-if="renderRecarga"
                        >
                            <b-form-group label="Recarga:" label-size="lg">
                                <b-form-select
                                    v-model="editableItem.statusLinea.reason"
                                    :state="
                                        getValidationState(validationContext)
                                    "
                                    :options="recargaOptions"
                                ></b-form-select>
                                <b-form-invalid-feedback>{{
                                    validationContext.errors[0]
                                }}</b-form-invalid-feedback>
                            </b-form-group>
                        </ValidationProvider>

                        <b-form-group label="Comentario" label-size="lg"
                            ><b-form-textarea
                                v-model="editableItem.comment"
                                max-rows="6"
                                placeholder="Comentario"
                            ></b-form-textarea
                        ></b-form-group>

                        <b-form-group>
                            <b-button block variant="success" type="submit"
                                >Guardar</b-button
                            >
                        </b-form-group>

                        <b-form-group v-if="can('destroy stock')">
                            <b-button block variant="danger" @click="deleteItem"
                                >Eliminar</b-button
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
            editableItem: {
                id: null,

                serie: null,

                inventario: null,

                iccStatus: { id: null, name: null },

                company: { id: null, name: null },

                iccType: { id: null, name: null },

                linea: {
                    id: null,
                    dn: null,
                },

                comment: null,

                statusLinea: {
                    status: { id: null, name: null },
                    reason: null,
                },
            },

            isLoading: false,

            recargaOptions: [
                { value: 50, text: "Recarga de $50" },

                { value: 100, text: "Recarga de $100" },

                { value: 150, text: "Recarga de $150" },
            ],
        };
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        deleteLinea() {
            this.isLoading = true;
            axios
                .delete(`/linea/${this.editableItem.linea.id}`)
                .then((response) => {
                    this.$bvToast.toast("Linea eliminada con exito", {
                        title: `${this.editableItem.linea.dn}`,
                        variant: "danger",
                        solid: true,
                    });

                    this.hideModal();

                    this.dataChanged();

                    this.isLoading = false;
                })
                .catch((error) => {
                    alert(error);
                    console.log(error);
                    this.isLoading = false;
                });
        },
        updateItem() {
            this.isLoading = true;

            var data = {
                comment: this.editableItem.comment,

                inventario_id: this.editableItem.inventario.id,

                status: this.editableItem.iccStatus.name,

                company_id: this.editableItem.company.id,

                icc_type_id: this.editableItem.iccType.id,

                linea_dn: this.editableItem.linea.dn,

                lineaStatus: this.editableItem.statusLinea.status.name,

                montoRecarga: this.editableItem.statusLinea.reason,
            };

            console.log(data);
            axios
                .put(`/icc/${this.editableItem.id}`, data)
                .then((response) => {
                    this.isLoading = false;

                    this.$bvToast.toast("Actualizado con exito", {
                        title: `Icc ${this.editableItem.serie}`,
                        variant: "success",
                        solid: true,
                    });
                    this.isLoadig = false;

                    this.dataChanged();

                    this.hideModal();
                })
                .catch((error) => {
                    console.log(error);
                    alert(error);
                    this.isLoadig = false;
                });
        },
        deleteItem() {
            this.isLoading = true;
            axios

                .delete(`/icc/${this.editableItem.id}`, {})
                .then((response) => {
                    this.isLoading = false;

                    this.$bvToast.toast("Eliminado con exito", {
                        title: `Icc  ${this.editableItem.serie}`,
                        variant: "danger",
                        solid: true,
                    });
                    this.dataChanged();

                    this.hideModal();
                })
                .catch((error) => {
                    console.log(error);
                    alert(error);
                    this.isLoadig = false;
                });
        },

        editIcc(item, row) {
            console.log(item);

            this.editableItem.id = item.id;

            this.editableItem.serie = item.serie;

            this.editableItem.comment = item.comment;

            this.editableItem.iccStatus = {
                id: item.status,
                name: item.status,
            };

            if (item.linea) {
                this.editableItem.linea.dn = item.linea.dn;

                this.editableItem.linea.id = item.linea.id;

                this.editableItem.statusLinea.status = {
                    id: item.linea.status,
                    name: item.linea.status,
                };

                this.editableItem.statusLinea.reason = item.linea.reason;
            }
            this.editableItem.company = {
                id: item.company.id,
                name: item.company.name,
            };

            this.editableItem.iccType = {
                id: item.type.id,
                name: item.type.name,
            };

            this.editableItem.inventario = {
                id: item.inventario_id,
                name: item.inventario_name,
            };

            this.$refs["my-modal"].show();
        },
        showModal() {
            this.$refs["my-modal"].show();
        },
        hideModal() {
            this.$refs["my-modal"].hide();
            this.resetItem();
        },
        resetItem() {
            this.editableItem = {
                id: null,

                serie: null,

                inventario: null,

                iccStatus: { id: null, name: null },

                company: { id: null, name: null },

                iccType: { id: null, name: null },

                linea: {
                    id: null,
                    dn: null,
                },

                comment: null,

                statusLinea: {
                    status: { id: null, name: null },
                    reason: null,
                },
            };
        },
        dataChanged() {
            this.$emit("data-changed");
        },
    },
    computed: {
        renderSimType: function () {
            if (this.can("full update stock") && this.editableItem.company) {
                return true;
            }
        },

        renderDn: function () {
            if (this.can("full update stock") && this.editableItem.linea.dn != null) {
                return true;
            }
        },
        renderRecarga: function () {
            if (
                this.can("full update stock") &&
                this.editableItem.statusLinea.status &&
                this.editableItem.statusLinea.status.name == "Recargable"
            ) {
                return true;
            }
        },
    },
};
</script>

<style></style>
