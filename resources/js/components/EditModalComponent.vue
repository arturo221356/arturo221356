<template>
        <!-- Info modal -->
    <b-modal 
      
      :id="infoModal.id" 
      
      :title="infoModal.title" 
       
      
      @hide="resetInfoModal"
      
      ref="modal"
      >
      
      <pre>{{ infoModal.content.id_sucursal }}</pre>


        <form ref="form" @submit.stop.prevent="handleSubmit">
          <b-form-group
            
            label="Sucursal"
            label-for="name-input"
            invalid-feedback="Name is required"
          >
           <select-sucursal
           :seleccionado="infoModal.content.id_sucursal"
           :todas="false"
           >
           </select-sucursal>
          
          </b-form-group>



          <b-form-group
            label="Estatus"

          > 

          <b-form-select  :options="options"></b-form-select>
          
          </b-form-group>



        </form>
    
        <!-- Footer del modal Botones -->
        
        <template v-slot:modal-footer="{ ok, cancel,  }">
        <b>Custom Footer</b>
        <!-- Emulate built in modal footer ok and cancel button actions -->
        <b-button size="sm" variant="success" @click="ok()">
          Guardar
        </b-button>

        <!-- Button with custom close trigger value -->
        <b-button :disabled="loading" size="sm" variant="outline-danger" @click="deleteItem(infoModal.itemId)">
          Eliminar
        </b-button>

        <b-button size="sm"  @click="cancel()">
          Cancelar
        </b-button>
      </template>

      <!-- Footer del modal Botones -->
    
    
    
    
    
    
    
    
    
    
    </b-modal>
</template>

<script>
export default {

    data(){
        return{
         
         loading : false,
         infoModal: {
            
            id: 'info-modal',
            title: '',
            content: '',
            itemId: '',
            
            },

          options: [
          { value: null, text: 'Please select an option' },
          { value: 'a', text: 'This is First option' },
          { value: 'b', text: 'Selected Option' },
          { value: { C: '3PO' }, text: 'This is an option with object value' },
          { value: 'd', text: 'This one is disabled', disabled: true }
        ],



        }
    },
    
    
    methods:{
    
    //manda la informacion al modal
      info(item, index,producto, button) {

        
        
        if(producto =='equipos'){
           this.infoModal.title = `Editar Imei: ${item.imei}`;
          
        }
        else if(producto =='sims'){
          this.infoModal.title = `Editar Icc: ${item.icc}`;
          
        }

        this.infoModal.itemId = item.id;
        this.infoModal.content = item;
        
        this.$root.$emit('bv::show::modal', this.infoModal.id, button)
      },
      //resetea los valores del modal
      resetInfoModal() {
        this.infoModal.title = ''
        this.infoModal.content = {}
        this.infoModal.itemId = ''
      },

     
     deleteItem(id){

        this.loading = true;

        axios.delete(`/admin/imei/${id}`)
          .then(()=>{

            alert('eliminado');

            

            this.$refs['modal'].hide();

            this.$parent.loadData();

            this.loading = false;

          })

      },

    },

}
</script>






            