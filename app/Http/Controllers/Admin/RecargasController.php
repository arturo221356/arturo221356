<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Recarga;
use Illuminate\Http\Request;

class RecargasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $recargas= Recarga::all();
        return view("admin.productos.recargas.index",compact('recargas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.productos.recargas.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,['nombre_recarga'=>'required','monto_recarga'=>'required|max:1000|integer|unique:recargas,monto',]);
        $recarga=new Recarga;
        $recarga->nombre=$request->nombre_recarga;
        $recarga->monto=$request->monto_recarga;
        $recarga->save();
        return redirect("/admin/productos/recargas");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Recarga  $recarga
     * @return \Illuminate\Http\Response
     */
    public function show(Recarga $recarga)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Recarga  $recarga
     * @return \Illuminate\Http\Response
     */
    public function edit(Recarga $recarga)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Recarga  $recarga
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recarga $recarga)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Recarga  $recarga
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recarga $recarga)
    {
        $recarga->delete();
        return redirect("/admin/productos/recargas");
    }
}
