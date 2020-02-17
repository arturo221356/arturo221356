@extends('layouts.app')


@section('TableNavbarButtons')
<li class="nav-item active">
    <a class="nav-link" href="/admin/productos/equipos/create">Nuevo Equipo<span class="sr-only">(current)</span></a>
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
        <th scope="col">Costo</th>
        <th scope="col">Editar</th>
        @stop

        @section('tbody')
            @foreach ($equipos as $equipo)
                <tr>
                   
                        <td>{{$equipo->marca}}</td>
                        <td>{{$equipo->modelo}}</td>
                        <td>${{$equipo->precio}}</td>
                        <td>${{$equipo->costo}}</td>
                        <td><a class="btn btn-outline-warning" href="{{route('admin.equipos.edit', $equipo->id)}}" role="button">Editar</td>
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