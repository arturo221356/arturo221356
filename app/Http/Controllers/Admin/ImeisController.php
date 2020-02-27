<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imei;
use App\Sucursal;
use App\Equipo;
use App\Status;
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

       
         $imeis = explode("\n", str_replace("\r", null, $request['imei']));

         

         $imeis = explode("\r\n", trim( $request['imei']));


        
        $errorMsgs = [];
        
        
        foreach($imeis as $imei){
            //$data = [];
            
            $data = ['imei' => $imei];

            //var_dump($data);
            
            $newImei = new Imei;

            $newImei->imei = $imei;
            
            $newImei->status_id = 1;
            
            $newImei->sucursal_id = $request->sucursal;
            
            $newImei->equipo_id = $request->equipo;    
           
            

            $validator = Validator::make($data, [
                'imei' => 'numeric|unique:imeis,imei|digits:15',
                
                

            ]);
            if ($validator->fails()) {
                
                
                $errorMsgs[$imei]= $imei;
                
               

                
                
            }else{
                
                
                $newImei->save();
                
                
            }
            
            
            
            

        
        }

       return redirect('/inventario/equipos')->withErrors($errorMsgs);

       

        

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
        $status = Status::all()->sortBy('status');
        $equipos = Equipo::all()->sortby('marca');
        return view('admin.productos.imeis.edit',compact("imei","sucursales","equipos","status"));

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
        $this->validate($request,['imei'=>'numeric|digits:15', Rule::unique('imei')->ignore($request->id),]);
        
        $imei = Imei::findOrFail($id);
        $imei->update($request->all());
        return redirect("/inventario/equipos");
      
        
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

    public function getimeis(Request $request){

       $sucursal = $request->sucursal;
        
        return ImeiResource::collection(Imei::where('sucursal_id', $sucursal)->get());


    }





}
