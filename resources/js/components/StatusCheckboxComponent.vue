<template>
   
 <div>         
    
    
      <b-form-checkbox-group
        switches 
        size="sm"
        v-model="selected"
        :options="options"
        @input="emitToParent"
        value-field="id"
        text-field="status"
       
      ></b-form-checkbox-group>
   

    

</div>
</template>
<script>
export default {

    props:['producto'],
    
    data() {
      return {
        selected: ['1'], // Must be an array reference!
        options: [],
        allSelected: false,
       
        
      }
    },
    watch:{
        selected(newVal, oldVal){


        if (newVal.length === 7) {
         
          this.allSelected = false
        }

            

        },
        producto(){
        
        

        
            
            
        axios.get('/get/status')
        .then(function (response) {
           
           
           this.selected = this.seleccionado;
           
           this.options= response.data;

           this.selected = ['1'];  

            
        }.bind(this));




       

        
        
 
        
        }
    },
    methods:{



                
    emitToParent(){
        
        
        this.$emit('status', this.selected);
                }
                
                
            
            },

    created: function(){
                    this.emitToParent();
                    
                }

    


    
  





}
</script>
