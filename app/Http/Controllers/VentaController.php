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
use App\Mail\OrderShipped;
use Illuminate\Support\Facades\Mail;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;

use App\Jobs\ChecksItx;
use App\Caja;



class VentaController extends Controller
{


    public function __construct()
    {
        $this->middleware('role:vendedor', ['only' => ['create', 'store']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            // $ventas = Venta::all();

            // return VentaResource::collection($ventas);

            $user = Auth::user();

            $userDistribution = $user->distribution;

            $initialDate = Carbon::parse($request->initial_date)->startOfDay()->toDateTimeString();

            $finalDate = Carbon::parse($request->final_date)->endOfDay()->toDateTimeString();


            if ($user->can('distribution inventarios')) {

                if ($request->inventario_id == "all") {

                    $ventas = Venta::DistributionVentas($initialDate, $finalDate);
                } else {

                    $ventas =  Venta::VentaInInventario($initialDate, $finalDate, $request->inventario_id);
                }
            } else {


                $inventariosIds =  $user->InventariosAsignados()->pluck('inventarios.id')->toArray();

                if ($request->inventario_id == "all") {
                    $ventas = Venta::whereIn('inventario_id', $inventariosIds)->whereBetween('created_at', [$initialDate, $finalDate])->orderBy('created_at', 'asc')->get();
                } else {
                    $ventas = Venta::whereBetween('created_at', [$initialDate, $finalDate])->whereIn('inventario_id', $inventariosIds)->where('inventario_id', $request->inventario_id)->orderBy('created_at', 'asc')->get();
                }
            }

            // if($request->inventario_id == "all"){
            //    
            // }else{
            //     $response = VentaResource::collection($ventas->where('inventario_id',$request->inventario_id));
            // }   


            $response = VentaResource::collection($ventas);

            return $response;
        } else {

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

                        if ($user->can('create transaction')) {

                            $dn = $producto->dn;

                            $recarga = Recarga::findOrFail($producto->recargaId);

                            

                            $newTrasnsaction =  (new Transaction)->newTaecelTransaction($taecelKey, $taecelNip, $dn, $recarga->id, $inventario->id);

                            $transaction = json_decode($newTrasnsaction);

                            $montoRecargaVirtual = 0;

                            if ($transaction->success == true) {
                                $montoRecargaVirtual = $recarga->monto;
                                $total += $recarga->monto;
                               
                            } else if ($transaction->success == false) {
                                $currentTransaction = Transaction::findOrFail($transaction->transaction_id);
                                $currentTransaction->taecel_success = false;
                                $currentTransaction->save();
                            }

                            $currentTransaction = Transaction::findOrFail($transaction->transaction_id);

                            $venta->transactions()->attach($currentTransaction, ['price' => $montoRecargaVirtual]);
                        }




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
                                        $status = 'Preactiva';
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

                                                'nip' => isset($producto->porta->nip) ? $producto->porta->nip : null,
                                                'temporal' => isset($producto->porta->temporal) ? $producto->porta->temporal : null,
                                                'trafico' => isset($producto->porta->trafico) ? $producto->porta->trafico : null,
                                                'fvc' => isset($fvc) ? $fvc : null,

                                            ]);

                                        $status = 'Porta subida';

                                        ChecksItx::dispatch($chip);

                                        break;

                                    case 3:

                                        // crea un nuevo pospago
                                        $chip = Pospago::create([

                                            'preactivated_at' => now(),
                                            'activated_at' => now(),

                                        ]);
                                        $status = 'Pospago';
                                        break;

                                    case 4:

                                        // crea una nueva linea remplazo

                                        $chip = Remplazo::create([

                                            'created_at' => now(),
                                            'updated_at' => now(),
                                        ]);
                                        $status = 'Remplazo';
                                        break;
                                    case 5:

                                        // crea una nueva linea telemarketing
                                        $chip = Telemarketing::create([


                                            'preactivated_at' => now(),
                                            'activated_at' => now(),
                                        ]);
                                        $status = 'Telemarketing';

                                        break;
                                }

                                // y le asigna una linea con el dn de la request 
                                $linea = $chip->linea()->create([
                                    'dn' => $producto->dn,
                                    'icc_product_id' => $producto->iccProduct->id,
                                    'icc_sub_product_id' => isset($producto->iccSubProduct->id) ? $producto->iccSubProduct->id : null,
                                    'icc_id' => $icc->id,

                                ]);

                                $linea->setStatus($status);
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

