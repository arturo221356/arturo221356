<?php

namespace App\Http\Controllers;

use App\Caja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Inventario;
use App\Http\Resources\CajasResource;

class CajaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Caja  $caja
     * @return \Illuminate\Http\Response
     */
    public function show(Caja $caja)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Caja  $caja
     * @return \Illuminate\Http\Response
     */
    public function edit(Caja $caja)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Caja  $caja
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Caja $caja)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Caja  $caja
     * @return \Illuminate\Http\Response
     */
    public function destroy(Caja $caja)
    {
        //
    }
    public function getCajas(Request $request){

        $cajas = Caja::cajasForUser()->with('cajable')->get();

        return CajasResource::collection($cajas);
    }
    public function getOwnCaja(Request $request){

        if(isset(Auth::User()->caja)){
            $caja = Auth::User()->caja;

            return new CajasResource($caja);
        }else{
            return [];
        }
       
       
    }

}
