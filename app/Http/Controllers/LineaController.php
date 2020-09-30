<?php

namespace App\Http\Controllers;

use App\Linea;

use App\Icc;

use App\Chip;

use App\Recarga;

use App\Transaction;

use App\Inventario;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use App\Imports\LineaImport;

use Illuminate\Support\Facades\Http;


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

        // // $recargable == 'true' ? $response = 'verdadero' : $response = 'falso';

        // return $response;

        $user = Auth::user();

        if ($user->can('preactivar linea'))

            $iccs = json_decode($request->data);

        $errores = [];

        $exitosos = [];

        if ($request->hasFile('file')) {

            $file = $request->file('file');


            $lineaImport = new LineaImport();

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

                            
                            $recargable == 'true' ? $linea->setStatus('Recargable',$montoRecarga) : $linea->setStatus('Preactiva');
                            

                        
                        
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

        $monto = 50;

        $dn = $request->dn;

        $linea = Linea::where('dn', $dn)->first();

        $monto = $linea->status()->reason;


        if ($linea == null) {

            $message = [
                'success' => false,
                'message' => 'Numero no existe en la base de datos',

            ];

            return json_encode($message);
        }
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

        // if (!$inventario->hasPermissionTo('activar chip', 'web')) {

        //     $message = [
        //         'success' => false,
        //         'message' => 'No tienes permiso de activar chips portate bien',

        //     ];

        //     return json_encode($message);
        // }


        $recarga = Recarga::where([['monto', '=', $monto], ['company_id', '=', $linea->icc->company_id]])->first();

        $taecelKey = $inventario->distribution->taecel_key;

        $taecelNip = $inventario->distribution->taecel_nip;

        $res = Http::asForm()->post('https://taecel.com/app/api/RequestTXN', [
            'key' => $taecelKey,
            'nip' => $taecelNip,
            'producto' => $recarga->taecel_code,
            'referencia' => $dn
        ]);

        $response = json_decode($res);

        $trasnsaction = new Transaction;

        $trasnsaction->taecel = true;

        $trasnsaction->taecel_success = $response->success;

        if ($response->data) {
            $trasnsaction->taecel_transID = $response->data->transID;
        }


        $trasnsaction->taecel_message =  $response->message;

        $trasnsaction->monto = $recarga->monto;

        $trasnsaction->dn = $dn;

        $trasnsaction->company_id = $recarga->company_id;

        $trasnsaction->recarga_id = $recarga->id;

        $trasnsaction->inventario_id = $linea->icc->inventario_id;

        $trasnsaction->save();

        if ($response->message == 'Consulta Exitosa') {

            $linea->setStatus('Activado');

            $linea->icc->setStatus('Vendido');

            $response = [
                'success' =>  true,
                'message' => 'Numero recargado con exito'
            ];
        } else {
            $response = [
                'success' =>  $response->success,
                'message' => $response->message
            ];
        }


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
