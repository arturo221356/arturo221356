@extends('layouts.app')

@section('content')
<a>Admin 
    @foreach($sucursales as $sucursal)
    {{$sucursal->nombre_sucursal}}</a>
    @endforeach
@endsection