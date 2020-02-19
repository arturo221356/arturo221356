

@extends('layouts.app')


@section('container')
 



    @section('content')
    
    
  <div class="container">  
    <div class="col-md-6 mx-auto">
        <h1>Editar Imei: {{$imei->imei}}  </h1><br>     
             <form method="POST" action="/admin/imei/{{$imei->id}}">
                <input type="hidden" name="_method" value="PUT">
               
                <div class="form-group">
                    <label for="name">Imei:</label>
                <input type="text" name="imei" id="imei" class="form-control" value="{{$imei->imei}}">
                    {{ csrf_field() }}
                </div>

                        <!--select de sucursal -->

                        <div class="form-group">
                            <label for="sucursal">Sucursal:</label>
                            <select id="sucursal" class="form-control" name="sucursal">
                                
                                <option selected value="{{$imei->sucursal->id}}">{{$imei->sucursal->nombre_sucursal}}</option>
                            
                                @foreach($sucursales as $sucursal)
                            <option value="{{$sucursal->id}}">{{$sucursal->nombre_sucursal}}</option>                                               @endforeach
                            </select>
                        </div>   
                        
                        
                        <!--select de rol -->
        
                        <div class="form-group">
                            <label for="equipo">Equipo:</label>
                            <select id="equipo" class="form-control" name="rol">
                            <option selected value="{{$imei->equipo->id}}">{{$imei->equipo->marca}} {{$imei->equipo->modelo}}</option>
                                @foreach($equipos as $equipo)
                                <option value="{{$equipo->id}}">{{$equipo->marca}} {{$equipo->modelo}}</option>
                                @endforeach
                            </select>
                            </div>  
                
                
                
                <div class="form-row">
                    
                        <input type="submit" name="enviar" value="Editar" class="btn btn-warning ">
 
            </form>
            
                <div class="col">
                    <form method="post" action="/admin/imei/{{$imei->id}}">
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