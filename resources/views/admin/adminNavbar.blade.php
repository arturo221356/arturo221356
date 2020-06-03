                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
                          <a class="nav-link" href="/admin">Inicio</a>
                        </li>
                        
                        
                        <li class="nav-item dropdown {{ Request::is('inventario') ? 'active' : '' }}">
                          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Inventario
                          </a>
                          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/inventario/">Reporte de Inventario</a>
                            <a class="dropdown-item" href="/admin/inventario/cargar">Cargar Inventario</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Productos</a>
                          </div>
                        </li>
                  
                        <li class="nav-item dropdown {{ Request::is('reportes') ? 'active' : '' }}">
                          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Reportes
                          </a>
                          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/admin/reportes/ventas">Reporte de ventas</a>
                            <a class="dropdown-item" href="/admin/reportes/activaciones/">Reporte de Activaciones</a>
                            <a class="dropdown-item" href="/admin/reportes/activaciones/">Reporte de Equipos</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Productos</a>
                          </div>
                        </li>
                  
                  
                        {{-- <li class="nav-item dropdown {{ Request::is('sucursales') ? 'active' : '' }}">
                          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Sucursales
                          </a>
                          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/admin/sucursales">Lista de Sucursales</a>
                            <a class="dropdown-item" href="/admin/sucursales/create">Nueva Sucursal</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Productos</a>
                          </div>
                        </li> --}}
                  
                        <li class="nav-item  dropdown {{ Request::is('sucursales') ? 'active' : '' }}">
                          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Distribucion
                          </a>
                          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/admin/users"  >
                              Usuarios
                            </a>
                            <a class="dropdown-item" href="/admin/sucursales"  >
                              Sucursales
                            </a>
                          </div>
                          </li>

                          <li class="nav-item dropdown {{ Request::is('sucursales') ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              Productos
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                              <a class="dropdown-item" href="/admin/productos/sims">Sims</a>
                              <a class="dropdown-item" href="/admin/productos/equipos">Equipos</a>
                              <a class="dropdown-item" href="/admin/productos/recargas">Recargas</a>
                              <a class="dropdown-item" href="/admin/users/create">Promocionales</a>

                            </div>
                          </li>
                  
                      </ul>
                      <form class="form-inline my-2 my-lg-0">
                        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                      </form>

                </div>