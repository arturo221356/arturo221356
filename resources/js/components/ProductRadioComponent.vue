<template>
  <div>
      <b-form-radio-group
        id="btn-radios-1"
        v-model="selected"
        :options="options"
        buttons
        name="radios-btn-default"
       
       
      ></b-form-radio-group>
        

   </div>
</template>
<script>
export default {
   
       

            data() {
                
                return {
                    
                    selected: 'equipos',
                    options: [
                    
                    { text: 'Equipos', value: 'equipos' },
                    { text: 'Sims', value: 'sims' },
                    { text: 'Prom', value: 'radio3', disabled: true },
                    ],
                    

                    fields:[],
                
                
                
                
                }
            },
            
            methods:{
                

                



            },


     } 





}

</script>