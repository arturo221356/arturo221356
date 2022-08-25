<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class TelcelUser extends Model
{
    protected $fillable = ['user', 'password', 'internetpass', 'FzaVtaPrepagoPadre', 'FzaVtaPospagoPadre', 'FzaVtaPrepagoPersonal', 'FzaVtaPospagoPersonal', 'FzaVtaPrepagoReporte', 'FzaVtaPospagoReporte', 'idSesion', 'idDispositivo', 'error', 'mensaje', 'distribution_id'];

    use HasFactory;



    public static function loginTelcel($urlapi, $telcelUser)
    {


        $iddispositivo = $telcelUser->idDispositivo;
        $pass = $telcelUser->password;
        $user = $telcelUser->user;

        try {

            $consulta = Http::contentType("application/json")->bodyFormat('json')->post($urlapi, [
                'EndPoint' => 1,
                "Entrada" => "{\"idDispositivo\":\"$iddispositivo\",\"Password\":\"$pass\",\"Region\":\"5\",\"Usuario\":\"$user\",\"versionApp\":\"4.5 220622P\"}",
                "Metodo" => "1",
                "Pantalla" => "0",
                "Usuario" => "",

            ]);
            $consultaJson = json_decode($consulta);

            if (isset($consultaJson->Error) && $consultaJson->Error == false) {

                $respuestaJson = json_decode($consultaJson->Respuesta);

                $telcelUser->idSesion =  $respuestaJson->idSesion ?? null;

                if ($respuestaJson->idSesion !== null) {
                    $telcelUser->FzaVtaPrepagoPadre = $respuestaJson->FzaVtaPrepagoPadre ?? $telcelUser->FzaVtaPrepagoPadre;
                    $telcelUser->FzaVtaPospagoPadre = $respuestaJson->FzaVtaPospagoPadre ?? $telcelUser->FzaVtaPospagoPadre;
                    $telcelUser->FzaVtaPrepagoPersonal = $respuestaJson->FzaVtaPrepagoPersonal ?? $telcelUser->FzaVtaPrepagoPersonal;
                    $telcelUser->FzaVtaPospagoPersonal = $respuestaJson->FzaVtaPospagoPersonal ?? $telcelUser->FzaVtaPospagoPersonal;
                    $telcelUser->FzaVtaPrepagoReporte = $respuestaJson->FzaVtaPrepagoReporte ?? $telcelUser->FzaVtaPrepagoReporte;
                    $telcelUser->FzaVtaPospagoReporte = $respuestaJson->FzaVtaPospagoReporte ?? $telcelUser->FzaVtaPospagoReporte;
                    $telcelUser->error = false;
                    $telcelUser->mensaje = null;
                }
            } else {
                $telcelUser->error = true;

                $telcelUser->mensaje = $consultaJson->Mensaje ?? 'error';

                $telcelUser->idSesion = null;
            }

            $telcelUser->save();

        } catch (ConnectionException $e) {

            $telcelUser->idSesion = null;

            $telcelUser->error = true;

            $telcelUser->save();
        }



        return $telcelUser->error ;
    }

    public static function logOut($urlapi, $telcelUser)
    {


        $iddispositivo = $telcelUser->idDispositivo;
        $idsesion = $telcelUser->idSesion;
        $user = $telcelUser->user;

        $consulta = Http::contentType("application/json")->bodyFormat('json')->post($urlapi, [
            'EndPoint' => 11,
            "Entrada" => "{\"idDispositivo\":\"$iddispositivo\",\"idSesion\":\"$idsesion\",\"Region\":\"5\",\"Usuario\":\"$user\"}",
            "Metodo" => "67",
            "Pantalla" => "0",
            "Usuario" => $user,

        ]);

         $telcelUser->idSesion =  null;

         $telcelUser->save();

        return $consulta;
    }
    public static function checkIn($urlapi, $telcelUser)
    {

        $consulta = Http::contentType("application/json")->bodyFormat('json')->post($urlapi, [
            'EndPoint' => 10,
            "Entrada" => "{\"idDispositivo\":\"$telcelUser->idDispositivo\",\"idSesion\":\"$telcelUser->idSesion\",\"latitud\":\"20.6596983\",\"longitud\":\"-103.3496083\",\"opcion\":\"1\",\"Region\":\"5\"}",
            "Metodo" => "61",
            "Pantalla" => "0",
            "Usuario" => $telcelUser->user,

        ]);

        return $consulta;
    }
}
