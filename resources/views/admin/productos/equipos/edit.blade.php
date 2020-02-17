@extends('layouts.app')

@section('title', 'Editar Equipo')

@section('container')
 



    @section('content')

    
    <div class="container">
        <div class="col-md-6 mx-auto">    
            <h1>Editar Equipo: {{$equipo->marca}} {{$equipo->modelo}}</h1><br> 
            <form method="POST" action="/admin/productos/equipos/{{$equipo->id}}">
                <input type="hidden" name="_method" value="PUT">
                <div class="form-group">
                    <label for="marca_equipo">Marca:</label>
                    <input type="text" name="marca" id="marca" class="form-control" placeholder="Marca Equipo" value="{{$equipo->marca}}" required>
                    {{ csrf_field() }}
                </div>
                <div class="form-group">
                    <label for="modelo_equipo">Modelo:</label>
                    <input type="text" name="modelo" id="modelo_equipo" class="form-control" placeholder="Modelo Equipo" required value="{{$equipo->modelo}}">
                    {{ csrf_field() }}
                </div>
                <div class="form-group">
                    <label for="precio_equipo">Precio:</label> 
                    <input type="number" name="precio" class="form-control" placeholder="Precio" id="precio_equipo" required value="{{$equipo->precio}}">
                    {{ csrf_field() }}
                </div>

                <div class="form-group">
                    <label for="costo_equipo">Costo:</label> 
                    <input type="number" name="costo" class="form-control" placeholder="Costo" id="costo_equipo" value="{{$equipo->costo}}">
                    {{ csrf_field() }}
                </div>

                <div class="form-row">
                <input type="submit" name="enviar" value="enviar" class="btn btn-primary">
            </form>
            
            <div class="col">
                <form method="post" action="/admin/productos/equipos/{{$equipo->id}}">
                    <input type="hidden" name="_method" value="DELETE" class="btn btn-danger">    
                    <input type="submit" name="enviar" value="Eliminar" class="btn btn-danger" > 
                {{ csrf_field() }}
                </form>
            </div>
        </div>
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