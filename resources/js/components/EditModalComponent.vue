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
            v-if="producto == 'equipos'"
            label="Equipo"
          >
          <select-equipo
          
            :seleccionado="equipo"
            v-on:equipo="equipoChange"
          >
          </select-equipo>
            
          </b-form-group>
          
          
          
          
          
          <b-form-group
            
            label="Sucursal"
          
          >
           <select-sucursal
           :seleccionado="sucursal"
           :todas="false"
           v-on:sucursal="sucursalChange"
           >
           </select-sucursal
           >
          
          </b-form-group>



          <b-form-group
            label="Estatus"

          > 

          <select-status
          
          :seleccionado="status"
          
          >
          </select-status>
          
          </b-form-group>

          <div class="form-group">
            <label for="">Comentario</label>
            <!-- <input type="text" name="" id="" class="form-control" placeholder="" aria-describedby="helpId" :value="comentario"> -->
            <b-form-textarea  placeholder="Inserta un comentario" v-model.lazy="comentario"></b-form-textarea>
            <small id="helpId" class="text-muted">Eliminar comentario</small>
          </div>



        </form>
       
        <!-- Footer del modal Botones -->
        
        <template v-slot:modal-footer="{ ok, cancel,  }">
        <b>Custom Footer</b>
        <!-- Emulate built in modal footer ok and cancel button actions -->
        <b-button size="sm" variant="success" @click="updateItem(infoModal.itemId)">
          Guardar
        </b-button>

        <!-- Button with custom close trigger value -->
        <b-button  size="sm" variant="outline-danger" @click="deleteItem(infoModal.itemId)">
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
         
         
         infoModal: {
            
            id: 'info-modal',
            title: '',
            content: '',
            itemId: '',
            
            
            },

        comentario: "",

        sucursal: "",

        status: "",

        producto: "",

        equipo: {id:"",},

        fetchUrl:"",


        }
    },
    
    
    methods:{
    
    
    //manda la informacion al modal
      sucursalChange(value){

        this.sucursal = value;

        console.log(this.sucursal);

      },
      equipoChange(value){
        this.equipo = value;
        console.log(this.equipo);

        console.log(this.comentario);
      },

      
      info(item, index,producto, button) {

        this.$root.$emit('bv::show::modal', this.infoModal.id, button)
        
        if(producto =='equipos'){
           this.infoModal.title = `Editar Imei: ${item.imei}`;
          this.fetchUrl = "/admin/imei/";
          this.equipo = item.equipo_id;
        }
        else if(producto =='sims'){
          this.infoModal.title = `Editar Icc: ${item.icc}`;
          this.fetchUrl = "/admin/icc/";
        }

        this.infoModal.itemId = item.id;
        
        this.infoModal.content = item;

        if(item.comment){
        this.comentario = item.comment.comment;
        }

        this.status = item.status_id;

        this.sucursal = item.id_sucursal;

        this.producto = producto;

        

        

        
      },

      updateItem(id){
              
        const params = {
          sucursal_id: this.sucursal.id,
          equipo_id: this.equipo.id,
          comment: this.comentario,
        };

        axios.patch(this.fetchUrl+id,params).then(res=>{

            alert(res.data);

            this.$refs['modal'].hide();

            this.$parent.loadData();
        }).catch(function(error) {
         console.log(error);
      });

      },
     
     
     passwordValidation(){
              
        this.passwordValidation();

        
      },



      //resetea los valores del modal
      resetInfoModal() {
        this.infoModal.title = ''
        this.infoModal.content = {}
        this.infoModal.itemId = ''
        this.comentario = ''
        this.status = ''
        this.sucursal = ''
      },

     
     deleteItem(id){

       

        axios.delete(this.fetchUrl+id)
          .then(()=>{

            alert('eliminado');

            

            this.$refs['modal'].hide();

            this.$parent.loadData();

            

          })

      },

    },

}
</script>






            