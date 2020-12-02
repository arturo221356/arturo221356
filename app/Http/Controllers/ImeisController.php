<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imei;
use Dotenv\Regex\Success;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ImeiResource as ImeiResource;
use App\Imports\ImeisImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;


class ImeisController extends Controller
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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(request $request)
    {
        $user = Auth::user();
        
        if($user->can('store stock')){
        //array de mensajes de error y exitosos
        $errores = [];
        
        $exitosos = [];
        
        $series = json_decode($request->data);

        $inventario = $request->input('inventario_id');

        $equipo = $request->input('equipo_id');



        //si el request contiene un archivo excel procede con esta funcion 
        if ($request->hasFile('file')) {

            $file = $request->file('file');

            

            //datos enviados al import que vienen desde la request
            $data = [
                'inventario_id' => $inventario,
                'equipo_id' => $equipo,     
                
            ];

            
            $imeiImport = new ImeisImport($data);
            $imeiImport->import($file);

            //obtiene los los mensales de error
            $imeiValidationErrors = $imeiImport->getErrors();
            $imeiValidationSuccess = $imeiImport->getsuccess();
            
            //pushea los mensajes a los arrays
            foreach($imeiValidationErrors as $validationErr){
                $errors = array_push($errores, $validationErr);
            }
            foreach($imeiValidationSuccess as $validationSucc){
                $errors = array_push($exitosos, $validationSucc);
            }
            
            
        } 



        if($series){

        foreach ($series as $data) {

            $serie = [];

            $serie = ['serie' => $data->serie];

            $imei = new Imei([
                'imei' => $data->serie,
                'inventario_id' => $data->inventario->id,
                'equipo_id' => $data->equipo->id,
                'status_id' =>1

            ]);
            

            // Crea la matriz de mensajes.
            $mensajes = array(
                'unique' => 'ya existe en la base de datos.',
                'digits' => 'La serie tiene que se numerica y de 15 digitos'
            );


            $validator = Validator::make($serie, [
                'serie' => 'required|unique:imeis,imei|digits:15',



            ], $mensajes);
            if ($validator->fails()) {

                $err = [];

                $errorList = [];

                $err['serie'] = $data->serie;



                foreach ($validator->errors()->toArray() as $error) {



                    foreach ($error as $sub_error) {
                        array_push($errorList, $sub_error);
                    }
                }
                $err['errores'] = $errorList;
                array_push($errores, $err);
            } else {

                $imei->save();
                $imei->setStatus('Disponible');
                array_push($exitosos, $serie);
            }
        }
    }

        //regresa los mensajes de errores y exitosos
        return ['errors' => $errores, 'success' => $exitosos];
        
    
    
    
    }   return ['errors' => ['usuario sin permisos']];
     
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Imei $imei)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        if($user->can('update stock')){

            $imei = Imei::findorfail($id);

            $imei->setStatus($request->status);

            if ($request->comment != NULL) {
    
                $imei->comment()->updateOrCreate([], ['comment' => $request->comment]);
            } else {
    
                $imei->comment()->delete();
            }

            if($user->can('full update stock')){
            


                $imei->update($request->all());
        
        
               
            }

            $imei->save();
        }
        


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(imei $imei)
    {

        $user = Auth::user();

        if ($user->can('destroy stock')) {

            $imei->delete();
        }
        
    }

    
    public function restore(Request $request)
    {
        $user = Auth::user();
        if ($user->can('destroy stock')) {
        $id = $request;
        
        $imei = Imei::onlyTrashed()->findOrfail($id)->first();

        $imei->restore();

        }

        
    }
}
