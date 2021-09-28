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
        return $chip;
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

            if ($inventario_id == 'all') {
                $inventariosIds = $user->getInventariosForUserIds();
            } else {
                if (in_array($inventario_id, $user->getInventariosForUserIds()->toArray())) {
                    $inventariosIds = [$inventario_id];
                } else {
                    $inventariosIds = [];
                }
            }

            $chips = Chip::whereBetween('activated_at', [$initialDate, $finalDate])
                ->whereHas('linea', function ($query) {
                    $query->currentStatus(['Activado', 'Sin Saldo']);
                })
                ->whereHas('linea.icc.inventario', function ($query) use ($inventariosIds) {

                    $query->whereIn('inventario_id', $inventariosIds);
                })
                ->orderBy('activated_at', 'asc')
                ->get();



            $response = ChipResource::collection($chips);


            return $response;
        }
    }


    public function recargaChip(Request $request)
    {

        $message = [];

        $dn = $request->dn;

        $appRequest = $request->appRequest;


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

        if ($appRequest == true) {


            $user = Auth::user();

            if (!$user) {
                return response([
                    'success' => false,
                    'message' => 'Usuario no autentificado',

                ], 401);
            }
            if ($user->inventario_propio == true) {
                if ($user->inventario->id != $inventario->id) {

                    $message = [
                        'success' => false,
                        'message' => 'Linea no pertenece a tu inventario',

                    ];

                    return json_encode($message);
                }
            } else {
                if (!in_array($inventario->id, $user->getInventariosForUserIds()->toArray())) {
                    $message = [
                        'success' => false,
                        'message' => 'Linea no pertenece a tu inventario',

                    ];

                    return json_encode($message);
                }
            }




            if (!$user->hasPermissionTo('activar chip')) {

                $message = [
                    'success' => false,
                    'message' => 'Usuario no tiene permiso de activar chips',

                ];

                return json_encode($message);
            }
        }



        if (!in_array($inventario->inventarioable_type, array('App\Grupo', 'App\User'), true)) {

            $message = [
                'success' => false,
                'message' => 'Solo para usuarios externos o grupos externos',

            ];

            return json_encode($message);
        }

        $inventarioable = $inventario->inventarioable;

        //revisa que el inventario tenga permisos para activar chips desde activa chip
        //cambie de inventario a user
        if (!$inventarioable->hasPermissionTo('activar chip')) {

            $message = [
                'success' => false,
                'message' => 'Inventario no tiene permiso de activar chips',

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

        $newTrasnsaction =  (new Transaction)->newTaecelTransaction($taecelKey, $taecelNip, $dn, $recarga->id, $inventario->id, true);


        if ($newTrasnsaction->taecel_success == false) {

            $response =  json_encode([
                'success' =>  false,
                'message' => $newTrasnsaction->taecel_message,
            ]);

            $linea->deleteStatus('Proceso');
        } else if ($newTrasnsaction->taecel_success == true) {



            $linea->setStatus('Activado');

            $linea->icc->setStatus('Vendido');

            $chip = $linea->productoable;

            $chip->transaction_id = $newTrasnsaction->id;

            $chip->activated_at = now();

            $chip->save();

            $response =  json_encode([
                'success' =>  true,
                'message' =>$newTrasnsaction->taecel_message . ",  Folio: " . $newTrasnsaction->taecel_folio . " Monto: " . $newTrasnsaction->monto,
            ]);

            $linea->deleteStatus('Proceso');


            if (Auth::check()) {

                $linea->user()->associate(Auth::user());

                $linea->save();
            }
        }




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

        if (!$user->hasPermissionTo('preactivar masivo')) {
            return 'no tienes permiso';
        }

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

                    // if ($user->hasPermissionTo('distribution inventarios')) {

                    //     $icc = Icc::iccInUserDistribution($requestIcc)->first();
                    // } else {
                    //     $icc = Icc::iccInUserInventario($requestIcc)->first();
                    // }
                    $inventariosIds = $user->getInventariosForUserIds();

                    $icc = Icc::where('icc', $requestIcc)->whereIn('inventario_id', $inventariosIds)->otherCurrentStatus(['Vendido', 'Traslado'])->first();


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
