@extends('layouts.app')


@section('TableNavbarName', 'Sucursales')
@section('TableNavbarButtons')
<li class="nav-item active">
    <a class="nav-link" href="/admin/sucursales/create">Nueva Sucursal<span class="sr-only">(current)</span></a>
</li>
@endsection

@section('container')
 



    @section('content')
        

    
    
    @include('layout.tablesearch') 
        
        
        
        
        @section('thead')
        <th scope="col">#</th>
        <th scope="col">Sucursal</th>
        <th scope="col">Direccion</th>
        <th scope="col">correo Electronico</th>
        <th scope="col">Caja</th>
        <th scope="col">Ultimo Corte</th>
        <th scope="col">Editar</th>
        @stop

        @section('tbody')
            @foreach ($sucursales as $sucursal)
                <tr>
                    <th>{{$sucursal->id}}</th>
                        <td>{{$sucursal->nombre_sucursal}}</td>
                        <td>{{$sucursal->direccion_sucursal}}</td>
                        <td>{{$sucursal->email_sucursal}}</td>
                        <td>$@if($sucursal->caja_sucursal== NULL) 0 @else{{$sucursal->caja_sucursal}}@endif</td>
                        <td>{{$sucursal->ultimo_corte}}</td>
                <td><a class="btn btn-outline-warning" href="{{route('admin.sucursales.edit', $sucursal->id)}}" role="button">Editar</td>
                </tr>
            @endforeach
        @stop
        @section('tfoot')
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        
        @stop
        @include('layout.table')
     
        @endsection



@endsection





