@extends('layouts.app')

@section('title', 'Crear nueva Sucursal')

@section('container')
 



    @section('content')

    
    <div class="container">
        <div class="col-md-6 mx-auto">    
            <h1>Nueva Sucursal</h1><br> 
            <form method="POST" action="/admin/sucursales">
                <div class="form-group">
                    <label for="nombre_sucursal">Nombre Sucursal:</label>
                    <input type="text" name="nombre_sucursal" id="nombre_sucursal" class="form-control" placeholder="Nombre">
                    {{ csrf_field() }}
                </div>
                <div class="form-group">
                    <label for="direccion_sucursal">Direccion:</label>    
                    <input type="text" name="direccion_sucursal" id="direccion_sucurssal" class="form-control" placeholder="Direccion 1234">
                    {{ csrf_field() }}
                </div>
                <div class="form-group">
                    <label for="email_sucursal">Correo Electronico:</label> 
                    <input type="email" name="email_sucursal" class="form-control" placeholder="Tienda@pro-moviles.com">
                    {{ csrf_field() }}
                </div>
                <input type="submit" name="enviar" value="enviar" class="btn btn-primary">
            </form>
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
        </div>  
    </div>  
    @endsection



@endsection