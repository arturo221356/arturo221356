<?php

namespace App\Http\Controllers;

use App\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ProductoGeneral;
use App\Transaction;
use App\Taecel;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('venta.index');
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
        $user = Auth::user();

        $productos = json_decode(json_encode($request->productos));

        $venta = new Venta;

        $venta->user()->associate($user);

        $venta->inventario()->associate($user->inventariosAsignados()->first());

        $total = 0;

        $venta->save();

        if ($request->comentario) {
    
            $venta->comment()->create(['comment' => $request->comentario]);
        }

        if($productos){

            foreach($productos as $producto){

               $total += $producto->precio; 

                switch($producto->type){
                    
                    
                    case 'recargas':




                    break;

                    case 'imeis':


                    break;

                    case 'generales':

                        $productoGeneral = new ProductoGeneral;

                        $productoGeneral->price = $producto->precio;

                        $productoGeneral->name = $producto->serie;

                        $productoGeneral->description = $producto->descripcion;

                        $productoGeneral->save();

                        $venta->generalProducts()->attach($productoGeneral,['price' => $producto->precio]);
                

                    break;

                    case 'iccs':


                    break;





                    
                }



            }
           
        }

        $venta->total = $total;

        $venta->save();


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function show(Venta $venta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function edit(Venta $venta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Venta $venta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Venta $venta)
    {
        //
    }
}
