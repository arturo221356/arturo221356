<template>
  <b-button variant="success" :href="exportUrl">Excel</b-button>  
</template>
<script>
export default {
  
 props:['sucursal-id','status-id'],
  
  data(){
    
    return{
    
      exportUrl : '',
      


    }
  },
  
  methods:{

    urlBuilder(status, sucursal ){
            
    

    this.exportUrl = '/export/imeis?sucursal_id='+sucursal+'&status_id='+status;
    
    console.log('cambio sucursal');



}


  },

  watch:{
  sucursalId(){

    this.urlBuilder(this.statusId, this.sucursalId);
    
    },

   statusId(){

    this.urlBuilder(this.statusId, this.sucursalId);
    
    }
  
  
  
  }
}

</script>