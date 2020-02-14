@extends('layouts.app')

@section('TableNavbarName', 'Usuarios')
@section('TableNavbarButtons')
    <li class="nav-item active">
    <a class="nav-link" href="/admin/users/create">Nuevo Usuario<span class="sr-only">(current)</span></a>
    </li>
@endsection


@section('container')
 



    @section('content')
        
        @include('layout.tablesearch') 


        
        
        
        
        @section('thead')
        <th scope="col">#</th>
        <th scope="col">Usuario</th>
        <th scope="col">Email</th>
        <th scope="col">Sucursal</th>
        <th scope="col">Rol</th>
        <th scope="col">Editar</th>
        @stop

        @section('tbody')
            @foreach ($users as $user)
                <tr>
                    <th>{{$user->id}}</th>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{implode('',$user->sucursal()->get()->pluck('nombre_sucursal')->toArray())}}</td>
                        <td>{{implode('',$user->roles()->get()->pluck('name')->toArray())}}</td>
               <td><a class="btn btn-outline-warning" href="{{route('admin.users.edit', $user->id)}}" role="button">Editar</td>
                </tr>
            @endforeach
        @stop
        @section('tfoot')

        
        @stop
        @include('layout.table')
     
        @endsection



@endsection