@extends('layouts.app')


@section('TableNavbarButtons')
<li class="nav-item active">
    <a class="nav-link" href="/admin/productos/equipos/create">Nuevo Equipo<span class="sr-only">(current)</span></a>
</li>
@endsection

@section('TableNavbarName', 'Equipos')






@section('container')
@section('content')
        

    <equipos-component></equipos-component>

            

@endsection