@extends('layouts.app')

@section('title', 'Crear nuevo Equipo')

@section('container')
 



    @section('content')

    
    <div class="container">
        <div class="col-md-6 mx-auto">    
            <h1>Nuevo Equipo</h1><br> 
            <form method="POST" action="/admin/productos/equipos">
                <div class="form-group">
                    <label for="marca_equipo">Marca:</label>
                    <input type="text" name="marca_equipo" id="marca_equipo" class="form-control" placeholder="Marca Equipo" required>
                    {{ csrf_field() }}
                </div>
                <div class="form-group">
                    <label for="modelo_equipo">Modelo:</label>
                    <input type="text" name="modelo_equipo" id="modelo_equipo" class="form-control" placeholder="Modelo Equipo" required>
                    {{ csrf_field() }}
                </div>
                <div class="form-group">
                    <label for="monto_sucursal">Precio:</label> 
                    <input type="number" name="precio_equipo" class="form-control" placeholder="Precio" id="precio_equipo" required>
                    {{ csrf_field() }}
                </div>

                <div class="form-group">
                    <label for="monto_sucursal">Costo:</label> 
                    <input type="number" name="costo_equipo" class="form-control" placeholder="Costo" id="costo_equipo">
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