<?php

namespace App\Http\Controllers;

use App\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ProductoGeneral;
use App\Transaction;
use App\Imei;
use App\Icc;
use App\IccSubProduct;
use App\Recarga;
use App\Chip;
use App\Porta;
use App\Pospago;
use App\Remplazo;
use App\Cliente;
use App\Http\Resources\VentaResource;
use Illuminate\Support\Carbon;
use App\Telemarketing;
use App\Mail\VentaComprobante;
use Illuminate\Support\Facades\Mail;


class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        if($request->ajax()){

            $ventas = Venta::all();

            return $ventas;

        }else{

            return view('venta.index');

        }
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('venta.create');
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



                switch ($producto->type) {


                    case 'recargas':

                        $dn = $producto->dn;

                        $recarga = Recarga::findOrFail($producto->recargaId);

                        $newTrasnsaction =  (new Transaction)->newTaecelTransaction($taecelKey, $taecelNip, $dn, $recarga->id);

                        $transaction = json_decode($newTrasnsaction);

                        $montoRecargaVirtual = 0;

                        if ($transaction->success == true) {
                            $montoRecargaVirtual = $recarga->monto;
                            $total += $recarga->monto;
                        } else if ($transaction->success == false) {
                        }

                        $currentTransaction = Transaction::findOrFail($transaction->transaction_id);

                        $venta->transactions()->attach($currentTransaction, ['price' => $montoRecargaVirtual]);


                        break;

                    case 'imeis':

                        $statuses = array("Vendido", "Traslado");

                        $imei = Imei::findOrFail($producto->id);
 
                        if (!in_array($imei->status, $statuses)) {

                        $imei->setStatus('Vendido');

                        $venta->imeis()->attach($imei, ['price' => $imei->equipo->precio]);

                        $total += $imei->equipo->precio;

                        }

                        break;

                    case 'generales':

                        $productoGeneral = new ProductoGeneral;

                        $productoGeneral->price = $producto->precio;

                        $productoGeneral->name = $producto->serie;

                        $productoGeneral->description = $producto->descripcion;

                        $productoGeneral->save();

                        $venta->generalProducts()->attach($productoGeneral, ['price' => $producto->precio]);

                        $total += $producto->precio;

                        break;

                    case 'iccs':

                        $icc = Icc::findOrFail($producto->id);

                        $statuses = array("Vendido", "Traslado");
 
                        if (!in_array($icc->status, $statuses)) {


                            


                            // en caso de que el chip ya tenga linea asignada 
                            if ($icc->linea) {

                                $linea = $icc->linea;

                                $linea->icc_sub_product_id = $producto->iccSubProduct->id;

                                $linea->save();

                                $dn = $linea->dn;
                            } else {

                                //si no tiene linea asignada crea una con respecto a la request  
                                switch ($producto->iccProduct->id) {

                                        //en caso de que la request sea linea nueva prepago 
                                    case 1:
                                        //crea un nuevo chip
                                        $chip = Chip::create([
                                            'preactivated_at' => now()
                                        ]);

                                        break;
                                        //en caso de que la request sea portabilidad 
                                    case 2:

                                        if (isset($producto->porta->fvc)) {

                                            $stringFvc = Carbon::parse($producto->porta->fvc)->toIso8601String();

                                            $fvc = new Carbon($stringFvc);

                                            $fvc->hour = 9;
                                        }

                                        $fvc =
                                            // crea un nuevo chip
                                            $chip = Porta::create([
                                                'preactivated_at' => now(),
                                                'nip' => isset($producto->porta->nip) ? $producto->porta->nip : null,
                                                'temporal' => isset($producto->porta->temporal) ? $producto->porta->temporal : null,
                                                'trafico' => isset($producto->porta->trafico) ? $producto->porta->trafico : null,
                                                'fvc' => isset($fvc) ? $fvc : null,

                                            ]);

                                        break;

                                    case 3:

                                        // crea un nuevo pospago
                                        $chip = Pospago::create([

                                            'preactivated_at' => now(),
                                            'activated_at' => now(),

                                        ]);

                                        break;

                                    case 4:

                                        // crea una nueva linea remplazo

                                        $chip = Remplazo::create([

                                            'preactivated_at' => now(),
                                            'activated_at' => now(),
                                        ]);

                                        break;
                                    case 5:

                                        // crea una nueva linea telemarketing
                                        $chip = Telemarketing::create([


                                            'preactivated_at' => now(),
                                            'activated_at' => now(),
                                        ]);

                                        break;
                                }

                                // y le asigna una linea con el dn de la request 
                                $linea = $chip->linea()->create([
                                    'dn' => $producto->dn,
                                    'icc_product_id' => $producto->iccProduct->id,
                                    'icc_sub_product_id' => isset($producto->iccSubProduct->id) ? $producto->iccSubProduct->id : null,
                                    'icc_id' => $icc->id,

                                ]);

                                $linea->setStatus('Preactiva');
                            }

                            switch ($linea->product->id) {


                                    // en caso de que la linea sea linea nueva prepago
                                case 1:

                                    //subproducto es igual al perteneciente a la linea 


                                    $subproduct = IccSubProduct::find($linea->subProduct->id);



                                    $dn = $linea->dn;

                                    //si el subproducto requiere una recarga 
                                    if ($subproduct->recarga_required) {

                                        if ($subproduct->recarga_id) {

                                            $recarga =  Recarga::find($subproduct->recarga_id);
                                        } else {

                                            $recarga =  Recarga::find($producto->recarga->id);
                                        }

                                        $newTrasnsaction =  (new Transaction)->newTaecelTransaction($taecelKey, $taecelNip, $dn, $recarga->id);

                                        $transaction = json_decode($newTrasnsaction);

                                        $currentTransaction = Transaction::findOrFail($transaction->transaction_id);

                                        if ($transaction->success == false) {

                                            $venta->transactions()->attach($currentTransaction, ['price' => 0]);
                                        } else if ($transaction->success == true) {

                                            $total += $linea->subProduct->precio;

                                            $total += $recarga->monto;

                                            $icc->setStatus('Vendido');

                                            $linea->setStatus('Activado');

                                            $chip = $linea->productoable;

                                            $chip->transaction_id = $currentTransaction->id;

                                            $venta->transactions()->attach($currentTransaction, ['price' => $recarga->monto]);

                                            $chip->activated_at = now();

                                            $chip->save();

                                            $venta->iccs()->attach($icc, ['price' => $linea->subProduct->precio]);
                                        }
                                    }
                                    // si el subproducto no requiere recarga 
                                    else {

                                        $total += $linea->subProduct->precio;

                                        $icc->setStatus('Vendido');
                                    }




                                    break;

                                default:
                                    $total += $linea->subProduct->precio;

                                    $icc->setStatus('Vendido');

                                    $venta->iccs()->attach($icc, ['price' => $linea->subProduct->precio]);

                                    break;
                            }
                        } //termina el if icc tiene estatus diferente a vendido
                        else{
                          
                        }



                        break; //termina el switch de tipo de producto Icc, imei, general 
                }
            } // final del foreach
        } //final del if productos

        $venta->total = $total;

        $venta->save();

        if($request->cliente){
            
            $cliente = json_decode(json_encode($request->cliente));

            $venta->cliente()->create([
                'name' => isset($cliente->nombre) ? $cliente->nombre : 'público en general',
                'email' => isset($cliente->email )? $cliente->email : null,
                'curp' => isset($cliente->curp) ? $cliente->curp : null,
                'rfc' => isset($cliente->rfc) ? $cliente->rfc : null,
                'referencia'=> isset($cliente->referencia) ? $cliente->referencia : null,
            ]);

            if(isset($cliente->email)){

                Mail::to($cliente->email)->send(new VentaComprobante($venta));

            }

        }

        return $venta->id;
        
        

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function show(Venta $venta)
    {   
        

        

        $response = new VentaResource($venta);

        // $ventaData = [

        //     'inventario'=>$venta->inventario->inventarioable,

        //     'distribution'=>$venta->inventario->distribution->name,

        //     'venta'=>$venta,
            
        //     'fecha' => Carbon::parse($venta->created_at)->format('d/m/y h:i:s' ),

        //     'vendedor' => $venta->user->name,

        //     'cliente' => $venta->cliente,

        //     'productosGenerales' =>$venta->generalProducts,

        //     'imeis' => $venta->imeis()->with('equipo')->get(),

        //     'iccs' => $venta->iccs()->with('linea','company','linea.product','linea.subProduct')->get(),

        //     'transactions' => $venta->transactions()->with('recarga')->get(),
            
        // ];

        //    Mail::to('arturo221355@gmail.com')->send(new VentaComprobante($venta));

        // return view('venta.show',$ventaData);

        return $response;
       

      

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
