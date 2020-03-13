<template>
  <b-container fluid>

  <nav class="navbar navbar-expand-lg navbar-light " style="background-color: #DEDEDE;">
      <b-row>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <a class="navbar-brand" href="#">{{navbarName}} {{actualSucursal}}</a>
        
        
        <ul class="navbar-nav m-auto mt-2 mt-lg-0">
            
            
            <radio-producto
              :user-role="userRole"
              v-on:producto="productoChange"
              v-on:fields="loadfields"

            >
            
            </radio-producto>
        
        
        </ul>
         
         
        
        <form class="form-inline my-2 my-lg-0 ml-2">
          
          <checkbox-status
            
            :producto="producto"
            v-on:status="statusChange"
          >
          </checkbox-status>
          
          <select-sucursal
            v-if="userRole == 'supervisor'||userRole =='admin'"
            v-on:sucursal="sucursalChange"
            
          ></select-sucursal>


          <input class="form-control mr-sm-2 search" type="text" placeholder="Buscar" aria-label="Search" id="filterInpt" v-model="filter">
          
        <export-excel
          :sucursal-id="sucursalid"

          :status-id="status"
        
          :producto="producto"
        ></export-excel>
        
        
        </form>
        
      </div>
    </b-row>
    </nav>




    
   
   



   
   
    <!-- Main table element -->
    <b-table
      id="my-table"
      show-empty
      responsive
      striped hover
      stacked="md"
      :items="items"
      :fields="fields"
      :filter="filter"
      :filterIncludedFields="filterOn"
      :sort-by.sync="sortBy"
      :sort-desc.sync="sortDesc"
      :sort-direction="sortDirection"
      @filtered="onFiltered"
      :busy="isBusy"
    >

      <!--busy template-->
      <template v-slot:table-busy>
        <div class="text-center text-primary my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Cargando...</strong>
        </div>
      </template>




      <!-- resultado template -->
      <template v-slot:table-caption>Resultado: - {{countItems}} </template>
      <template v-slot:cell(editar)="data">
        <!-- `data.value` is the value after formatted by the Formatter -->
        <b-button :href="`/admin/imei/${data.item.id}/edit`"> Editar</b-button>
      </template>


    </b-table>

    
 
  </b-container>
</template>

<script>
  export default {

  props: {
    
    userRole: {type: String, required: true},
    userSucursal:{type: String},
    //fields: { type: Array, required: true },
    navbarName: {type: String, required: true},
  },
    
    
    data() {
      return {
        
        producto:"",
        status:[],
        fields:[],
        fetchUrl:'',
        sucursalid:0,
        countItems: 0,
        items: [],
        totalRows: 0,
        sortBy: '',
        sortDesc: false,
        sortDirection: 'asc',
        filter: null,
        filterOn: [],
        actualSucursal: "",
        isBusy: false,
        
        

      }
    },




    computed: {
      sortOptions() {
        // Create an options list from our fields
        return this.fields
          .filter(f => f.sortable)
          .map(f => {
            return { text: f.label, value: f.key }
          })
      },
        rows() {
        return this.items.length
      },


      





    

    },
  watch:{





      },





    mounted() {
  
       if(this.userSucursal){
          
          console.log('loading datadfdf');
          this.sucursalid = this.userSucursal;
          this.loadData();
        }
    
    
    },
    methods: {
      
        loadData(){
          
          

        console.log(this.sucursal);

         this.isBusy = true;

          

         

         axios.post(this.fetchUrl,{
              
         
         
         sucursal_id: this.sucursalid,

         status: this.status,



     
              
          }).then(response => {
              
              
              
              this.items = response.data.data;
              
              this.countItems = this.items.length;


              this.isBusy = false;

             

              console.log(this.totalRows);

             



            
          })
        
        
        
        
          
          
          
          
         
          
          
         
         
        
        
        
        
        
        },
        
        
        
        sucursalChange(value) {
        
        
        
        this.sucursal = value;
        
        this.actualSucursal = this.sucursal.text;

        this.sucursalid = this.sucursal.id;

        
        this.loadData();
        
        

      },
      statusChange(value) {
        
        
        
        this.status = value;

        
        console.log(this.status);

       if(this.actualSucursal != ""){
          this.loadData();
        }
        

      },

      productoChange(value) {
        
        this.producto = value;

        
        if(value == 'equipos'){
          this.fetchUrl = '/get/imeis/';
          
        }
        else{
          this.fetchUrl ='';
          
        }
        
        

        

        if(this.actualSucursal != ""){
          this.loadData();
        }
      },

    loadfields(value) {
        
        
        
        this.fields = value;

      },







      
      onFiltered(filteredItems) {
        // Trigger pagination to update the number of buttons/pages due to filtering
        //this.totalRows = filteredItems.length

        console.log(this.filter);

        this.countItems = filteredItems.length;



        
        
      }
    
    },

    created(){

    }


  
  
  
  
  
  
  }
</script>
