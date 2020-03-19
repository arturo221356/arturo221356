<template>
  <div>
   
   
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
          
             
             <input class="form-control mr-sm-2 search" type="text" placeholder="Buscar" aria-label="Search" id="filterInpt" v-model="filter">
          

        
        
        </form>
        
      </div>
    </b-row>
    </nav>   
    

    
    
    
    
    
    
    
    <b-table striped hover :items="items"></b-table>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        items: [
          { age: 40, first_name: 'Dickerson', last_name: 'Macdonald' },
          { age: 21, first_name: 'Larsen', last_name: 'Shaw' },
          { age: 89, first_name: 'Geneva', last_name: 'Wilson' },
          { age: 38, first_name: 'Jami', last_name: 'Carney' }
        ]
      }
    }
  }
</script>