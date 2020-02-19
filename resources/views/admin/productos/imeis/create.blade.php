@extends('layouts.app')


@section('container')

@section('content')



<div class="jumbotron">
    <div class="col-md-6 mx-auto">
        <h1>Agregar Imeis</h1>

        <form action="/admin/imei" method="POST">
            @csrf  
            <div class="form-group">
                <select name="sucursal" class="form-control">
                    @foreach ($sucursales as $sucursal)
                        <option value="{{$sucursal->id}}">{{$sucursal->nombre_sucursal}}</option>
                        
                    @endforeach
                </select>
                
            </div>

            <div class="form-group">
                <select name="equipo" class="form-control">
                    @foreach ($equipos as $equipo)
                        <option value="{{$equipo->id}}">{{$equipo->marca}} {{$equipo->modelo}}</option>
                        
                    @endforeach
                </select>
                
            </div>

            <div class="form-group">
                <textarea name="imei" id="imei" cols="15" rows="10" class="form-control">

                </textarea>
                
                

            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" name="" id="" value="Enviar">
                
            </div>
        
        
        
        </form>

    </div>

</div>







































@endsection
@endsection