<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use MarvinLabs\Luhn\Facades\Luhn;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Client\ConnectionException;
use App\Icc;
use App\Porta;
use Illuminate\Support\Facades\Auth;
use App\PortaClient;






class TelcelPorta extends Model
{

    use HasFactory;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $casts = ['promociones' => 'array'];

    protected $fillable = ["idcop", "dn", "nip", "imei", "nombre", 'apaterno', 'amaterno', 'curp', 'pdv', 'random_client', 'message', 'selected_promo', 'promociones'];

    public function linea()
    {
        return $this->belongsTo('App\Linea')->withTrashed();
    }
    public function user()
    {
        return $this->belongsTo('App\User')->withTrashed();
    }






    public static function newTelcelPorta($apiurl, $numero, $nombre, $apaterno, $amaterno, $curp, $telcelUser)
    {

        if (app('valuestore')->get('telcel_porta_working') == false) {
            $response = [
                'error' => true,
                'mensaje' => 'Servicio temporalmente deshabilitado',
            ];

           
            return [
                'datosPorta' => null,
                'respuesta' => $response,
            ];
        }
        if($telcelUser->idSesion == null){
            $response = [
                'error' => true,
                'mensaje' => 'Usuario sin sesion',
            ];

            return [
                'datosPorta' => null,
                'respuesta' => $response,
            ]; 
        }
        if($telcelUser->error == true){
            $response = [
                'error' => true,
                'mensaje' => 'Error en la sesion '.$telcelUser->mensaje,
            ];

            return [
                'datosPorta' => null,
                'respuesta' => $response,
            ];  
        }


        $portaClient = PortaClient::firstOrCreate(['curp' => $curp], [
            'nombre' => $nombre,
            'apaterno' => $apaterno,
            'amaterno' => $amaterno,
        ]);


        if ($portaClient->counter >= 5) {
            $response = [
                'error' => true,
                'mensaje' => 'Cliente con mas de 5 lineas a su nombre',
            ];

            return $response;
        }



        if (!TelcelPorta::where('dn', $numero)->whereRaw('created_at >= now() - interval ? day', [4])->exists()) {




            $telcelPorta = TelcelPorta::create([
                'dn' => $numero,
                'nombre' => $nombre,
                'apaterno' => $apaterno,
                'amaterno' => $amaterno,
                'curp' => $curp,
                'pdv' => $telcelUser->user,

            ]);



            try {

            $consulta = Http::contentType("application/json")->bodyFormat('json')->post($apiurl, [
                'EndPoint' => 3,
                "Entrada" => 
                "{
                    \"ApMaterno\":\"$amaterno\",
                    \"ApPaterno\":\"$apaterno\",
                    \"AsentaId\":\"0\",\"AsentaNom\":\"\",
                    \"CURP\":\"$curp\",
                    \"Direccion\":\"\",\"EdoId\":\"0\",\"EdoNom\":\"\",
                    \"FzaVtaPospagoPadre\":\"$telcelUser->FzaVtaPospagoPadre\",
                    \"FzaVtaPospagoPersonal\":\"$telcelUser->FzaVtaPospagoPersonal\",
                    \"FzaVtaPospagoReporte\":\"$telcelUser->FzaVtaPospagoReporte\",
                    \"FzaVtaPrepagoPadre\":\"$telcelUser->FzaVtaPrepagoPadre\",
                    \"FzaVtaPrepagoPersonal\":\"$telcelUser->FzaVtaPrepagoPersonal\",
                    \"FzaVtaPrepagoReporte\":\"$telcelUser->FzaVtaPrepagoReporte\",
                    \"idDispositivo\":\"$telcelUser->idDispositivo\",
                    \"IDRegion\":\"5\",
                    \"idSesion\":\"$telcelUser->idSesion\",
                    \"Latitud\":\"20.659698300\",
                    \"Longitud\":\"-103.349608300\",\"MnpioId\":\"0\",\"MnpioNom\":\"\",
                    \"Nombre\":\"$nombre\",
                    \"OnLine\":\"1\",\"Plataforma\":\"2\",\"SistemaOrigen\":\"CAMBACEO\",
                    \"Telefono\":\"$numero\",
                    \"TipoPlan\":\"1\",
                    \"Usuario\":\"$telcelUser->user\"
                }",
    
                    "Metodo" => "20",
                    "Pantalla" => "0",
                    "Usuario" => $telcelUser->user,
    
            ]);

                if ($consulta->successful()) {

                    $consultaJson = json_decode($consulta);

                    $respuestaJson = $consultaJson->Respuesta ? json_decode($consultaJson->Respuesta) : json_encode('Error');

                    $telcelPorta->error = $consultaJson->Error ?? true;

                    $telcelPorta->idcop =  $respuestaJson->idCop ?? '';

                    $telcelPorta->message = $consultaJson->Mensaje  ?? '';

                    $telcelPorta->promociones = $respuestaJson->ListaPromociones ?? [];

                    $telcelPorta->save();


                    if ($consultaJson->Error == false) $portaClient->increment('counter', 1);


                    $response = [
                        'error' =>  $consultaJson->Error ?? true,
                        'mensaje' => $consultaJson->Mensaje  ?? '',
                    ];
                } else {
                    $telcelPorta->error = true;

                    $telcelPorta->message = 'error de consulta';

                    $telcelPorta->save();

                    $response = [
                        'error' =>  true,
                        'mensaje' =>  serialize($consulta),
                    ];
                }
            } catch (ConnectionException $e) {
                $telcelPorta->error = true;

                $telcelPorta->message = 'error de conexion';

                $telcelPorta->save();

                $response = [
                    'error' => true,
                    'mensaje' => 'Error de conexion'
                ];
            }
        } else {
            $telcelPorta = TelcelPorta::where('dn', $numero)->whereRaw('created_at >= now() - interval ? day', [4])->latest()->first();
            $response = [
                'error' => $telcelPorta->error,
                'mensaje' => $telcelPorta->message,
            ];
        }

