<template>
   
 <div>         
    
    
      <b-form-checkbox-group
        switches 
        size="sm"
        v-model="selected"
        :options="options"
        @input='emitToParent'
       
      ></b-form-checkbox-group>
   

    

</div>
</template>
<script>
export default {

    props:['producto'],
    
    data() {
      return {
        selected: ['5'], // Must be an array reference!
        options: [],
       
        
      }
    },
    watch:{
        
        producto(){
        
        
        
         
           
        if (this.producto == 'equipos'){
          this.selected= ['5'],      
          this.options = [
          { text: 'Disponible' , value: '5'},
          { text: 'Garantia', value: '4' },
          { text: 'En Transito', value: '3' },
          { text: 'Perdido', value: '2' },
          { text: 'Incompleto', value: '6' },
        ]

        }else{
        
          this.selected= ['5'],
          this.options = [
          { text: 'Disponible' , value: '5'},
          { text: 'Perdido', value: '2' },
          { text: 'En Transito', value: '3' },
          { text: 'Bloqueado', value: '4' },
        ]
        }  
        
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
