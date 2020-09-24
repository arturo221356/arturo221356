<?php

namespace App\Http\Controllers;

use App\Linea;

use App\Icc;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class LineaController extends Controller
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
    public function verificarIcc(Request $request)
    {
        $requestIcc = $request->icc;

        $user = Auth::user();

        if ($user->can('distribution inventarios')) {

            $icc = Icc::where('icc', $requestIcc)->with('linea')
                ->otherCurrentStatus(['Vendido', 'Traslado'])

                ->whereHas('inventario', function ($query) {
                    $user = Auth::user();
                    $query->where('distribution_id', $user->distribution->id);
                })->get();
        }else{
           
            $icc = Icc::where('icc', $requestIcc)->with('linea')
            ->otherCurrentStatus(['Vendido', 'Traslado'])

            ->whereHas('inventario', function ($query) {
                $user = Auth::user();
                $inventariosIds =  $user->InventariosAsignados()->pluck('inventarios.id')->toArray();
                $query->whereIn('inventario_id',$inventariosIds);
            })->get();
        }

        return $icc;
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
    public function preactivarPrepago(Request $request)
    {
        $requestIcc = $request->icc;

        $icc = Icc::where('icc', $requestIcc)->otherCurrentStatus(['Vendido', 'Traslado']);

        $icc->linea->create([
            'dn' => '3310512007',

        ])->attach($producto);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Linea  $linea
     * @return \Illuminate\Http\Response
     */
    public function show(Linea $linea)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Linea  $linea
     * @return \Illuminate\Http\Response
     */
    public function edit(Linea $linea)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Linea  $linea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Linea $linea)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Linea  $linea
     * @return \Illuminate\Http\Response
     */
    public function destroy(Linea $linea)
    {
        //
    }
}