                                        $newTrasnsaction =  (new Transaction)->newTaecelTransaction($taecelKey, $taecelNip, $dn, $recarga->id, $inventario->id);

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



                                            
                                        }
                                    }
                                    // si el subproducto no requiere recarga 
                                    else {

                                        $linea->setStatus('Sin Saldo');

                                        $total += $linea->subProduct->precio;

                                        $icc->setStatus('Vendido');
                                    }


                                    $chip->activated_at = now();

                                    $chip->save();

                                    $venta->iccs()->attach($icc, ['price' => $linea->subProduct->precio]);




                                    break;

                                default:
                                    $total += $linea->subProduct->precio;

                                    $icc->setStatus('Vendido');

                                    $venta->iccs()->attach($icc, ['price' => $linea->subProduct->precio]);

                                    break;
                            }
                        } //termina el if icc tiene estatus diferente a vendido
                        else {
                        }



                        break; //termina el switch de tipo de producto Icc, imei, general 
                }
            } // final del foreach
        } //final del if productos

        $venta->total = $total;

        $venta->save();

        $caja = Caja::find($venta->inventario->caja->id);

        $caja->total += $total;

        $caja->save();


        $cliente = json_decode(json_encode($request->cliente));

        $venta->cliente()->create([
            'name' => isset($cliente->nombre) ? $cliente->nombre : 'público en general',
            'email' => isset($cliente->email) ? $cliente->email : null,
            'curp' => isset($cliente->curp) ? $cliente->curp : null,
            'rfc' => isset($cliente->rfc) ? $cliente->rfc : null,
            'referencia' => isset($cliente->referencia) ? $cliente->referencia : null,
        ]);

        if (isset($cliente->email)) {

        Mail::to($cliente->email)->queue(new VentaComprobante($venta));

        
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






    public function totalPerDay(Request $request)
    {
        $response = [];

        $caja = Caja::find($request->caja_id);



        if ($request->customDates == true) {

            $initialDate = Carbon::parse($request->initial_date)->startOfDay()->toDateTimeString();

            $finalDate = Carbon::parse($request->final_date)->endOfDay()->toDateTimeString();
        } else {

            if (isset($caja->lastcorte->created_at)) {
                $initialDate = $caja->lastcorte->created_at;
            } else {

                $initialDate = $caja->created_at;
            }

            $finalDate = Carbon::now();
        }






        if ($caja->cajable_type == 'App\\Inventario') {
            $ventas = $caja->cajable->ventas()->whereBetween('created_at', [$initialDate, $finalDate])->orderBy('created_at')->get()->groupBy(function ($item) {
                return [
                    'fecha' => $item->created_at->format('d-m-Y'),

                ];
            })
                ->map(function ($row) {
                    return

                        $row->sum('total');
                });
            foreach ($ventas as $key => $total) {

                $object = [
                    'fecha' => $key,
                    'total' => $total,

                ];

                array_push($response, $object);
            }
        }


        return $response;
    }

    public function getInvoice(Request $request){
        
        $venta = Venta::find($request->folio);

        $seller = new Party([
            'name'          => $venta->inventario->distribution->name,
            'address'       => $venta->inventario->inventarioable->address,
    
            'custom_fields' => [
                'sucursal' =>  $venta->inventario->inventarioable->name,
                'vendedor'          => $venta->user->name,
    
            ],
        ]);
    
    
    
        $customer = new Buyer([
            'name'          => $venta->cliente->name,
            'custom_fields' => [
                'Correo' => $venta->cliente->email,
                'RFC'  => $venta->cliente->rfc,
                'CURP' =>  $venta->cliente->curp,
            ],
        ]);
    
        $items = [];
    
        $imeis = $venta->imeis()->with('equipo')->get();
    
        foreach($imeis as $imei){
            
            array_push($items, (new InvoiceItem())->title($imei->equipo->marca.'  '.$imei->equipo->modelo.'  '.$imei->imei)->pricePerUnit($imei->pivot->price));
            
        }
    
        $iccs = $venta->iccs()->with('linea','company','linea.product','linea.subProduct')->get();
    
        foreach($iccs as $icc){
            
            array_push($items, (new InvoiceItem())->title($icc->linea->dn.' '.$icc->linea->subProduct->name.'  '.$icc->linea->product->name.'  '.$icc->company->name.'  '.$icc->icc)->pricePerUnit($icc->pivot->price));
            
        }
    
        $transactions = $venta->transactions()->with('recarga')->get();
    
        foreach($transactions as $transaction){
            
            array_push($items, (new InvoiceItem())->title($transaction->company->name.'  '. $transaction->recarga->name.'  '.$transaction->dn)->pricePerUnit($transaction->pivot->price));
            
        }
    
        $generales = $venta->generalProducts;
    
        foreach($generales as $vtageneral){
            
            array_push($items, (new InvoiceItem())->title($vtageneral->name.'  '. $vtageneral->description)->pricePerUnit($vtageneral->pivot->price));
            
        }
    
    
    
    
        $invoice = Invoice::make('Comprobante')
            ->buyer($customer)
            ->seller($seller)
            ->date($venta->created_at)
            ->filename('invoices/Comprobante_'.$venta->id)
            ->sequence($venta->id)
            ->addItems($items);

        return $invoice->download();


    }
}
