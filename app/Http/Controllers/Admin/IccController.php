<?php

namespace App\Http\Controllers\Admin;

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
        $iccs = Icc::find(1)->get();

        $products = IccProduct::all();



        return view('admin.index', compact("iccs", "products"));
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
       //array de mensajes de error y exitosos
        $errores = [];

        $exitosos = [];

        $series = json_decode($request->data);

        $sucursal = $request->input('sucursal_id');




        //si el request contiene un archivo excel procede con esta funcion 
        if ($request->hasFile('file')) {

            $file = $request->file('file');



            //datos enviados al import que vienen desde la request
            $data = [
                'sucursal_id' => $sucursal,
                'status_id' => 1,

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

            foreach ($series as $data) {

                $serie = [];

                $serie = ['serie' => $data->serie];

                

                $icc = new Icc([
                    'icc' => $data->serie,
                    'status_id' => 1,
                    'sucursal_id' => $data->sucursal,
                   


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

                    $err['serie'] = $data->serie;



                    foreach ($validator->errors()->toArray() as $error) {



                        foreach ($error as $sub_error) {
                            array_push($errorList, $sub_error);
                        }
                    }
                    $err['errores'] = $errorList;
                    array_push($errores, $err);
                } else {

                    $icc->save();
                    array_push($exitosos, $serie);
                }
            }
        }

        //regresa los mensajes de errores y exitosos
        return ['errors' => $errores, 'success' => $exitosos];
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Icc  $icc
     * @return \Illuminate\Http\Response
     */
    public function show(Icc $icc)
    {
        //
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
        $icc = Icc::findorfail($id);


        $icc->update($request->all());


        if ($request->comment != NULL) {

            $icc->comment()->updateOrCreate([], ['comment' => $request->comment]);
        } else {

            $icc->comment()->delete();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Icc  $icc
     * @return \Illuminate\Http\Response
     */
    public function destroy(Icc $icc)
    {
        $icc->details()->delete();

        $icc->delete();
       
    }
}
