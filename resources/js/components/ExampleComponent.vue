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
          
          <select class='form-control' v-model='sucursal' @change='sucursalChange()'>
                <option value='0' >Seleccionar Sucursal</option>
                <option value='all' >Todas</option>
                <option v-for='data in sucursales' :value="{ id: data.id, text: data.nombre_sucursal }" :key='data'>{{ data.nombre_sucursal }} </option>
          </select>


          <input class="form-control mr-sm-2 search" type="text" placeholder="Search" aria-label="Search" id="filterInpt" v-model="filter">
          
        </form>
      </div>
    </nav>


    <!-- Main table element -->
    <b-table
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
    >

      <template v-slot:table-caption>Aqui sirve para contar.</template>
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
        sucursal: 0,
        sucursales: [],
        items: [],
       
        totalRows: 1,
        sortBy: '',
        sortDesc: false,
        sortDirection: 'asc',
        filter: null,
        filterOn: [],
        actualSucursal: "",

      }
    },
      created(){

    
      this.getSucursales()
    
    },



    computed: {
      sortOptions() {
        // Create an options list from our fields
        return this.fields
          .filter(f => f.sortable)
          .map(f => {
            return { text: f.label, value: f.key }
          })
      }
    },
    mounted() {
      // Set the initial number of items
    //   this.totalRows = this.items.length
    },
    methods: {
      
      getSucursales: function(){
        axios.get('/get/sucursales')
        .then(function (response) {
            this.sucursales= response.data;
        }.bind(this));
      },

      sucursalChange: function() {
           
           this.actualSucursal = this.sucursal.text;


                axios.post(this.fetchUrl,{
                 
                   sucursal_id: this.sucursal.id
                 
                 
              }).then(function (response) {
                 this.items = response.data.data;
                
              }.bind(this));
            },



      
      onFiltered(filteredItems) {
        // Trigger pagination to update the number of buttons/pages due to filtering
        this.totalRows = filteredItems.length
        this.currentPage = 1
      }
    
    },


  
  
  
  
  
  
  }
</script>
