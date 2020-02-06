@extends('layouts.app')


@section('container')
 



    @section('content')
    
    
  <div class="container">  
    <div class="col-md-6 mx-auto">
        <h1>Nuevo Usuario</h1><br>     
             <form method="POST" action="/admin/users/">
                <div class="form-group">
                    <label for="name">Nombre:</label>
                <input type="text" name="name" id="name" class="form-control">
                    {{ csrf_field() }}
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>    
                    <input type="text" name="email" id="email" class="form-control">
                    {{ csrf_field() }}
                </div>
                <div class="form-group">
                    <label for="password">password:</label>    
                    <input type="password" name="password" id="password" class="form-control">
                    {{ csrf_field() }}
                </div>

                                        <!--select de sucursal -->
                        
                                        <div class="form-group">
                                            <label for="sucursal">Sucursal</label>
                                            <select id="sucursal" class="form-control" name="sucursal">
                                                <option selected value="">Seleccionar Sucursal</option>
                                              @foreach($sucursales as $sucursal)
                                            <option value="{{$sucursal->id}}">{{$sucursal->nombre_sucursal}}</option>
                                                @endforeach
                                            </select>
                                          </div>   
                                        <!--select de rol -->
                        
                                        <div class="form-group">
                                            <label for="rol">Rol</label>
                                            <select id="rol" class="form-control" name="rol">
                                            <option selected value="">Seleccionar Rol</option>
                                              @foreach($roles as $rol)
                                             <option value="{{$rol->id}}">{{$rol->name}}</option>
                                                @endforeach
                                            </select>
                                          </div>  
                
                
                
                <div class="form-row">
                    
                        <input type="submit" name="enviar" value="Enviar" class="btn btn-primary ">
 
            </form>
            

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