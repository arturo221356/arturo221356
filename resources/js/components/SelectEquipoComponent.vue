<template>
        <select v-model="selected"  class="form-control" @change='emitToParent'>

        <option v-for="(equipo) in equipos" :key="equipo.id" :value="equipo.id">{{ equipo.marca }}-{{equipo.modelo}}  ${{equipo.precio}}</option>

        </select>
</template>
<script>
export default {
    props:{

        seleccionado:"",

    },
    
    data(){
        return{
            
        equipos: [],
        
        equipo: {id:0},

        selected:null,

        }

    },
     
     created: function(){
            
            
         axios.get('/get/equipos')
        .then(function (response) {
           
           
           this.selected = this.seleccionado;
           
           this.equipos= response.data;

            
        }.bind(this));




        },
    methods:{


      emitToParent (event) {
      

      this.equipo.id = this.selected;



      
      
      this.$emit('equipo', this.equipo);
      
      
      }
    }    
}
</script>