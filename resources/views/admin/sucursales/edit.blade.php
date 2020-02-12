@extends('layouts.app')


@section('container')
 



    @section('content')
    
    
  <div class="container">  
    <div class="col-md-6 mx-auto">
        <h1>Editar: {{$sucursal->nombre_sucursal}}  </h1><br>     
             <form method="POST" action="/admin/sucursales/{{$sucursal->id}}">
                <input type="hidden" name="_method" value="PUT">
                <div class="form-group">
                    <label for="nombre_sucursal">Nombre Sucursal:</label>
                <input type="text" name="nombre_sucursal" id="nombre_sucursal" class="form-control" value="{{$sucursal->nombre_sucursal}}">
                    {{ csrf_field() }}
                </div>
                <div class="form-group">
                    <label for="direccion_sucursal">Direccion:</label>    
                    <input type="text" name="direccion_sucursal" id="direccion_sucurssal" class="form-control" value="{{$sucursal->direccion_sucursal}}">
                    {{ csrf_field() }}
                </div>
                <div class="form-group">
                    <label for="email_sucursal">Correo Electronico:</label> 
                    <input type="email" name="email_sucursal" class="form-control" value="{{$sucursal->email_sucursal}}">
                    {{ csrf_field() }}
                </div>
                <div class="form-row">
                    
                        <input type="submit" name="enviar" value="Editar" class="btn btn-warning ">
                   
                
            </form>
            
                <div class="col">
                    <form method="post" action="/admin/sucursales/{{$sucursal->id}}">
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