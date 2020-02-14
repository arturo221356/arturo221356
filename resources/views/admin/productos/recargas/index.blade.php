@extends('layouts.app')


@section('TableNavbarButtons')
<li class="nav-item active">
    <a class="nav-link" href="/admin/productos/recargas/create">Nueva Recarga<span class="sr-only">(current)</span></a>
</li>
@endsection

@section('TableNavbarName', 'Recargas')






@section('container')
@section('content')
        

    
    
    @include('layout.tablesearch') 


        
        
        
        
        @section('thead')
        
        <th scope="col">Nombre</th>
        <th scope="col">Monto</th>
        <th scope="col">Eliminar</th>
        @stop

        @section('tbody')
            @foreach ($recargas as $recarga)
                <tr>
                   
                        <td>{{$recarga->nombre}}</td>
                        <td>${{$recarga->monto}}</td>
                <td> 
                    <form method="post" action="/admin/productos/recargas/{{$recarga->id}}">
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