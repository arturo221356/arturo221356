<template>
        <!-- Info modal -->
    <b-modal 
      
      :id="infoModal.id" 
      
      :title="infoModal.title" 
       
      
      @hide="resetInfoModal"
      
      ref="modal"
      >
      
      <pre>{{ infoModal.content }}</pre>

        <form ref="form" @submit.stop.prevent="handleSubmit">
          <b-form-group
            
            label="Name"
            label-for="name-input"
            invalid-feedback="Name is required"
          >
           <select-sucursal>
           </select-sucursal>
          
          </b-form-group>
        </form>
    
        <!-- Footer del modal Botones -->
        
        <template v-slot:modal-footer="{ ok, cancel, hide }">
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


        }
    },
    
    
    methods:{
    
    //manda la informacion al modal
      info(item, index, button) {

        
        
        if(this.producto =='equipos'){
           this.infoModal.title = `Editar Imei: ${item.imei}`;
          
        }
        else if(this.producto =='sims'){
          this.infoModal.title = `Editar Icc: ${item.icc}`;
          
        }

        this.infoModal.itemId = item.id;
        this.infoModal.content = JSON.stringify(item, null, 2)
        this.$root.$emit('bv::show::modal', this.infoModal.id, button)
      },
      //resetea los valores del modal
      resetInfoModal() {
        this.infoModal.title = ''
        this.infoModal.content = ''
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






            