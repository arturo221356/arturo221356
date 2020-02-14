@extends('layouts.app')


@section('TableNavbarButtons')
<li class="nav-item active">
    <a class="nav-link" href="/admin/productos/recargas/create">Nuevo Equipo<span class="sr-only">(current)</span></a>
</li>
@endsection

@section('TableNavbarName', 'Equipos')






@section('container')
@section('content')
        

    
    
    @include('layout.tablesearch') 


        
        
        
        
        @section('thead')
        
        <th scope="col">Marca</th>
        <th scope="col">Modelo</th>
        <th scope="col">Precio</th>
        <th scope="col">Costo de Compra</th>
        <th scope="col">Eliminar</th>
        @stop

        @section('tbody')
            @foreach ($equipos as $equipo)
                <tr>
                   
                        <td>{{$equipo->marca}}</td>
                        <td>{{$equipo->modelo}}</td>
                        <td>${{$equipo->precio}}</td>
                        <td>${{$equipo->costo}}</td>
                <td> 
                    <form method="post" action="/admin/productos/recargas/{{$equipo->id}}">
                    <input type="hidden" name="_method" value="DELETE" class="btn btn-danger">    
                    <input type="submit" name="enviar" value="Eliminar" class="btn btn-danger" > 
                    {{ csrf_field() }}
                </form> </td>
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