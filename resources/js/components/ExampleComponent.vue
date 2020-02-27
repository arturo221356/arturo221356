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
      axios.post('/admin/inventario/equipos',{
        sucursal: 2,
      }).then(res=>{
        this.items = res.data.data;
      })
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
      info(item, index, button) {
        // this.infoModal.title = `Row index: ${index}`
        // this.infoModal.content = JSON.stringify(item, null, 2)
        // this.$root.$emit('bv::show::modal', this.infoModal.id, button)
      },
      resetInfoModal() {
        // this.infoModal.title = ''
        // this.infoModal.content = ''
      },
      onFiltered(filteredItems) {
        // Trigger pagination to update the number of buttons/pages due to filtering
        this.totalRows = filteredItems.length
        this.currentPage = 1
      }
    }
  

  
  
  
  
  
  
  }
</script>
