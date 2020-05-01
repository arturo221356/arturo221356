@extends('layouts.app')

@section('content')

<cargar-inventario>
</cargar-inventario> 

<form action="/admin/imei" method="post" enctype="multipart/form-data">
    <input type="file" name="file" id="file" >
    @csrf
    <button type="submit">enviar</button>
</form>

@endsection