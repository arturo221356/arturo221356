<?php

namespace App\Http\Controllers;

use App\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ProductoGeneral;
use App\Transaction;
use App\Taecel;
use App\Imei;
use App\Icc;
use App\Recarga;

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

        $inventario = $user->inventariosAsignados()->first();

        $taecelKey = $inventario->distribution->taecel_key;

        $taecelNip = $inventario->distribution->taecel_nip;

        $productos = json_decode(json_encode($request->productos));

        $venta = new Venta;

        $venta->user()->associate($user);

        $venta->inventario()->associate($inventario);

        $total = 0;

        $venta->save();

        if ($request->comentario) {

            $venta->comment()->create(['comment' => $request->comentario]);
        }

        if ($productos) {

            foreach ($productos as $producto) {

                $total += $producto->precio;

                switch ($producto->type) {


                    case 'recargas':

                        $dn = $producto->dn;

                        $recarga = Recarga::findOrFail($producto->recargaId);

                        $requestTXN =  (new Taecel)->taecelRequestTXN($taecelKey, $taecelNip, $recarga->taecel_code, $dn);

                        $taecelRequest = json_decode($requestTXN);

                        $trasnsaction = Transaction::create([

                            'taecel' => true,

                            'monto' => $recarga->monto,

                            'dn' => $dn,

                            'company_id' => $recarga->company_id,

                            'recarga_id' => $recarga->id,

                            'inventario_id' => $inventario->id,

                            'taecel_success' => $taecelRequest->success,

                            'taecel_message' => $taecelRequest->message,

                        ]);
                        if ($taecelRequest->data) {
                            $trasnsaction->taecel_transID = $taecelRequest->data->transID;
                
                            $trasnsaction->save();
                        }
                        if ($taecelRequest->success == false) {

                            $response =  json_encode([
                                'success' =>  false,
                                'message' => $taecelRequest->message,
                            ]);
                
                           
                        } else if ($taecelRequest->success == true) {
                
                            $transID = $taecelRequest->data->transID;
                
                            $statusTXN =  (new Taecel)->TaecelStatusTXN($taecelKey, $taecelNip, $transID);
                
                            $taecelStatusTXN = json_decode($statusTXN);
                
                            if ($taecelStatusTXN->data) {
                
                                $trasnsaction->taecel_status = $taecelStatusTXN->data->Status;
                
                                $trasnsaction->taecel_message = $taecelStatusTXN->message;
                
                                $trasnsaction->taecel_timeout = $taecelStatusTXN->data->Timeout;
                
                                $trasnsaction->taecel_folio = $taecelStatusTXN->data->Folio;
                
                                $trasnsaction->taecel_nota = $taecelStatusTXN->data->Nota;
                            }
                
                            if ($taecelStatusTXN->success  == true) {
                
                                $response =  json_encode([
                                    'success' =>  true,
                                    'message' => $taecelRequest->message . ",  Folio: " . $taecelStatusTXN->data->Folio . " Monto: " . $taecelStatusTXN->data->Monto,
                                ]);
                            } else if ($taecelStatusTXN->success  == false) {
                               
                               
                
                                $response =  json_encode([
                                    'success' =>  false,
                                    'message' => $taecelStatusTXN->message,
                                ]);
                            }
                        }
                
                        $trasnsaction->save();

                        $venta->transactions()->attach($trasnsaction, ['price' => $recarga->monto]);



                        break;

                    case 'imeis':

                        $imei = Imei::findOrFail($producto->id);

                        $imei->setStatus('Vendido');

                        $venta->imeis()->attach($imei, ['price' => $imei->equipo->precio]);



                        break;

                    case 'generales':

                        $productoGeneral = new ProductoGeneral;

                        $productoGeneral->price = $producto->precio;

                        $productoGeneral->name = $producto->serie;

                        $productoGeneral->description = $producto->descripcion;

                        $productoGeneral->save();

                        $venta->generalProducts()->attach($productoGeneral, ['price' => $producto->precio]);


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
