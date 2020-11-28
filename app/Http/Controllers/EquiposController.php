<?php

namespace App\Http\Controllers;

use App\Equipo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Distribution;

class EquiposController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $userDistribution = Auth::User()->distribution->id;

            $distribution = Distribution::find($userDistribution);
        
            $equipos = $distribution->equipos()->get();
        
            return response()->json($equipos);

        } else {
            return view('admin.productos.equipos.index');
        }

        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return view('admin.productos.equipos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,
        ['marca'=>'required',
        'modelo'=>'required',
        'precio'=>'required|numeric',
        'costo'=>'required|numeric',

        ]);
        $equipo =new Equipo;
        $equipo->marca=$request->marca;
        $equipo->modelo=$request->modelo;
        $equipo->precio=$request->precio;
        $equipo->costo=$request->costo;
        $equipo->distribution_id = Auth::user()->distribution->id;
        $equipo->save();
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Equipo  $equipo
     * @return \Illuminate\Http\Response
     */
    public function show(Equipo $equipo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Equipo  $equipo
     * @return \Illuminate\Http\Response
     */
    public function edit(Equipo $equipo)
    {
        return view('admin.productos.equipos.edit',compact('equipo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Equipo  $equipo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Equipo $equipo)
    {
        
        $this->validate($request,
        ['marca'=>'required',
        'modelo'=>'required',
        'precio'=>'required|numeric',
        'costo'=>'required|numeric',

        ]);
        $equipo->update($request->all());
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Equipo  $equipo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Equipo $equipo)
    {

        $message = '';

        $variant = '';

        $title = '';

        //verifica que no existan series con este equipo anted de eliminarlos 
        $seriesHijas = $equipo->imeis()->count();
        if($seriesHijas){

            $message = "$equipo->marca $equipo->modelo tiene $seriesHijas series, eliminalas antes !!";
            
            $variant = "danger";
            
            $title = 'Error';

        }else{
            
            $equipo->delete();
            
            $message = "$equipo->name Eliminado con exito";
            
            $variant = "warning";
            
            $title = 'Exito';

        }

        
        
       return ['message'=>$message,'variant' => $variant,'title'=>$title];
    }



}
