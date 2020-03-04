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



    <b-pagination
      v-model="currentPage"
      :total-rows="totalRows"
      :per-page="perPage"
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
      :per-page="perPage"
      :current-page="currentPage"
      :busy="isBusy"
    >

      <template v-slot:table-busy>
        <div class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Cargando...</strong>
        </div>
      </template>





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
        totalRows: 1,
        sortBy: '',
        sortDesc: false,
        sortDirection: 'asc',
        filter: null,
        filterOn: [],
        actualSucursal: "",
        perPage: 150,
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
      }


    },
    mounted() {
      // Set the initial number of items
    //   this.totalRows = this.items.length
    },
    methods: {
      
        loadData(){

          




        },
        
        
        
        sucursalChange(value) {
        
        
        
        this.sucursal = value;

        console.log(this.sucursal);

        this.isBusy = true;
        
        
        this.actualSucursal = this.sucursal.text;



            axios.post(this.fetchUrl,{
              
            
            sucursal_id: this.sucursal.id,

                

                
              
              
          }).then(response => {
              this.items = response.data.data;

              this.totalRows = response.data.meta.total;

              this.isBusy = false;

             

              console.log(this.totalRows);

              if(this.totalRows == 1){
                this.product = 'Equipo';
              }
              else{
                this.product = 'Equipos'
              }


            
          })
        

        
        
        
        
        
        
        },





      
      onFiltered(filteredItems) {
        // Trigger pagination to update the number of buttons/pages due to filtering
        this.totalRows = filteredItems.length
        this.currentPage = 1
      }
    
    },


  
  
  
  
  
  
  }
</script>
