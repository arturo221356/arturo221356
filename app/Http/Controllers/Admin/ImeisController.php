<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imei;
use App\Sucursal;
use App\Equipo;
use Illuminate\Support\Facades\Validator;

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
        return view('admin.productos.imeis.create' , compact("sucursales","equipos"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(request $request)
    {

       
        $imeis = explode("\n", str_replace("\r", "", $request['imei']));
        

        
        foreach($imeis as $imei){
            
            $data = ['imei' => $imei];
            
            $newImei = new Imei;

            $newImei->imei = $imei;
            
            $newImei->status_id = 5;
            
            $newImei->sucursal_id = $request->sucursal;
            
            $newImei->equipo_id = $request->equipo;    
           
            $validator = Validator::make($data, [
                'imei' => 'numeric|unique:imeis,imei',

            ]);
            if ($validator->fails()) {
                echo "errror";
                
            }else{
                $newImei->save();
                
                
            }
            

            
            

        
        }

        return redirect('/inventario/equipos');

        

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
    public function edit($imei)
    {
        $imei = Imei::findorfail($imei);
        $sucursales = Sucursal::all()->sortBy('nombre_sucursal');
        $equipos = Equipo::all()->sortby('marca');
        return view('admin.productos.imeis.edit',compact("imei","sucursales","equipos"));

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
        return redirect("/inventario/equipos");
    }
}
