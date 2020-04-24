<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imei;
use App\Sucursal;
use App\Equipo;
use App\Status;
use App\Comment;
use Dotenv\Regex\Success;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ImeiResource as ImeiResource;

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
        $sucursales = Sucursal::all()->sortBy('nombre_sucursal');
        $equipos = Equipo::all()->sortby('marca');
        return view('admin.productos.imeis.create', compact("sucursales", "equipos"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(request $request)
    {
        $errores = [];
        $exitosos = [];
        foreach ($request->data as $data) {

            $prueba = [];
            
            $prueba = ['imei' => $data['serie']];

            $imei = new Imei([
                'imei' => $data['serie'],
                'status_id' => 1,
                'sucursal_id' => $data['sucursal'],
                'equipo_id' => $data['equipo']

            ]);

            $validator = Validator::make($prueba, [
                'imei' => 'unique:imeis,imei|digits:15',



            ]);
            if ($validator->fails()) {

                array_push($errores,$prueba);

               
            } else {

                $imei->save();
                
            }
            

        }


        return $errores;
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
        $imei = Imei::findorfail($id);

        $sucursales = Sucursal::all();

        return view("admin.productos.imeis.edit", compact("imei"))->with("sucursales");
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

        $imei = Imei::findorfail($id);


        $imei->update($request->all());


        if ($request->comment != NULL) {

            $imei->comment()->updateOrCreate([], ['comment' => $request->comment]);
        } else {

            $imei->comment()->delete();
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


        $imei->delete();
    }
}
