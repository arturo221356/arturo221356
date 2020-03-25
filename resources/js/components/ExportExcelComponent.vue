<template>
  <b-button variant="success" :href="exportUrl" target="_blank">Excel</b-button>  
</template>
<script>
export default {
  
 props:['sucursal-id','status-id','producto'],
  
  data(){
    
    return{
    
      exportUrl : '',
      statusArray: '',
      productoRoute:'',
      i: 0,
      


    }
  },
  
  methods:{

    urlBuilder(status, sucursal ){
    
     this.i = 0;

     this.statusArray = '';

     
     
     
     if(this.producto == 'equipos'){

     this.productoRoute = '/export/imeis?sucursal_id=';
     
     this.i = 0;

     this.statusArray = '';
     
     status.forEach(element => {

       

       this.statusArray += '&status_id['+this.i+']='+element;

       this.i ++ ;

       console.log(this.statusArray);
     }

     
     
     );  



    this.exportUrl = this.productoRoute+sucursal+this.statusArray;
    
    




     }
     else if(this.producto == 'sims'){
      
      
    this.productoRoute = '/export/iccs?sucursal_id=';
     
    this.i = 0;

    this.statusArray = '';
     
    status.forEach(element => {

       

       this.statusArray += '&status_id['+this.i+']='+element;

       this.i ++ ;

       
     }

     
     
     );  



    this.exportUrl = this.productoRoute+sucursal+this.statusArray;

    console.log(this.exportUrl);


     }

}


  },

  watch:{
  sucursalId(){

    this.urlBuilder(this.statusId, this.sucursalId);
    
    },

   statusId(){

    this.urlBuilder(this.statusId, this.sucursalId);
    
    },

  
  
  
  }
}

</script>