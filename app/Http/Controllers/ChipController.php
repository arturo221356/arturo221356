<?php

namespace App\Http\Controllers;

use App\Chip;

use App\Icc;

use App\Transaction;

use App\Linea;

use App\Taecel;


use App\Recarga;


use Illuminate\Support\Facades\Validator;

use App\Imports\LineaImport;


use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use App\Http\Resources\ChipResource;

use Illuminate\Support\Carbon;


class ChipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Chip  $chip
     * @return \Illuminate\Http\Response
     */
    public function show(Chip $chip)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Chip  $chip
     * @return \Illuminate\Http\Response
     */
    public function edit(Chip $chip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Chip  $chip
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chip $chip)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Chip  $chip
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chip $chip)
    {
        //
    }

    public function getActivated(Request $request)
    {

        $user = Auth::user();

        if ($request->ajax()) {

            $inventario_id = $request->inventario_id;

            $initialDate = Carbon::parse($request->initial_date)->startOfDay()->toDateTimeString();

            $finalDate = Carbon::parse($request->final_date)->endOfDay()->toDateTimeString();


            if ($inventario_id === 'all') {

                if ($user->can('distribution inventarios')) {

                    $chips = Chip::DistributionActivatedChips($initialDate, $finalDate);
                } else {
                    $chips = Chip::InUserInventarioActivatedChips($initialDate, $finalDate);
                }
            } else {

                $chips = Chip::InventarioActivatedChips($initialDate, $finalDate, $inventario_id);
            }


            $response = ChipResource::collection($chips);


            return $response;
        }
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

                case 'Exportada':
                    $message['message'] = 'Numero exportado';
                    break;
                case 'Proceso':
                    $message['message'] = 'Numero con falla durante proceso de recarga contacta al distribuidor';
                    break;
                default:
                    $message['message'] = 'Numero activado anteriormente';
                    break;
            }


            return json_encode($message);
        }


        $inventario = $linea->icc->inventario;


        //revisa que el inventario tenga permisos para activar chips desde activa chip

        if (!$inventario->hasPermissionTo('activar chip', 'web')) {

            $message = [
                'success' => false,
                'message' => 'No tienes permiso de activar chips portate bien',

            ];

            return json_encode($message);
        }




        //monto de recarga esta guardado en la razon del status

        $monto = $linea->status()->reason;


        //selecciona la recarga conforme a la compañia del icc seleccionado y monto igual a la razon del status

        $recarga = Recarga::where([['monto', '=', $monto], ['company_id', '=', $linea->icc->company_id]])->first();


        // claves taecel pertenecientes a la distribucion

        $taecelKey = $inventario->distribution->taecel_key;

        $taecelNip = $inventario->distribution->taecel_nip;

        //cambia el status de la linea a proceso para evitar errores que se duplique la recarga 
        $linea->setStatus('Proceso', 'si ves esto es porque hubo un error al procesar la recarga, verifica la transaccion');

        $requestTXN = (new Taecel)->taecelRequestTXN($taecelKey, $taecelNip, $recarga->taecel_code, $dn);

        $taecelRequest = json_decode($requestTXN);


        $trasnsaction = Transaction::create([
            'taecel' => true,

            'monto' => $recarga->monto,

            'dn' => $dn,

            'company_id' => $recarga->company_id,

            'recarga_id' => $recarga->id,

            'inventario_id' => $linea->icc->inventario_id,

            'taecel_success' => $taecelRequest->success,

            'taecel_message' => $taecelRequest->message,

        ]);
        if ($taecelRequest->data) {
            $trasnsaction->taecel_transID = $taecelRequest->data->transID;

            $trasnsaction->save();
        }



        if ($taecelRequest->success == false) {

            $response =  json_encode([
                'success' =>  false,
                'message' => $taecelRequest->message,
            ]);

            $linea->deleteStatus('Proceso');
        } else if ($taecelRequest->success == true) {

            $transID = $taecelRequest->data->transID;

            $statusTXN = (new Taecel)->TaecelStatusTXN($taecelKey, $taecelNip, $transID);

            $taecelStatusTXN = json_decode($statusTXN);

            if ($taecelStatusTXN->data) {

                $trasnsaction->taecel_status = $taecelStatusTXN->data->Status;

                $trasnsaction->taecel_message = $taecelStatusTXN->message;

                $trasnsaction->taecel_timeout = $taecelStatusTXN->data->Timeout;

                $trasnsaction->taecel_folio = $taecelStatusTXN->data->Folio;

                $trasnsaction->taecel_nota = $taecelStatusTXN->data->Nota;
            }

            if ($taecelStatusTXN->success  == true) {

                $linea->setStatus('Activado');

                $linea->icc->setStatus('Vendido');

                $chip = $linea->productoable;

                $chip->transaction_id = $trasnsaction->id;

                $chip->activated_at = now();

                $chip->save();

                $response =  json_encode([
                    'success' =>  true,
                    'message' => $taecelRequest->message . ",  Folio: " . $taecelStatusTXN->data->Folio . " Monto: " . $taecelStatusTXN->data->Monto,
                ]);
            } else if ($taecelStatusTXN->success  == false) {
                //cambia el estatus de la linea y el icc a activado y vendido
                $linea->deleteStatus('Proceso');

                $response =  json_encode([
                    'success' =>  false,
                    'message' => $taecelStatusTXN->message,
                ]);
            }
        }

        $trasnsaction->save();

        return  $response;
    }






    public function preactivarPrepago(Request $request)
    {

        $recargable = $request->recargable;



        $montoRecarga = $request->monto;


        $user = Auth::user();

        if ($recargable == 'true' && !$user->hasPermissionTo('asignar recarga')) {

            $recargable = 'false';
        }

        if ($user->hasPermissionTo('preactivar linea'))

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

                    if ($user->hasPermissionTo('distribution inventarios')) {

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

                            $chip = Chip::create([
                                'preactivated_at' => now()
                            ]);

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
}
