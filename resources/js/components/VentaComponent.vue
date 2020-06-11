<template>
    <div>
        <b-navbar
            toggleable="lg"
            type="light"
            style="background-color: #dedede;"
        >
            <b-navbar-brand href="#">Ventas</b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav>
                    <b-nav-form inline>
                        <b-form-input
                            v-model="buscarProducto"
                            placeholder="Buscar Producto"
                            class="mb-2 mr-sm-2 mb-sm-0"
                            aria-describedby="password-help-block"
                            id="text-password"
                        ></b-form-input>

                        <b-button variant="success">Agregar</b-button>
                    </b-nav-form>
                </b-navbar-nav>

                <!-- Right aligned nav items -->
                <b-navbar-nav class="ml-auto">
                    <b-button  v-b-modal.modal-producto-general>Agregar venta general</b-button>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>
        <!-- cards -->
        <b-list-group>
            <b-list-group-item
                href="#"
                class="flex-column align-items-start"
                v-for="item in listItems"
                :key="item.id"
            >
                <div class="d-flex">
                    <div class="col-8  justify-content-start">
                        <h3 class="mb-1">{{ item.title }}</h3>

                            <h5 v-if="item.content.icc">ICC:  {{ item.content.icc }}</h5>
                       
                            <h5 v-if="item.content.dn">DN:  {{ item.content.dn }}</h5>

                            <h5 v-if="item.content.dn_temporal">DN TEMPORAL:  {{ item.content.dn_temporal}}</h5>
                        
                    </div>
                    <div class="col-4 row justify-content-end">
                        <small
                            ><b-button variant="danger">Eliminar</b-button>
                            <h5 class="mt-3">
                                Precio: ${{ item.precio }}
                            </h5></small
                        >
                    </div>
                </div>

              
            </b-list-group-item>


        </b-list-group>
        {{ totalVenta }}

        <!-- modal producto general -->
        <b-modal id="modal-producto-general" >
            <b-form>
                
                <!-- <b-form-input placeholder="Nombre" v-model="producto.nombre"></b-form-input>

                <b-form-input placeholder="Descripcion" v-model="producto.descripcion"></b-form-input>

                <b-form-input placeholder="precio" v-model="produco.precio"></b-form-input> -->
                
             <b-button type="submit" variant="primary">Agregar</b-button>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
export default {
    data() {
        return {
            buscarProducto: "",

           

            recargas:[],

            planes:[],

            portas:[],

            lineasNuevas:[],

            otros:[],

            generales:[],


            listItems: [
                {
                    title: "Linea Nueva",
                    content: {
                        dn: "3310512007",
                        dn_temporal: "3921106507",
                    },
                    precio: 99,
                },
                {
                    title: "Portabilidad",
                    content: {
                        icc: "89520384464646464454f"
                    },
                    precio: 20,
                },
                {
                    title: "Plan",
                    content: "2",
                    precio: 30,
                },
                {
                    title: "Recarga",
                    content: "2",
                    precio: 50,
                },
            ],
        };
    },
    computed: {
        totalVenta: function () {
            var sum = 0;
            this.listItems.forEach(function (item) {
                sum += item.precio;
            });

            return sum;
        },
    },
};
</script>

<style></style>
