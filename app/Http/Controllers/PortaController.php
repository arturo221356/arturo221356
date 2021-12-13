<?php

namespace App\Http\Controllers;

use App\Http\Resources\PortaResource;
use App\Icc;
use App\Porta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Jobs\ChecksItx;
use App\Linea;

class PortaController extends Controller
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
     * @param  \App\Porta  $porta
     * @return \Illuminate\Http\Response
     */
    public function show(Porta $porta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Porta  $porta
     * @return \Illuminate\Http\Response
     */
    public function edit(Porta $porta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Porta  $porta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Porta $porta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Porta  $porta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Porta $porta)
    {
        //
    }
    public function newExternoPorta(Request $request)
    {

        $icc = Icc::where('icc', $request->icc)->first();

        if ($icc === null) {
            $response = [
                'success' => false,
                'message' => 'Icc no existe en la base de datos'
            ];

            return $response;
        }
        if(!$icc->linea()->first() == null){

            $response = [
                "success" => false,
                "message" => "Icc ya tiene linea activa: " . $icc->linea->dn,

            ];

            return $response;
        }

        if (!in_array($icc->inventario->inventarioable_type, array('App\Grupo', 'App\User'), true)){
            $response = [
                "success" => false,
                "message" => "Funcion solo disponible para usuarios externos",

            ];

            return $response;
        }

        
           

                if (isset($request->fvc)) {

                    $stringFvc = Carbon::parse($request->fvc)->toIso8601String();

                    $fvc = new Carbon($stringFvc);

                    $fvc->hour = 9;
                }


                $porta = Porta::create([

                    'nip' => isset($request->nip) ? $request->nip : null,
                    'temporal' => isset($request->temporal) ? $request->temporal : null,
                    'trafico' => isset($request->trafico) ? $request->trafico : null,
                    'fvc' => isset($fvc) ? $fvc : null,

                ]);



                $linea = $porta->linea()->create([
                    'dn' => $request->dn,
                    'icc_product_id' => 2,

                    'icc_id' => $icc->id,

                ]);

                $linea->setStatus('Porta subida');

                if (Auth::check()) {

                    $linea->user()->associate(Auth::user());
    
                    $linea->save();
                }

                $icc->setStatus('Vendido');

                ChecksItx::dispatch($porta);

                $response = [
                    "success" => true,
                    "message" => "Portabilidad Subida",
                ];
            
        

        return  json_encode($response);
    }

    public function getPortas(Request $request)
    {

        $user = Auth::user();

        if ($request->ajax()) {

            $inventario_id = $request->inventario_id;

            $initialDate = Carbon::parse($request->initial_date)->startOfDay()->toDateTimeString();

            $finalDate = Carbon::parse($request->final_date)->endOfDay()->toDateTimeString();

            if ($inventario_id == 'all') {
                $inventariosIds = $user->getInventariosForUserIds();
            } else {
                if (in_array($inventario_id, $user->getInventariosForUserIds()->toArray())) {
                    $inventariosIds = [$inventario_id];
                } else {
                    $inventariosIds = [];
                }
            }

            // $portas = Porta::whereBetween('created_at', [$initialDate, $finalDate])
            //     ->whereHas('linea', function ($query) {
            //         $query->currentStatus(['Activado','Porta subida','Preactiva','Porta Exitosa']);
            //     })
            //     ->whereHas('linea.icc', function ($query) use ($inventariosIds) {
            //         $query->whereIn('inventario_id', $inventariosIds);
            //     })
            //     ->orderBy('created_at', 'asc')

            //     ->get();
            
            $portas = Linea::currentStatus(['Activado','Porta subida','Preactiva','Porta Exitosa'])

            ->whereHas('icc.inventario', function ($query) use ($inventariosIds) {
                $query->whereIn('id', $inventariosIds);
            })

            ->whereHasMorph(
                'productoable',
                [Porta::class],
                function ($query, $type)  use ($initialDate, $finalDate) {

                    $query->whereBetween('activated_at', [$initialDate, $finalDate]);
                }
            )->get();


            $response = PortaResource::collection($portas);


            return $response;
        }
    }
}
