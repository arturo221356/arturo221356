<template>
    <div>
        <b-modal
            ref="my-modal"
            hide-footer
            title="Editar cantidad de accesorios"
            @hidden="resetItem"
        >
            <b-overlay :show="isLoading" rounded="sm">
                <validation-observer ref="observer" v-slot="{ handleSubmit }">
                    <b-form @submit.prevent="handleSubmit(updateItem)">
                        <div class="mb-4">
                            <h4>{{editableItem.name}}  {{editableItem.description}}</h4>
                        </div>
                        

                        <ValidationProvider
                            v-slot="validationContext"
                            rules="required|numeric"
                        >
                            <b-form-group label="Cantidad" label-size="lg">
                                <b-form-input
                                    autocomplete="off"
                                    v-model="editableItem.cantidad"
                                    type="number"
                                    :state="
                                        getValidationState(validationContext)
                                    "
                                    placeholder="Ingresa la cantidad"
                                ></b-form-input>

                                <b-form-invalid-feedback
                                    ><b-form-invalid-feedback>{{
                                        validationContext.errors[0]
                                    }}</b-form-invalid-feedback></b-form-invalid-feedback
                                >
                            </b-form-group>
                        </ValidationProvider>

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
                description: null,
                cantidad: null,
                name: null,
                inventario_id: null,
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
                cantidad: this.editableItem.cantidad,

                inventario_id: this.editableItem.inventario_id,

                id: this.editableItem.id,
            };

            axios
                .post(`/update/otros`, data)
                .then((response) => {
                    this.isLoading = false;

                    this.$bvToast.toast("Actualizado con exito", {
                        title: `${this.editableItem.name} nueva cantidad ${this.editableItem.cantidad}`,
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
                    this.isLoading = false;
                });
        },
        deleteItem() {
            this.isLoading = true;
            axios

                .post(`/delete/otros`, {id: this.editableItem.id, inventario_id: this.editableItem.inventario_id})
                .then((response) => {
                    this.isLoading = false;

                    this.$bvToast.toast("Eliminado con exito", {
                        title: `Accesorios  Eliminados`,
                        variant: "danger",
                        solid: true,
                    });
                    this.dataChanged();

                    this.hideModal();
                })
                .catch((error) => {
                    console.log(error);
                    alert(error);
                    this.isLoading = false;
                });
        },

        editAccesorio(item, row) {
            console.log(item);

            this.editableItem.id = item.id;

            this.editableItem.name = item.name;

            this.editableItem.description = item.description;

            this.editableItem.cantidad = item.pivot.stock;

            this.editableItem.inventario_id = item.pivot.inventario_id;

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
                
                name: null,

                description: null,

                cantidad: null,

                inventario_id: null,
            };
        },
        dataChanged() {
            this.$emit("data-changed");
        },
    },
    computed: {},
};
</script>

<style></style>