        return [
            'datosPorta' => $telcelPorta->refresh(),
            'respuesta' => $response,
        ];
    }


    public static function confirmTelcelPorta($icc, $idcop, $promo, $nip, $telcelUser, $apiurl)
    {
        ///////////imei random
        $inicio = '86881003';

        $random = mt_rand(100000, 999999);

        $checkDigit = Luhn::computeCheckDigit($inicio . $random);

        $imei = "$inicio" . "$random" . "$checkDigit";
        ////////////////////////////

        $telcelPorta = TelcelPorta::where('idcop', $idcop)->first();


        try {
            ////CONFIRMAR porta 
            $confirmacion = Http::contentType("application/json")->bodyFormat('json')->post($apiurl, [
                'EndPoint' => 3,
                "Entrada" => "{\"Iccid\":\"$icc\",\"idCop\":\"$idcop\",\"IDPromocion\":\"$promo\",\"IMEI\":\"$imei\",\"Nip\":\"$nip\",\"NumeroKit\":\"\",\"SistemaOrigen\":\"CAMBACEO\"}",
                "Metodo" => "21",
                "Pantalla" => "0",
                "Usuario" => $telcelUser->user

            ]);

            if ($confirmacion->successful()) {
                $confirmacionJson = json_decode($confirmacion);

                $respuestaJson = $confirmacionJson->Respuesta ? json_decode($confirmacionJson->Respuesta) : json_encode('Error');

                if ($confirmacionJson->Error == false) {

                    $finnishPorta = TelcelPorta::finishTelcelPorta($idcop, $telcelUser, $icc, $apiurl);

                    return $finnishPorta;
                } else {
                    $telcelPorta->message = $confirmacionJson->Mensaje ?? '';

                    $telcelPorta->error = true;

                    $telcelPorta->selected_promo = $promo ?? null;

                    $telcelPorta->save();

                    $response = [
                        'datosPorta' => $telcelPorta->refresh(),
                        'respuesta' => [
                            'error' =>  true,
                            'mensaje' =>  $confirmacionJson->Mensaje ?? '',
                            'respuesta' =>  $respuestaJson
                        ]
                    ];
                }
            } else {
                $telcelPorta->message =  serialize($confirmacion) ?? 'error';

                $telcelPorta->error = true;

                $telcelPorta->save();

                $response = [
                    'datosPorta' => $telcelPorta->refresh(),
                    'respuesta' => [
                        'error' =>  true,
                        'mensaje' => serialize($confirmacion) ?? '',
                        'respuesta' => ''
                    ]

                ];
            }
        } catch (ConnectionException $e) {
            $telcelPorta->message = 'Error de conexion en confirmacion';

            $telcelPorta->error = true;

            $telcelPorta->save();

            $response = [
                'datosPorta' => $telcelPorta->refresh(),
                'respuesta' => [
                    'error' => true,
                    'mensaje' => 'Error de conexion',
                    'respuesta' => 'Error de conexion'
                ]

            ];
        }

        return $response;
    }










    public static function finishTelcelPorta($idcop, $telcelUser , $icc, $apiurl)
    {
        $telcelPorta = TelcelPorta::where('idcop', $idcop)->first();

        try {
            ////TERMINAR PORTA
            $consulta = Http::contentType("application/json")->bodyFormat('json')->post($apiurl, [
                'EndPoint' => 3,
                "Entrada" => " {\"AsentaId\":\"0\",\"AsentaNom\":\"\",\"Direccion\":\"\",\"EdoId\":\"0\",\"EdoNom\":\"\",\"idCop\":\"$idcop\",\"IDRegion\":\"5\",\"Latitud\":\"20.659698300\",\"Longitud\":\"-103.349608300\",\"MnpioId\":\"0\",\"MnpioNom\":\"\",\"OnLine\":\"1\",\"Plataforma\":\"2\",\"SistemaOrigen\":\"CAMBACEO\",\"Usuario\":\"$telcelUser->user\"}",
                "Metodo" => "23",
                "Pantalla" => "0",
                "Usuario" => $telcelUser->user

            ]);
            if (!$consulta->successful()) {

                $telcelPorta->message =  serialize($consulta) ?? 'error';

                $telcelPorta->error = true;

                $telcelPorta->save();

                $response = [
                    'datosPorta' => $telcelPorta->refresh(),
                    'respuesta' => [
                        'error' =>  true,
                        'mensaje' => serialize($consulta) ?? '',
                        'respuesta' => ''
                    ]

                ];
            } else {

                $consultaJson = json_decode($consulta);

                $respuestaJson = $consultaJson->Respuesta ? json_decode($consultaJson->Respuesta) : json_encode('Error');


                $telcelPorta->message = $consultaJson->Mensaje ?? '';

                $telcelPorta->error = $consultaJson->Error;

                if ($consultaJson->Error == false) {

                    $telcelPorta->message = 'Portabilidad subida';

                    $telcelPorta->finnished = true;

                    ///////////////cambiar forma de obetener ICC
                    $iccR = Icc::where('icc', $icc)->first();
                    $porta = Porta::create([
                        'nip' => isset($telcelPorta->nip) ?? null,
                    ]);
                    $linea = $porta->linea()->create([
                        'dn' => $telcelPorta->dn,
                        'icc_product_id' => 2,
                        'icc_id' => $iccR->id,

                    ]);

                    $linea->setStatus('Porta subida');

                    if (Auth::check()) {

                        $linea->user()->associate(Auth::user());

                        $telcelPorta->user()->associate(Auth::user());

                        $linea->save();
                    }

                    $iccR->setStatus('Vendido');


                    $telcelPorta->linea()->associate($linea);
                }

                $telcelPorta->save();

                $response = [
                    'datosPorta' => $telcelPorta->refresh(),
                    'respuesta' => [
                        'error' =>  $consultaJson->Error ?? true,
                        'mensaje' =>  $consultaJson->Error == false ? 'Portabilidad Subida' :  $consultaJson->Error ?? '',
                        'respuesta' =>  $respuestaJson
                    ]
                ];
            }
        } catch (ConnectionException $e) {
            $telcelPorta->message = 'Error de conexion en confirmacion';

            $telcelPorta->error = true;

            $telcelPorta->save();

            $response = [
                'datosPorta' => $telcelPorta->refresh(),
                'respuesta' => [
                    'error' => true,
                    'mensaje' => 'Error de conexion',
                    'respuesta' => 'Error de conexion'
                ]

            ];
        }
        return $response;
    }
}
