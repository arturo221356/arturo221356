<template>
  <b-container fluid>

  <nav class="navbar navbar-expand-lg navbar-light " style="background-color: #DEDEDE;">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <a class="navbar-brand" href="#">{{navbarName}}  {{actualSucursal}}</a>
        
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
          @yield('TableNavbarButtons')
        </ul>
        
        <form class="form-inline my-2 my-lg-0">
          
          <select-sucursal
            v-on:sucursal="sucursalChange"
          
          ></select-sucursal>


          <input class="form-control mr-sm-2 search" type="text" placeholder="Search" aria-label="Search" id="filterInpt" v-model="filter">
          
        </form>
      </div>
    </nav>


    <!-- pagination  -->
    <b-pagination
      v-model="currentPage"
      
      :per-page="perPage"
      :total-rows="totalRows"
      aria-controls="my-table"
      prev-text="Atras"
      next-text="Siguiente"
      first-number
      last-number
      
      
     
    ></b-pagination>
    
   
   
    <p class="mt-3">Current Page: {{ currentPage }}</p>


   
   
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
      
      :current-page="currentPage"
      :busy="isBusy"
    >

      <!--busy template-->
      <template v-slot:table-busy>
        <div class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Cargando...</strong>
        </div>
      </template>




      <!-- resultado template -->
      <template v-slot:table-caption>Resultado: - {{totalRows}} {{product}} </template>
      <template v-slot:cell(editar)="data">
        <!-- `data.value` is the value after formatted by the Formatter -->
        <b-button :href="`/admin/imei/${data.item.id}/edit`"> Editar</b-button>
      </template>


    </b-table>

    <!-- Info modal -->

  </b-container>
</template>

<script>
  export default {

  props: {
    
    fetchUrl: { type: String, required: true },
    fields: { type: Array, required: true },
    navbarName: {type: String, required: true},
  },
    
    
    data() {
      return {
        
        product: '',
        countItems: 0,
        items: [],
        totalRows: 0,
        sortBy: '',
        sortDesc: false,
        sortDirection: 'asc',
        filter: null,
        filterOn: [],
        actualSucursal: "",
        perPage:0,
        currentPage: 1,
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
        currentPage:{
          handler: function(){
              this.loadData();
          }
          
        }



      },





    mounted() {
  

    
    
    },
    methods: {
      
        loadData(){
          
          

        console.log(this.sucursal);

         this.isBusy = true;

         

         axios.post(this.fetchUrl,{
              
            
         sucursal_id: this.sucursal.id,

         page: this.currentPage,

        

                

                
              
              
          }).then(response => {
              
              
              
              this.items = response.data.data;

              this.totalRows = response.data.meta.total;

              this.perPage = response.data.meta.per_page;

              



              this.isBusy = false;

             

              console.log(this.totalRows);

              console.log(this.currentPage);



            
          })
        
        
        
        
        
        
        
        
        },
        
        
        
        sucursalChange(value) {
        
        
        
        this.sucursal = value;
        
        this.actualSucursal = this.sucursal.text;

        this.currentPage = 1;

        this.loadData();

},





      
      onFiltered(filteredItems) {
        // Trigger pagination to update the number of buttons/pages due to filtering
        //this.totalRows = filteredItems.length
        
      }
    
    },


  
  
  
  
  
  
  }
</script>
