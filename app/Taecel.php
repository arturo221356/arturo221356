<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;


class Taecel extends Model
{
    use HasFactory;

    public  function TaecelRequestTXN($key, $nip, $producto, $referencia)
    {
        Http::fake([

            'https://taecel.com/app/api/RequestTXNxxx' => Http::response([
                'success' => true,
                'message' => 'Consulta Exitosa',
                'data' => ['transID' => '210901470051'],



            ], 500, ['Headers']),




        ]);


        $taecelRequest = $this->requestTXN($key, $nip, $producto, $referencia);

        $respuesta = json_decode($taecelRequest);

        if ($respuesta == null) {
            $prueba = [];
            for ($i = 1; $i <= 3; $i++) {

                $taecelRequest = $this->requestTXN($key, $nip, $producto, $referencia);

                array_push($prueba, $taecelRequest);
            }
        }

        return $taecelRequest;
    }

    public  function requestTXN($key, $nip, $producto, $referencia)
    {
        try {

            $request =  Http::asForm()->timeout(70)->post("https://taecel.com/app/api/RequestTXN", [
                'key' => $key,
                'nip' => $nip,
                'producto' => $producto,
                'referencia' => $referencia,

            ]);
        } catch (RequestException $e) {
            $response = json_encode([
                'success' =>  false,
                'message' => "Error de conexion ",
            ]);
        }


        if ($request->serverError()) {

            $response =  json_encode([
                'success' =>  false,
                'message' => 'Error servidor' . $request->status(),
            ]);
        }
        if ($request->clientError()) {

            $response =  json_encode([
                'success' =>  false,
                'message' => 'Error del cliente ' . $request->status(),
            ]);
        }
        if ($request->failed()) {

            $response =  json_encode([
                'success' =>  false,
                'message' => 'Error ' . $request->status() . ' NO REPETIR LA RECARGA, COMUNICATE CON EL DISTRIBUIDOR',
            ]);
        }

        if ($request->successful()) {
            $response =  $request;
        }

        return $response;
    }




    public function TaecelStatusTXN($key, $nip, $transId)
    {

        Http::fake([


            'https://taecel.com/app/api/StatusTXNXXX' => Http::response(

                // ':(
                //  Error intencional en formato de respuesta, se debe de ejecutar nuevamente el Método StatusTXN para Revisar el Status de la Transacción
                //   :/',
                [
                    'success' => true,
                    'message' => 'Recarga Exitosa',
                    'data' => [
                        'TransID' => '210901470051',
                        "Fecha" => "2021-09-22 12:27:47",
                        "Carrier" => "Paquete Amigo Sin Limite",
                        "Telefono" => "5555555510",
                        "Folio" => "34234",
                        "Status" => "Fracasada ",
                        "Monto" => "$30.00",
                        "Cargo" => "$0.00",
                        "Abono" => "$0.00",
                        "Via" => "WS",
                        "Región" => "9",
                        "Timeout" => "10.05",
                        "IP" => "189.203.100.231",
                        "Bolsa" => "Tiempo Aire",
                        "Comision" => "$0.00",
                        "Nota" => "",
                        "pin" => "",
                        "Saldo Final" => "$8,565.05"


                    ],
                ],
                500,
                ['Headers']
            ),






        ]);


        $taecelRequest = $this->statusTXN($key, $nip, $transId);

        $respuesta = json_decode($taecelRequest);

        if ($respuesta == null) {
            $prueba = [];
            for ($i = 1; $i <= 3; $i++) {

                $taecelRequest = $this->statusTXN($key, $nip, $transId);

                array_push($prueba, $taecelRequest);
            }
        }

        return $taecelRequest;
    }




    /***
     * 
     * gets the status from the API 
     */



    private function statusTXN($key, $nip, $transId)
    {
        try {

            $request =  Http::asForm()->timeout(70)->post("https://taecel.com/app/api/StatusTXN", [
                'key' => $key,
                'nip' => $nip,
                'transID' => $transId,

            ]);
        } catch (RequestException $e) {
            $response = json_encode([
                'success' =>  false,
                'message' => "Error de conexion ",
            ]);
        }


        if ($request->serverError()) {

            $response =  json_encode([
                'success' =>  false,
                'message' => 'Error servidor' . $request->status(),
            ]);
        }
        if ($request->clientError()) {

            $response =  json_encode([
                'success' =>  false,
                'message' => 'Error del cliente ' . $request->status(),
            ]);
        }
        if ($request->failed()) {

            $response =  json_encode([
                'success' =>  false,
                'message' => 'Error ' . $request->status() . ' NO REPETIR LA RECARGA!!!, COMUNICATE CON EL DISTRIBUIDOR',
            ]);
        }

        if ($request->successful()) {
            $response =  $request;
        }

        return $response;
    }






    public function getBalance($key, $nip)
    {
        try {

            $request =  Http::asForm()->timeout(70)->post("https://taecel.com/app/api/getBalance", [
                'key' => $key,
                'nip' => $nip,


            ]);
        } catch (RequestException $e) {
            $response = json_encode([
                'success' =>  false,
                'message' => "Error de conexion ",
            ]);
        }


        if ($request->serverError()) {

            $response =  json_encode([
                'success' =>  false,
                'message' => 'Error servidor' . $request->status(),
            ]);
        }
        if ($request->clientError()) {

            $response =  json_encode([
                'success' =>  false,
                'message' => 'Error del cliente ' . $request->status(),
            ]);
        }
        if ($request->failed()) {

            $response =  json_encode([
                'success' =>  false,
                'message' => 'Error ' . $request->status() . ' NO REPETIR LA RECARGA, COMUNICATE CON EL DISTRIBUIDOR',
            ]);
        }

        if ($request->successful()) {
            $response =  $request;
        }

        return $response;
    }
}
