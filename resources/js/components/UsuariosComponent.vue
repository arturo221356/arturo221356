<template>
  <div>
      
                  <b-navbar
                toggleable="lg"
                type="light"
                style="background-color: #dedede;"
            >
                <b-navbar-brand href="#">Usuarios</b-navbar-brand>

                <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

                <b-collapse id="nav-collapse" is-nav>
                    <b-navbar-nav>
                       <a>Agregar usuario</a>
                    </b-navbar-nav>

                    <!-- Right aligned nav items -->
                    <b-navbar-nav class="ml-auto"> 
                         <b-form-input type="search"></b-form-input>

                        
                    </b-navbar-nav>
                 

                </b-collapse>
            </b-navbar>
  </div>
</template>

<script>
export default {

}
</script>

