<template>
    <b-form-select 
    v-model="selected" 
    
    :options="status"
              
    value-field="id"

    text-field="status"

    
    
    ></b-form-select>
</template>
<script>
export default {
    
    props:{

        seleccionado:"",

    },
    
    data(){
        return{
            
        status: [],
        

        selected:null,

        }

    },



     created: function(){
            
            
         axios.get('/get/status')
        .then(function (response) {
           
           
           this.selected = this.seleccionado;
           
           this.status= response.data;

            
        }.bind(this));




        }
}
</script>