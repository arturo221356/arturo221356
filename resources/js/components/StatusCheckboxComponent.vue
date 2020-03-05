<template>
   
 <div>         
            <b-form-checkbox-group
                v-model="selected"
                :options="options"
                name="buttons-1"
                buttons
            ></b-form-checkbox-group>
            
{{producto}}
</div>
</template>
<script>
export default {

    props:['producto'],
    
    data() {
      return {
        selected: [], // Must be an array reference!
        options: [],
       
        
      }
    },
    watch:{
        
        producto(){
        
        
        
         
           
        if (this.producto == 'equipos'){
                
          this.options = [
          { text: 'this.producto' , value: this.producto},
          { text: 'Apple', value: 'apple' },
          { text: 'Pineapple', value: 'pineapple' },
          { text: 'Grape', value: 'grape' }
        ]

        }else{
        
        
          this.options = [
          { text: 'otro' , value: this.producto },
          { text: 'Apple', value: 'apple' },
          { text: 'Pineapple', value: 'pineapple' },
          { text: 'Grape', value: 'grape' }
        ]
        }  
        
        }
    },

    


    
  





}
</script>
