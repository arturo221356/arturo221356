<template>
    <div>
        <b-modal
            ref="my-modal"
            hide-footer
            :title="`Imei: ${editableItem.serie}`"
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
                                    query="App\Sucursal"
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
                                    v-model="editableItem.status"
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
                            name="equipo"
                            v-slot="validationContext"
                            rules="required"
                            v-if="can('full update stock')"
                        >
                            <b-form-group label="Equipo:" label-size="lg">
                                <select-general
                                    url="/get/equipos"
                                    pholder="Seleccionar Equipo"
                                    v-model="editableItem.equipo"
                                    :state="
                                        getValidationState(validationContext)
                                    "
                                    :equipo="true"
                                >
                                </select-general>
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

                equipo: { marca: null, modelo: null, id: null },

                status: null,

                comment: null,

            },

            isLoading: false,

        };
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },

        updateItem() {
            this.isLoading = true;

            var data = {
                comment: this.editableItem.comment,

                inventario_id: this.editableItem.inventario.id,

                status: this.editableItem.status.name,

                equipo_id :this.editableItem.equipo.id

            };


            axios
                .put(`/imei/${this.editableItem.id}`, data)
                .then((response) => {
                    this.isLoading = false;

                    this.$bvToast.toast("Actualizado con exito", {
                        title: `Imei ${this.editableItem.serie}`,
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

                .delete(`/imei/${this.editableItem.id}`, {})
                .then((response) => {
                    this.isLoading = false;

                    this.$bvToast.toast("Eliminado con exito", {
                        title: `Imei  ${this.editableItem.serie}`,
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

        editImei(item, row) {
            console.log(item);

            this.editableItem.id = item.id;

            this.editableItem.serie = item.serie;

            this.editableItem.comment = item.comment;

            this.editableItem.equipo = item.equipo;

            this.editableItem.status = {
                id: item.status,
                name: item.status,
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

                equipo: null,


                comment: null,
            };
        },
        dataChanged() {
            this.$emit("data-changed");
        },
    },
    computed: {

    },
};
</script>

<style></style>