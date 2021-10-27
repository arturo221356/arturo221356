<template>
    <div>
        <b-modal
            id="accesorios-modal"
            hide-footer
            title="Accesorios"
            @hide="closedModal"
        >
            <b-form-group label="Buscar Accesorio" label-size="lg">
                <select-general
                    url="/get/otros"
                    pholder="Seleccionar Accesorio"
                    v-model="accesorio"
                    query="filtrado"
                    :accesorio="true"
                >
                </select-general>
            </b-form-group>
            <div class="mt-4">
                <b-button block @click="checkStock">Agregar</b-button>
            </div>
        </b-modal>
    </div>
</template>

<script>
export default {
    props: ["productos"],
    data() {
        return {
            accesorio: null,


        };
    },
    methods: {
        open() {
            this.$root.$emit("bv::show::modal", "accesorios-modal");
        },
        close() {
            this.$root.$emit("bv::hide::modal", "accesorios-modal");
        },
        closedModal() {
            this.accesorio = null;
        },
        checkStock() {

            var counter = 1;

           this.productos.forEach(element => {
               if(element.id == this.accesorio.id && element.type == "accesorios") counter ++;       
           });
           console.log(counter);

            
            axios
                .post("/otros/check-stock", {
                    id: this.accesorio.id,
                    counter: counter,
                })
                .then((response) => {
                    if (response.data == true) {
                        this.addAccesorio();
                    } else {
                        alert("No hay suficientes en inventario");
                    }
                })
                .catch((error) => {
                    alert(error);
                });
            console.log(counter);
        },
        addAccesorio() {
            const accesorio = {
                id: this.accesorio.id,
                serie: this.accesorio.name,
                precio: this.accesorio.precio,
                descripcion: this.accesorio.description,
                type: "accesorios",
            };

            this.$emit("add-accesorio", { ...accesorio });

            this.close();
        },
    },
};
</script>

<style></style>
