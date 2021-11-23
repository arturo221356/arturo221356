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
use App\Otro;
use App\Http\Resources\VentaResource;
use Illuminate\Support\Carbon;
use App\Mail\VentaComprobante;
use Illuminate\Support\Facades\Mail;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;

use App\Caja;
use App\Linea;
use App\SoldOtro;

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
        return view('venta.index');
    }
    public function getVentas(Request $request)
    {


        $user = Auth::user();

        $inventario_id = $request->inventario_id;

        $initialDate = Carbon::parse($request->initialDate)->startOfDay()->toDateTimeString();

        $finalDate = Carbon::parse($request->finalDate)->endOfDay()->toDateTimeString();



        if ($inventario_id == 'all') {
            $inventariosIds = $user->getInventariosForUserIds();
        } else {
            if (in_array($inventario_id, $user->getInventariosForUserIds()->toArray())) {
                $inventariosIds = [$inventario_id];
            } else {
                $inventariosIds = [];
            }
        }


        $ventas = Venta::whereBetween('created_at', [$initialDate, $finalDate])->whereIn('inventario_id', $inventariosIds)->orderBy('created_at', 'asc')->get();


        $response = VentaResource::collection($ventas);

        return $response;
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



                            $montoRecargaVirtual = 0;

                            if ($newTrasnsaction->taecel_success == true) {
                                $montoRecargaVirtual = $recarga->monto;
                                $total += $recarga->monto;
                            }


                            $venta->transactions()->attach($newTrasnsaction, ['price' => $montoRecargaVirtual]);
                        }




                        break;

                    case 'imeis':

                        $statuses = array("Vendido", "Traslado");

                        $imei = Imei::findOrFail($producto->id);

                        if (!in_array($imei->status, $statuses)) {

                            $imei->setStatus('Vendido');

                            $venta->imeis()->attach($imei, ['price' => $imei->equipo->precio, 'cost' => $imei->equipo->costo]);

                            $total += $imei->equipo->precio;
                        }

                        break;

                    case 'accesorios':




                        $accesorio = Otro::findOrFail($producto->id);

                        $accesorio->sellOtro($inventario->id);

                        $soldOtro = new SoldOtro;

                        $soldOtro->otro_id = $accesorio->id;

                        $soldOtro->precio_vendido = $accesorio->precio;

                        $soldOtro->costo = $accesorio->costo;

                        $soldOtro->save();

                        $venta->soldOtros()->attach($soldOtro, ['price' => $accesorio->precio, 'cost' => $accesorio->costo]);

                        $total += $producto->precio;

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
                            } else {

                                // sino tiene linea asignada le  crea una 
                                $linea = (new Linea)->newLineaWithProduct($producto, $icc->id);
                            }

                            // le asigna el usuario a la linea
                            $linea->user()->associate(Auth::user());

                            $linea->save();

                            //revisa el tipo de producto y ve si tiene recarga asignada

                            switch ($linea->product->id) {


                                    // en caso de que la linea sea linea nueva prepago
                                case 1:

                                    //subproducto es igual al perteneciente a la linea 


                                    $subproduct = IccSubProduct::find($linea->subProduct->id);

                                    $chip = $linea->productoable;

                                    $dn = $linea->dn;

                                    //si el subproducto requiere una recarga 
                                    if ($subproduct->recarga_required) {

                                        if ($subproduct->recarga_id) {

                                            $recarga =  Recarga::find($subproduct->recarga_id);
                                        } else {

                                            $recarga =  Recarga::find($producto->recarga->id);
                                        }

                                        $newTrasnsaction =  (new Transaction)->newTaecelTransaction($taecelKey, $taecelNip, $dn, $recarga->id, $inventario->id);



                                        if ($newTrasnsaction->taecel_success == false) {

                                            $venta->transactions()->attach($newTrasnsaction, ['price' => 0]);
                                        } else if ($newTrasnsaction->taecel_success == true) {

                                            $total += $linea->subProduct->precio;

                                            $total += $recarga->monto;

                                            $icc->setStatus('Vendido');

                                            $linea->setStatus('Activado');



                                            $chip->transaction_id = $newTrasnsaction->id;

                                            $venta->transactions()->attach($newTrasnsaction, ['price' => $recarga->monto]);
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

    public function getInvoice(Request $request)
    {

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

        foreach ($imeis as $imei) {

            array_push($items, (new InvoiceItem())->title($imei->equipo->marca . '  ' . $imei->equipo->modelo . '  ' . $imei->imei)->pricePerUnit($imei->pivot->price));
        }

        $iccs = $venta->iccs()->with('linea', 'company', 'linea.product', 'linea.subProduct')->get();

        foreach ($iccs as $icc) {

            array_push($items, (new InvoiceItem())->title($icc->linea->dn . ' ' . $icc->linea->subProduct->name . '  ' . $icc->linea->product->name . '  ' . $icc->company->name . '  ' . $icc->icc)->pricePerUnit($icc->pivot->price));
        }

        $transactions = $venta->transactions()->with('recarga')->get();

        foreach ($transactions as $transaction) {

            array_push($items, (new InvoiceItem())->title($transaction->company->name . '  ' . $transaction->recarga->name . '  ' . $transaction->dn)->pricePerUnit($transaction->pivot->price));
        }

        $generales = $venta->generalProducts;

        foreach ($generales as $vtageneral) {

            array_push($items, (new InvoiceItem())->title($vtageneral->name . '  ' . $vtageneral->description)->pricePerUnit($vtageneral->pivot->price));
        }




        $invoice = Invoice::make('Comprobante')
            ->buyer($customer)
            ->seller($seller)
            ->date($venta->created_at)
            ->filename('invoices/Comprobante_' . $venta->id)
            ->sequence($venta->id)
            ->addItems($items);

        return $invoice->download();
    }
}
