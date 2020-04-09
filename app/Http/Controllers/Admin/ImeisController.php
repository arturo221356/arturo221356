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
            
            $newImei->status_id = 5;
            
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

       return redirect('/inventario')->withErrors($errorMsgs);

       

        

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
        
        
        if ($request->comment !=NULL) {
            
            $imei->comment()->updateOrCreate([],['comment' => $request->comment]);

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
