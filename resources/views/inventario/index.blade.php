@extends('layouts.app')

@section('TableNavbarButtons')

@section('TableNavbarName', "Inventario $currentSucursal")

<li class="nav-item">
    <a class="nav-link" href="">Equipos<span class="sr-only">(current)</span></a>
</li>
<li class="nav-item">
    <a class="nav-link" href="">Sims<span class="sr-only">(current)</span></a>
</li>

@if ($userRole != 'seller')
        <li class="nav-item">
           
           
            <select id="sucursal" class="form-control" name="sucursal">
              <option value="all" selected>Todas</option>
                @foreach($sucursales as $sucursal)
            <option value="{{$sucursal->id}}">{{$sucursal->nombre_sucursal}}</option>
                @endforeach
            </select>
        </li>
@endif

@endsection








@section('container')
@section('content')
        
        @if(count($errors)>0)
                
            <div class="alert alert-warning mt-lg-2" role="alert">
                    <h4 class="alert-heading">Error</h4>
                    <p>
                    @foreach($errors->all() as $error)
                        {{$error}}
                        <hr>
                    @endforeach</p>
                    
                </div>
            

            
            @endif

    
    
    @include('layout.tablesearch') 


        
        
        
        
        @section('thead')
        
        <th scope="col">Imei</th>
        <th scope="col">Marca</th>
        <th scope="col">Modelo</th>
        <th scope="col">Sucursal</th>
        <th scope="col">Precio</th>
        <th scope="col">Estatus</th>
        
        @if ($userRole == 'admin')
        <th scope="col">Editar</th> 
        <th scope="col">Costo</th> 
        @endif
       
        @stop


        
        @section('tbody')
           @foreach ($imeis as $imei)
                <tr>
                    <td>{{$imei->imei}}</td> 
                    <td>{{$imei->equipo->marca}}</td>
                    <td>{{$imei->equipo->modelo}}</td>    
                    <td>{{$imei->sucursal->nombre_sucursal}}</td>
                    <td>${{$imei->equipo->precio}}</td>
                    <td>{{$imei->status->status}}</td>
                    

                    
                    
                    @if ($userRole == 'admin')
                    <td>${{$imei->equipo->costo}}</td> 
                    <td><a class="btn btn-outline-warning" href="{{route('admin.imei.edit', $imei->id)}}" role="button">Editar</td>
                    @endif
                    
                </tr>
            @endforeach
        @stop
        @section('tfoot')
            <tr>
                
                <th></th>
                <th></th>
                <th></th>
            </tr>
        
        @stop
        @include('layout.table')
     
        @endsection

            

@endsection