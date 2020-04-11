<template>
  <div>
      <b-form-radio-group
        id="btn-radios-1"
        v-model="selected"
        :options="options"
        buttons
        name="radios-btn-default"
       
        @input='emitToParent'
      ></b-form-radio-group>
        

   </div>
</template>
<script>
export default {
   
       
            props:{
                userRole: {type: String, required: true},
                busy: {type:Boolean}

            },
            
            data() {
                return {
                    selected: 'equipos',
                    options: [],
                    

                    fields:[],
                
                
                
                
                }
            },
            
            methods:{
                
                emitToParent(){
                        
                        if(this.selected == 'equipos'){
                            
                                    
                                    switch(this.userRole){
                                            
                                            case 'admin': 
                                                
                                                this.fields = 
                                                [{ key: 'id', label: '#', sortable: true, sortDirection: 'desc' },
                                                { key: 'imei', label: 'Imei', sortable: true, class: 'text-center' },
                                                { key: 'marca', label: 'Marca', sortable: true, class: 'text-center' },
                                                { key: 'modelo', label: 'Modelo', sortable: true, class: 'text-center' },
                                                { key: 'precio', label: 'Precio', sortable: true, class: 'text-center' },
                                                { key: 'costo', label: 'Costo', sortable: true, class: 'text-center' },
                                                { key: 'sucursal', label: 'Sucursal', sortable: true, class: 'text-center' },
                                                { key: 'status', label: 'Status', sortable: true, class: 'text-center' },
                                                { key: 'created_at', label: 'Agregado', sortable: true, class: 'text-center' },
                                                { key: 'updated_at', label: 'Ultimo cambio', sortable: true, class: 'text-center' },
                                                { key: 'editar', label: 'Editar', class: 'text-center' },]
                                            
                                            break;

                                            case 'supervisor':
                                                
                                                this.fields = 
                                                [{ key: 'id', label: '#', sortable: true, sortDirection: 'desc' },
                                                { key: 'imei', label: 'Imei', sortable: true, class: 'text-center' },
                                                { key: 'marca', label: 'Marca', sortable: true, class: 'text-center' },
                                                { key: 'modelo', label: 'Modelo', sortable: true, class: 'text-center' },
                                                { key: 'precio', label: 'Precio', sortable: true, class: 'text-center' },
                                                { key: 'sucursal', label: 'Sucursal', sortable: true, class: 'text-center' },
                                                { key: 'status', label: 'Status', sortable: true, class: 'text-center' },
                                                { key: 'created_at', label: 'Agregado', sortable: true, class: 'text-center' },
                                                { key: 'updated_at', label: 'Ultimo cambio', sortable: true, class: 'text-center' },
                                                ]
                                                
                                            
                                            break;

                                            case 'seller':
                                                
                                                this.fields = 
                                                [{ key: 'id', label: '#', sortable: true, sortDirection: 'desc' },
                                                { key: 'imei', label: 'Imei', sortable: true, class: 'text-center' },
                                                { key: 'marca', label: 'Marca', sortable: true, class: 'text-center' },
                                                { key: 'modelo', label: 'Modelo', sortable: true, class: 'text-center' },
                                                { key: 'precio', label: 'Precio', sortable: true, class: 'text-center' },
                                                { key: 'status', label: 'Status', sortable: true, class: 'text-center' },
                                                { key: 'updated_at', label: 'Ultimo cambio', sortable: true, class: 'text-center' },
                                                ]
                                            
                                            
                                            
                                            
                                            
                                            
                                            break;    



                                            default:
                                                
                                                this.fields = []
                                                



                                    }
                        
                        }
                        else if(this.selected == 'sims'){
                            
                             switch(this.userRole){

                                 case'admin':
                            
                                    this.fields = 
                                        [{ key: 'id', label: '#', sortable: true, sortDirection: 'desc' },
                                        { key: 'icc', label: 'Icc', sortable: true, class: 'text-center' },
                                        { key: 'sucursal', label: 'Sucursal', sortable: true, class: 'text-center' },
                                        { key: 'status', label: 'Status', sortable: true, class: 'text-center' },
                                        { key: 'created_at', label: 'Agregado', sortable: true, class: 'text-center' },
                                        { key: 'updated_at', label: 'Ultimo cambio', sortable: true, class: 'text-center' },
                                        { key: 'editar', label: 'Editar', class: 'text-center' },]
                                
                                break;

                                case 'supervisor':

                                    this.fields = 
                                        
                                        [{ key: 'id', label: '#', sortable: true, sortDirection: 'desc' },
                                        { key: 'icc', label: 'Icc', sortable: true, class: 'text-center' },
                                        { key: 'sucursal', label: 'Sucursal', sortable: true, class: 'text-center' },
                                        { key: 'status', label: 'Status', sortable: true, class: 'text-center' },
                                        { key: 'created_at', label: 'Agregado', sortable: true, class: 'text-center' },
                                        { key: 'updated_at', label: 'Ultimo cambio', sortable: true, class: 'text-center' },]
                                    
                                break;

                                case 'seller':

                                    this.fields =

                                        [{ key: 'id', label: '#', sortable: true, sortDirection: 'desc' },
                                        { key: 'icc', label: 'Icc', sortable: true, class: 'text-center' },
                                        { key: 'status', label: 'Status', sortable: true, class: 'text-center' },
                                        { key: 'created_at', label: 'Agregado', sortable: true, class: 'text-center' },
                                        { key: 'updated_at', label: 'Ultimo cambio', sortable: true, class: 'text-center' },]
                                        
                                break;
                                
                                default:
                                                
                                    this.fields = []



                             }
                                

                        }
                        
                        this.$emit('producto', this.selected)

                        this.$emit('fields', this.fields)
                },



            },
            created: function(){
                    this.emitToParent();


                }, 
            watch:{

                busy(){

                    this.options = [
                    { text: 'Equipos', value: 'equipos', disabled: this.busy },
                    { text: 'Sims', value: 'sims', disabled: this.busy},
                    { text: 'Otros', value: 'otros', disabled: true },
                    ]

                }


            }





}

</script>