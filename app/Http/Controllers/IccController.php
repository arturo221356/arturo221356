<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Icc;
use App\IccProduct;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Imports\IccsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class IccController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        $user = Auth::user();

        if ($user->can('store stock')) {
            //array de mensajes de error y exitosos
            $errores = [];

            $exitosos = [];

            $series = json_decode($request->data);

            $inventario = $request->input('inventario_id');

            $company = $request->input('company_id');

            $type = $request->input('icc_type_id');


            //si el request contiene un archivo excel procede con esta funcion 
            if ($request->hasFile('file')) {

                $file = $request->file('file');



                //datos enviados al import que vienen desde la request
                $data = [
                    'inventario_id' => $inventario,
                    'status_id' => 1,
                    'icc_type_id' => $type,
                    'company_id' => $company,

                ];


                $imeiImport = new IccsImport($data);
                $imeiImport->import($file);

                //obtiene los los mensales de error
                $imeiValidationErrors = $imeiImport->getErrors();
                $imeiValidationSuccess = $imeiImport->getsuccess();

                //pushea los mensajes a los arrays
                foreach ($imeiValidationErrors as $validationErr) {
                    $errors = array_push($errores, $validationErr);
                }
                foreach ($imeiValidationSuccess as $validationSucc) {
                    $errors = array_push($exitosos, $validationSucc);
                }
            }



            if ($series) {

                foreach ($series as $datos) {

                    $serie = [];

                    $serie = ['serie' => $datos->serie];



                    $icc = new Icc([

                        'icc' => $datos->serie,
                        'status_id' => 1,
                        'inventario_id' => $datos->inventario->id,
                        'company_id' => $datos->company->id,
                        'icc_type_id' => $datos->simType->id,



                    ]);

                    // Crea la matriz de mensajes.
                    $mensajes = array(
                        'unique' => 'ya existe en la base de datos.',
                        'digits' => 'La serie tiene que se numerica y de 19 digitos'
                    );


                    $validator = Validator::make($serie, [
                        'serie' => 'required|unique:iccs,icc|digits:19',



                    ], $mensajes);
                    if ($validator->fails()) {

                        $err = [];

                        $errorList = [];

                        $err['serie'] = $datos->serie;



                        foreach ($validator->errors()->toArray() as $error) {



                            foreach ($error as $sub_error) {
                                array_push($errorList, $sub_error);
                            }
                        }
                        $err['errores'] = $errorList;
                        array_push($errores, $err);
                    } else {

                        $icc->save();
                        $icc->setStatus('Disponible');
                        array_push($exitosos, $serie);
                    }
                }
            }

            //regresa los mensajes de errores y exitosos
            return ['errors' => $errores, 'success' => $exitosos];
        }
        return ['errors' => ['usuario sin permisos']];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Icc  $icc
     * @return \Illuminate\Http\Response
     */
    public function show(Icc $icc)
    {
        return $icc;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Icc  $icc
     * @return \Illuminate\Http\Response
     */
    public function edit(Icc $icc)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Icc  $icc
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $icc = Icc::findorfail($id);

        if ($user->can('update stock')) {

            if ($request->comment != NULL) {

                $icc->comment()->updateOrCreate([], ['comment' => $request->comment]);
            } else {

                $icc->comment()->delete();
            }
            $icc->setStatus($request->status);
        
            if ($user->can('full update stock')) {

                $icc->inventario_id = $request->inventario_id;

                $icc->company_id = $request->company_id;

                $icc->icc_type_id = $request->icc_type_id;

                if($request->lineaDn){
                    
                    $linea = $icc->linea;

                    $linea->dn = $request->lineaDn;

                    $montoRecarga= null;

                    $lineaStatus = null;

                    if($request->lineaStatus == 'Recargable'){
                        if($user->hasPermissionTo('asignar recarga')){
                            $montoRecarga = $request->montoRecarga;

                            $lineaStatus = $request->lineaStatus;

                            $linea->setStatus($lineaStatus,$montoRecarga);
                        }

                    }else{
                        $lineaStatus = $request->lineaStatus;

                        $montoRecarga = null;

                        $linea->setStatus($lineaStatus,$montoRecarga);
                    }

                    

                    

                    $linea->save();
                }

                

            }

            $icc->save();
            
        }
        return $request;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Icc  $icc
     * @return \Illuminate\Http\Response
     */
    public function destroy(Icc $icc)
    {
        $user = Auth::user();
        if ($user->can('destroy stock')) {

            $linea = $icc->linea();

            $linea->delete();

            $icc->setStatus('Eliminado');

            $icc->delete();
        }
    }


    public function restore(Request $request)
    {
        $user = Auth::user();
        if ($user->can('destroy stock')) {
        $id = $request;
        
        $icc = Icc::onlyTrashed()->findOrfail($id)->first();

        $icc->restore();

        $icc->deleteStatus('Eliminado');

        $linea = $icc->linea()->withTrashed();

        $linea->restore();

        return $icc;
        }

        
    }
}
