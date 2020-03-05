<template>

                   

                   
          <select class='form-control mr-3' v-model='sucursal' @change='emitToParent'>
                <option value="0"  selected>Seleccionar Sucursal</option>
                <option :value="{id:'all', text:'Todas'}" >Todas</option>
                <option v-for='data in sucursales' :value="{ id: data.id, text: data.nombre_sucursal }" :key="data.id" >{{ data.nombre_sucursal }} </option>
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


    emitToParent (event) {
      this.$emit('sucursal', this.sucursal)
    }






            },
      created: function(){
            this.getSucursales();
        } 
     }
 
    
    


</script>
