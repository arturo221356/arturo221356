@extends('layouts.app')

@section('title', 'Crear nueva Recarga')

@section('container')
 



    @section('content')

    
    <div class="container">
        <div class="col-md-6 mx-auto">    
            <h1>Nueva Recarga</h1><br> 
            <form method="POST" action="/admin/productos/recargas">
                <div class="form-group">
                    <label for="nombre_recarga">Nombre:</label>
                    <input type="text" name="nombre_recarga" id="nombre_recarga" class="form-control" placeholder="Nombre Recarga" readonly value="Recarga tiempo aire">
                    {{ csrf_field() }}
                </div>
                <div class="form-group">
                    <label for="monto_sucursal">Monto:</label> 
                    <input type="number" name="monto_recarga" class="form-control" placeholder="monto" id="monto_recarga" onInput="edValueKeyPress()">
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
<script>
function edValueKeyPress() {
    var edValue = document.getElementById("monto_recarga");
    var s = edValue.value;

    var lblValue = document.getElementById("nombre_recarga");
    lblValue.value = "Recarga tiempo aire " + s;
}
</script>

