<?php

namespace App\Http\Controllers;

use App\Linea;

use App\Icc;

use Illuminate\Support\Carbon;Linea


use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;



use Illuminate\Support\Facades\Request as FacadesRequest;


class LineaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }
    public function verificarIcc(Request $request)
    {


        $requestIcc = $request->icc;

        $user = Auth::user();


        if ($user->hasPermissionTo('distribution inventarios')) {

            $icc = Icc::iccInUserDistribution($requestIcc)->with('company', 'type')->first();
        } else {
            $icc = Icc::iccInUserInventario($requestIcc)->with('company', 'type')->first();
        }
        if ($icc === null) {
            $response = [
                'success' => false,
                'message' => 'Icc no existe en la base de datos'
            ];

            return $response;
        }

        if ($icc->linea()->first() == null) {
            $response = [
                "success" => true,
                "data" => $icc,
            ];
        } else {
            $response = [
                "success" => false,
                "message" => "Icc ya tiene linea activa: " . $icc->linea->dn,

            ];
        }

        return  json_encode($response);
    }

   




    
    public function getExportadas(Request $request){

        $user = Auth::user();

        if ($request->ajax()) {

            $inventario_id = $request->inventario_id;

            $initialDate = Carbon::parse($request->initial_date)->startOfDay()->toDateTimeString();

            $finalDate = Carbon::parse($request->final_date)->endOfDay()->toDateTimeString();


            if ($inventario_id === 'all') {

                if ($user->can('distribution inventarios')) {

                    $chips = Linea::DistributionExportadas($initialDate, $finalDate);
                } else {
                    $chips = Linea::InUserInventarioExportadas($initialDate, $finalDate);
                }
            } else {

                $chips = Linea::InventarioExportadas($initialDate, $finalDate, $inventario_id);
            }


            $response = ExportadaResource::collection($chips);


            return $response;
        }
    }

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
        $user = Auth::user();

        if($user->can('destroy stock')){
            
            $linea = $linea;

            $chip = $linea->productoable();

            $chip->delete();

            $linea->deleteStatus($linea->statuses);
    
            $linea->forceDelete();
        }

    }
}
