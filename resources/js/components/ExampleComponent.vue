<template>
  <b-container fluid>

  <nav class="navbar navbar-expand-lg navbar-light " style="background-color: #DEDEDE;">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <a class="navbar-brand" href="#">@yield('TableNavbarName')</a>
        
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
          @yield('TableNavbarButtons')
        </ul>
        
        <form class="form-inline my-2 my-lg-0">
          
          <select class='form-control' v-model='sucursal' @change='sucursalChange()'>
                <option value='0' >Seleccionar Sucursal</option>
                <option value='all' >Todas</option>
                <option v-for='data in sucursales' :value='data.id'>{{ data.nombre_sucursal }}</option>
          </select>


          <input class="form-control mr-sm-2 search" type="text" placeholder="Search" aria-label="Search" id="filterInpt" v-model="filter">
          
        </form>
      </div>
    </nav>


    <!-- Main table element -->
    <b-table
      show-empty
      small
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



    </b-table>

    <!-- Info modal -->

  </b-container>
</template>

<script>
  export default {
    

    
    
    data() {
      return {
        sucursal: 0,
        sucursales: [],
        items: [],
        fields: [
          { key: 'id', label: '#', sortable: true, sortDirection: 'desc' },
          { key: 'imei', label: 'Imei', sortable: true, class: 'text-center' },
          { key: 'marca', label: 'Marca', sortable: true, class: 'text-center' },
          { key: 'modelo', label: 'Modelo', sortable: true, class: 'text-center' },
          { key: 'sucursal', label: 'Sucursal', sortable: true, class: 'text-center' },
          { key: 'status', label: 'Status', sortable: true, class: 'text-center' },

        ],
        totalRows: 1,
        sortBy: '',
        sortDesc: false,
        sortDirection: 'asc',
        filter: null,
        filterOn: [],

      }
    },
      created(){
      // axios.post('/admin/inventario/equipos',{
      //   sucursal_id: 1,
      // }).then(res=>{
      //   this.items = res.data.data;
      // })
    
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
                axios.post('/admin/inventario/equipos',{
                 
                   sucursal_id: this.sucursal
                 
                 
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
        //     created: function(){
        //     
        // }

  
  
  
  
  
  
  }
</script>
