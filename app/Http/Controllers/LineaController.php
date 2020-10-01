<?php

namespace App\Http\Controllers;

use App\Linea;

use App\Icc;

use App\Chip;

use App\Recarga;

use App\Transaction;

use App\Inventario;

use Illuminate\Http\Client\RequestException;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use App\Imports\LineaImport;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Maatwebsite\Excel\Excel;

class LineaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function verificarIcc(Request $request)
    {


        $requestIcc = $request->icc;

        $user = Auth::user();


        if ($user->can('distribution inventarios')) {

            $icc = Icc::iccInUserDistribution($requestIcc)->with('company', 'type')->first();
        } else {
            $icc = Icc::iccInUserInventario($requestIcc)->with('company', 'type')->first();
        }
        if ($icc === null) {
            $response = [
                'success' => false,
                'message' => 'Icc no existe en la base de datos'
            ];

            return $response;
        }

        if ($icc->linea()->first() == null) {
            $response = [
                "success" => true,
                "data" => $icc,
            ];
        } else {
            $response = [
                "success" => false,
                "message" => "Icc ya tiene linea activa: " . $icc->linea->dn,

            ];
        }

        return  json_encode($response);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function preactivarPrepago(Request $request)
    {

        $recargable = $request->recargable;

        $montoRecarga = $request->monto;


        $user = Auth::user();

        if ($user->can('preactivar linea'))

            $iccs = json_decode($request->data);

        $errores = [];

        $exitosos = [];

        if ($request->hasFile('file')) {

            $file = $request->file('file');

            $data = [
                'recargable' => $recargable,
                'monto' => $montoRecarga,
            ];


            $lineaImport = new LineaImport($data);

            $lineaImport->import($file);

            //obtiene los los mensales de error
            $lineaValidationErrors = $lineaImport->getErrors();
            $lineaValidationSuccess = $lineaImport->getsuccess();

            //pushea los mensajes a los arrays
            foreach ($lineaValidationErrors as $validationErr) {
                array_push($errores, $validationErr);
            }
            foreach ($lineaValidationSuccess as $validationSucc) {
                array_push($exitosos, $validationSucc);
            }
        }

        if ($iccs) {
            foreach ($iccs as $item) {

                $dn = $item->dn;

                $requestIcc  = $item->icc;


                // Crea la matriz de mensajes.
                $mensajes = array(

                    'digits' => 'La Icc/DN tiene que se numerica y de 19/10 digitos'
                );

                $activacion = ['serie' => $requestIcc, 'dn' => $dn];

                //reglas del validador
                $validator = Validator::make($activacion, [
                    'serie' => 'required|digits:19',
                    'dn' => 'required|digits:10'


                ], $mensajes);

                if ($validator->fails()) {


                    $err = [];

                    $errorList = [];

                    $err['icc'] = $requestIcc;

                    $err['dn'] = $dn;

                    foreach ($validator->errors()->toArray() as $error) {



                        foreach ($error as $sub_error) {
                            array_push($errorList, $sub_error);
                        }
                    }
                    $err['errores'] = $errorList;

                    array_push($errores, $err);
                } else {

                    if ($user->can('distribution inventarios')) {

                        $icc = Icc::iccInUserDistribution($requestIcc)->first();
                    } else {
                        $icc = Icc::iccInUserInventario($requestIcc)->first();
                    }



                    if ($icc != null) {

                        if ($icc->linea()->first() != null) {

                            $err['icc'] = $requestIcc;

                            $err['dn'] = $dn;

                            $errorList = [];

                            $message = "Icc ya cuenta con linea activa " . $icc->linea->dn;

                            array_push($errorList, $message);

                            $err['errores'] = $errorList;

                            array_push($errores, $err);
                        } else {


                            $exitoso = ['icc' => $icc->icc, 'dn' => $dn];

                            array_push($exitosos, $exitoso);

                            $chip = Chip::create([]);

                            $linea = $chip->linea()->create([
                                'dn' => $dn,
                                'icc_product_id' => 1,
                                'icc_id' => $icc->id,

                            ]);


                            $recargable == 'true' ? $linea->setStatus('Recargable', $montoRecarga) : $linea->setStatus('Preactiva');
                        }
                    } else {


                        $err['icc'] = $requestIcc;

                        $err['dn'] = $dn;

                        $errorList = [];

                        $message = "Icc no encontrado en la base de datos";

                        array_push($errorList, $message);

                        $err['errores'] = $errorList;

                        array_push($errores, $err);
                    }
                }
            }
        }


        //regresa los mensajes de errores y exitosos
        return ['errors' => $errores, 'success' => $exitosos];
    }






    public function recargaChip(Request $request)
    {

        $message = [];

        $dn = $request->dn;


        //selecciona la linea que tiene el valor DN que corresponda con la request
        $linea = Linea::where('dn', $dn)->first();


        // si no encuenta la linea retorna numero no existe en la DB
        if ($linea == null) {

            $message = [
                'success' => false,
                'message' => 'Numero no existe en la base de datos',

            ];

            return json_encode($message);
        }

        // revisa que el status de la liena sea recargble 
        if ($linea->status() != 'Recargable') {
            $message = [
                'success' => false,

            ];

            switch ($linea->status()) {
                case 'Preactiva':
                    $message['message'] = 'Numero no tiene recarga asignada';
                    break;
                default:
                    $message['message'] = 'Numero activado anteriormente';
                    break;
            }


            return json_encode($message);
        }


        $inventario = $linea->icc->inventario;


        // revisa que el inventario tenga permisos para activar chips desde activa chip

        // if (!$inventario->hasPermissionTo('activar chip', 'web')) {

        //     $message = [
        //         'success' => false,
        //         'message' => 'No tienes permiso de activar chips portate bien',

        //     ];

        //     return json_encode($message);
        // }




        //monto de recarga esta guardado en la razon del status

        $monto = $linea->status()->reason;


        //selecciona la recarga conforme a la compañia del icc seleccionado y monto igual a la razon del status

        $recarga = Recarga::where([['monto', '=', $monto], ['company_id', '=', $linea->icc->company_id]])->first();


        // claves taecel pertenecientes a la distribucion

        $taecelKey = $inventario->distribution->taecel_key;

        $taecelNip = $inventario->distribution->taecel_nip;




        //peticion HTTP requestTXN

        $res = Http::asForm()->post('https://taecel.com/app/api/RequestTXN', [
            'key' => $taecelKey,
            'nip' => $taecelNip,
            'producto' => $recarga->taecel_code,
            'referencia' => $dn
        ]);

        if($res->serverError()){
           
            $response = [
                'success' =>  false,
                'message' => 'Error del servidor',
            ];

            return $response;
        }

        if($res->clientError()){
            $response = [
                'success' =>  false,
                'message' => 'Error ',
            ];

            return $response;
        }
        // Determine if the status code was >= 400...
        if($res->failed()){

            $response = [
                'success' =>  false,
                'message' => 'Error',
            ];

            return $response;

        }

        
        $requestTXN = json_decode($res);

       

        // crea una transaccion nueva
        $trasnsaction = new Transaction;

        $trasnsaction->taecel = true;

        $trasnsaction->monto = $recarga->monto;

        $trasnsaction->dn = $dn;

        $trasnsaction->company_id = $recarga->company_id;

        $trasnsaction->recarga_id = $recarga->id;

        $trasnsaction->inventario_id = $linea->icc->inventario_id;


        // si el requestTXN falla 

        if ($requestTXN->success == false) {

            $trasnsaction->taecel_success = $requestTXN->success;

            $trasnsaction->taecel_message =  $requestTXN->message;

            $trasnsaction->save();

            $response = [
                'success' =>  $requestTXN->success,
                'message' => $requestTXN->message
            ];

            return $response;
        }
        /// si el requestTXN es correcto 
        else if ($requestTXN->success == true) {

            $trasnsaction->taecel_transID = $requestTXN->data->transID;

            // peticion HTTP statusTXN

            
            try{
                $status = Http::asForm()->timeout(60)->retry(3, 1000)->post('https://taecel.com/app/api/statusTXN', [
                    'key' => $taecelKey,
                    'nip' => $taecelNip,
                    'transID' => $trasnsaction->taecel_transID,
                ]);
                

            } catch(RequestException $e){
                
                $response = [
                    'success' =>  false,
                    'message' => 'Error de conexion',
                ];

                $trasnsaction->save();

                return $response;

              
            }

            if($status->serverError()){
                
                $response = [
                    'success' =>  false,
                    'message' => 'Error del servidor',
                ];
    
                return $response;

            }
            if($status->clientError()){
                
                $response = [
                    'success' =>  false,
                    'message' => 'Error del servidor',
                ];
    
                return $response;

            }
            if($status->failed()){
                
                $response = [
                    'success' =>  false,
                    'message' => 'Error del servidor',
                ];
    
                return $response;

            }

           

             $statusTXN = json_decode($status);

             $aa = json_last_error();

             return $aa;

            // $trasnsaction->taecel_success = $statusTXN->success;

            // $trasnsaction->taecel_message =  $requestTXN->message;

            // // si la recarga se rechaza despues de la peticion
            // if ($statusTXN->success == false) {

                

            //     if ($statusTXN->data) {

            //         $trasnsaction->taecel_status = $statusTXN->data->Status;

            //         $trasnsaction->taecel_message = $statusTXN->message;

            //         $trasnsaction->taecel_timeout = $statusTXN->data->Timeout;

                   
            //     }

            //     $response = [
            //         'success' => $statusTXN->success,
            //         'message' => $statusTXN->message
            //     ];

            //     $trasnsaction->save();

            //     return $response;
            // } else if ($statusTXN->success == true) {


            //     $trasnsaction->taecel_status = $statusTXN->data->Status;

            //     $trasnsaction->taecel_nota = $statusTXN->data->Nota;

            //     $trasnsaction->taecel_folio = $statusTXN->data->Folio;

            //     $trasnsaction->taecel_timeout = $statusTXN->data->Timeout;

            //     $trasnsaction->save();

            //     $response = [
            //         'success' => $statusTXN->success,
            //         'message' => $statusTXN->data->Nota
            //     ];

            //     //cambia el estatus de la linea y el icc a activado y vendido
            //     $linea->setStatus('Activado');

            //     $linea->icc->setStatus('Vendido');

            //     return $response;
            // }
        }


    }



    public function taecel($key, $nip, $producto, $referencia, $transId, $method)
    {
        Http::fake([
            
            'https://taecel.com/app/api/RequestTXN' => Http::response([
                'success' => true,
                'message'=> 'Consulta Exitosa',
                'data' => ['transID' => '200901979343'],


            
            ], 200, ['Headers']),


            'https://taecel.com/app/api/statusTXN' => Http::response(':(
                 Error intencional en formato de respuesta, se debe de ejecutar nuevamente el Método StatusTXN para Revisar el Status de la Transacción
                  :/', 200, ['Headers']),
        
          
        
        ]);
        
        
        $response = Http::asForm()->post("https://taecel.com/app/api/".$method, [
            'key' => $key,
            'nip' => $nip,
            'producto' => $producto,
            'referencia' => $referencia,

        ]);

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Linea  $linea
     * @return \Illuminate\Http\Response
     */
    public function show(Linea $linea)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Linea  $linea
     * @return \Illuminate\Http\Response
     */
    public function edit(Linea $linea)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Linea  $linea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Linea $linea)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Linea  $linea
     * @return \Illuminate\Http\Response
     */
    public function destroy(Linea $linea)
    {
        //
    }
}
