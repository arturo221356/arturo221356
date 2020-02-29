<template>

                   

                   
                              <select class='form-control' v-model='sucursal' @change='sucursalChange()'>
                              <option value='0' >Seleccionar Sucursal</option>
                              <option value='all' >Todas</option>
                              <option v-for='data in sucursales' :value='data.id'>{{ data.nombre_sucursal }}</option>
                            </select>
</template>

<script>
export default {
        mounted() {
            console.log('Component mounted.')
        },
        data(){
            return {
                sucursal: 0,
                sucursales: [],
            }
        },
        methods:{
            getSucursales: function(){
              axios.get('/get/sucursales')
              .then(function (response) {
                 this.sucursales= response.data;
              }.bind(this));
            },
            
                      sucursalChange: function() {
                axios.post('/admin/inventario/equipos',{
                 params: {
                   sucursal: 2
                 }
                 
              }).then(function (response) {
                 this.items= response.data.data;
              }.bind(this));
            }
            
            
            },
                created: function(){
            this.getSucursales()
        } 
        }
 
    
    


</script>
