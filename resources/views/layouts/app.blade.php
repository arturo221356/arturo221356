<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}" defer></script>

  <script type="text/javascript">
    window.Laravel = {
           csrfToken: "{{ csrf_token() }}",
          jsPermissions: {!! auth()->check()?auth()->user()->jsPermissions():null !!}
      }
  </script>

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>

  <div id="app">
    <nav class="navbar navbar-expand-xl navbar-dark bg-dark">
      <a class="navbar-brand" href="{{ url('/home') }}">{{ config('app.name', 'Laravel') }}</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
  
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        @auth
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="{{ url('/home') }}">Inicio <span class="sr-only">(current)</span></a>
          </li>
  
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
              Ventas
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
  
              @role('vendedor')
              <a class="dropdown-item" href="/ventas/create">Nueva Venta</a>
              <div class="dropdown-divider"></div>
              @endrole
  
              <a class="dropdown-item" href="/ventas">Reporte</a>
  
  
            </div>
          </li>
          <li class="nav-item dropdown {{ Request::is('inventario') ? 'active' : '' }}">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
              Inventario
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="/inventario">Reporte de Inventario</a>
  
              @can ('store stock')
              <a class="dropdown-item" href="/inventario/cargar">Cargar Inventario</a>
              @endcan
  
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="/inventario/traspasos"> Traspasos</a>
  
  
            </div>
          </li>
          @can('ver cuentas')
          <li class="nav-item">
            <a class="nav-link" href="/cuentas">Cuentas</a>
          </li>
          @endcan
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
              Lineas
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              @can('preactivar masivo')
              <a class="dropdown-item" href="/linea/preactivar">Preactivar</a>
  
              <div class="dropdown-divider"></div>
              @endcan
              <a class="dropdown-item" href="/linea/reporte">Reporte</a>
  
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
              Equipos
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              @can('create equipo')
              <a class="dropdown-item" href="/equipos">Lista</a>
  
              <div class="dropdown-divider"></div>
              @endcan
              <a class="dropdown-item " href="/equipo/reporte">Reporte</a>
  
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
              Accesorios
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              @can('create equipo')
              <a class="dropdown-item" href="/otros">Lista</a>
  
              <div class="dropdown-divider"></div>
              @endcan
              <a class="dropdown-item " href="/reporte/otros">Reporte</a>
  
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
              Recargas
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
  
              <a class="dropdown-item disabled" href="/equipos">Lista</a>
  
              <div class="dropdown-divider"></div>
  
              <a class="dropdown-item " href="/transaction">Reporte</a>
  
            </div>
          </li>
          @can('create sucursal')
          <li class="nav-item">
            <a class="nav-link" href="/sucursales">Sucursales</a>
          </li>
          @endcan
          @can('create user')
          <li class="nav-item">
            <a class="nav-link" href="/users">Usuarios</a>
          </li>
          @endcan
  
        </ul>
        <ul class="navbar-nav ml-auto">
  
          <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false" v-pre>
              {{ Auth::user()->name }} <span class="caret"></span>
            </a>
  
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                {{ __('Cerrar Sesion') }}
              </a>
  
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
            </div>
          </li>
        </ul>
        <ul class="navbar-nav">
          <navbar-search-component></navbar-search-component>
          
        </ul>
        @endauth
  
        @guest
        <ul class="navbar-nav ml-auto">
  
          <li class="nav-item active">
            <a class="nav-link" href="{{ route('login') }}">{{ __('Iniciar Sesion') }}</a>
          </li>
        </ul>
        @endguest
      </div>
    </nav>

    <main class="py-4">

      <div>
        @yield('content')
      </div>


    </main>
  </div>
</body>

</html>