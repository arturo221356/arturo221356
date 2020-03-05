    
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
        
        <span>Picked: {{ selected }}</span>
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
                
                emitToParent(){
                        
                        if(this.selected == 'equipos'){
                            
                            this.fields = 
                                        [{ key: 'id', label: '#', sortable: true, sortDirection: 'desc' },
                                        { key: 'imei', label: 'Imei', sortable: true, class: 'text-center' },
                                        { key: 'marca', label: 'Marca', sortable: true, class: 'text-center' },
                                        { key: 'modelo', label: 'Modelo', sortable: true, class: 'text-center' },
                                        { key: 'sucursal', label: 'Sucursal', sortable: true, class: 'text-center' },
                                        { key: 'status', label: 'Status', sortable: true, class: 'text-center' },
                                        { key: 'editar', label: 'Editar', class: 'text-center' },]


                        }
                        else {
                            this.fields = 
                                        [{ key: 'id', label: '#', sortable: true, sortDirection: 'desc' },
                                        { key: 'imei', label: 'ICCCCCCCC', sortable: true, class: 'text-center' },
                                        { key: 'marca', label: 'Marca', sortable: true, class: 'text-center' },
                                        { key: 'modelo', label: 'Modelo', sortable: true, class: 'text-center' },
                                        { key: 'sucursal', label: 'Sucursal', sortable: true, class: 'text-center' },
                                        { key: 'status', label: 'Status', sortable: true, class: 'text-center' },
                                        { key: 'editar', label: 'Editar', class: 'text-center' },];
                        }
                        
                        this.$emit('producto', this.selected)

                        this.$emit('fields', this.fields)
                },



            },
            created: function(){
                    this.emitToParent();
                } 





        }
    </script>