<!DOCTYPE html>
<html>

<head>
    <title>Generate Pdf</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>

<body>

    <div class="jumbotron">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="display-4">{{$distribution}}</h3>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-6">

                    <h5><B>Sucursal:</B> {{$inventario->name}}</h5>
                    <h5><B>Vendedor:</B> {{$vendedor}}</h5>
                    @if (isset($inventario->address))
                    <h5><B>Domicilio:</B> {{$inventario->address}}</h5>
                    @endif
                </div>
                <div class="col-sm-6">
                    <h5><B>Folio:</B> {{$venta->id}}</h5>
                    <h5><B>Fecha:</B> {{$fecha}}</h5>

                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    @if (isset($venta->cliente->name))
                    <h5>
                        <B>Cliente:</B> {{$venta->cliente->name}}
                    </h5>
                    @endif
                </div>

            </div>
        
        <hr class="my-4">
        <div class="row">
            <div class="col-sm-12">


                <div class="list-group">



                    @foreach ($productosGenerales as $productoGeneral)
                    <a class="list-group-item list-group-item-action  flex-column align-items-start ">
                        <div class="col-auto">
                            <h5>{{$productoGeneral->name}}</h5>
                            <p>{{$productoGeneral->description}}</p>
                            <p align="right"><B>Precio:</B> ${{$productoGeneral->pivot->price}}</p>
                        </div>
                    </a>
                    @endforeach
                    @foreach ($imeis as $imei)
                    <a class="list-group-item list-group-item-action flex-column align-items-start ">
                        <div class="col-auto">
                            <h5>{{$imei->equipo->marca}} {{$imei->equipo->modelo}}</h5>
                            <p>Imei: {{$imei->imei}}</p>
                            <p align="right"><B>Precio:</B> ${{$imei->pivot->price}}</p>
                        </div>
                    </a>
                    @endforeach
                    @foreach ($iccs as $icc)
                    <a class="list-group-item list-group-item-action ">
                        <div class="col-auto">
                            <h5>{{$icc->linea->subProduct->name}}</h5>
                            <p>{{$icc->linea->product->name}}<br>
                                Compañia: {{$icc->company->name}}<br>
                                Numero: {{$icc->linea->dn}}<br>
                                Icc: {{$icc->icc}}<br>


                            </p>
                            <p align="right"><B>Precio:</B> ${{$icc->pivot->price}}</p>
                        </div>
                    </a>
                    @endforeach

                    @foreach ($transactions as $transaction)
                    <a class="list-group-item list-group-item-action flex-column align-items-start {{ $transaction->taecel_success == false ? 'list-group-item-danger' : '' }}">
                        <div class="col-auto">
                            <h5>{{$transaction->recarga->name}}</h5>
                            Compañia: {{$transaction->company->name}}<br>
                            Numero: {{$transaction->dn}}<br>
                            @if($transaction->taecel == true)
                            Mensaje: {{$transaction->taecel_message}}<br> 
                            Folio: {{$transaction->taecel_folio}}<br> 
                            @endif


                            </p>
                            <p align="right"><B>Precio:</B> ${{$transaction->pivot->price}}</p>
                        </div>
                    </a>
                    @endforeach

                    <hr class="my-2">

                <a href="#"  class="list-group-item list-group-item-action "><h4 align="right">
                    Total: ${{$venta->total}}</h4></a>
                </ul>
            </div>
        </div>


        



       

    </div>
</div>

</body>

</html>