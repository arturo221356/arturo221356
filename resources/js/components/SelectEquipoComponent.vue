<template>
        <select v-model="selected" name="equipo" id="equipo" class="form-control">

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
        

        selected:null,

        }

    },
     
     created: function(){
            
            
         axios.get('/get/equipos')
        .then(function (response) {
           
           
           this.selected = this.seleccionado;
           
           this.equipos= response.data;

            
        }.bind(this));




        }    
}
</script>