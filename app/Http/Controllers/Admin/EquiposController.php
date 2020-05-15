<?php

namespace App\Http\Controllers\Admin;

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

            $userDistribution = Auth::User()->distribution()->id;

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
        return view('admin.productos.equipos.create');
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
        ['marca_equipo'=>'required',
        'modelo_equipo'=>'required',
        'precio_equipo'=>'required|numeric',
        'costo_equipo'=>'required|numeric',

        ]);
        $equipo =new Equipo;
        $equipo->marca=$request->marca_equipo;
        $equipo->modelo=$request->modelo_equipo;
        $equipo->precio=$request->precio_equipo;
        $equipo->costo=$request->costo_equipo;
        $equipo->save();
        return redirect("/admin/productos/equipos");
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
        return redirect("/admin/productos/equipos");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Equipo  $equipo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Equipo $equipo)
    {
        $equipo->delete();
        return redirect("/admin/productos/equipos");
    }



}
