<template>
    <b-form-select 
    v-model="selected" 
    
    :options="options"
              
    value-field="id"

    text-field="status"

    @change='emitToParent'
    
    ></b-form-select>
</template>
<script>
export default {
    
    props:{

        seleccionado:"",

    },
    
    data(){
        return{
            
        options: [],
        

        selected:null,

        status: 0,

        }

    },



     created: function(){
            
            
         axios.get('/get/status')
        .then(function (response) {
           
           
           this.selected = this.seleccionado;
           
           this.options= response.data;

            
        }.bind(this));




        },
    methods:{



    emitToParent (event) {
      

      this.status = this.selected;



      
      
      this.$emit('status', this.status);
      
      
      }
    }
}
</script>