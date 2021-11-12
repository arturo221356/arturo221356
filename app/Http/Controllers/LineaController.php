<?php

namespace App\Http\Controllers;

use App\Linea;

use App\Icc;

use App\Chip;

use App\Porta;

use Illuminate\Support\Carbon;

use App\Http\Resources\ExportadaResource;


use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;

use App\Exports\LineaExport;
use App\Pospago;

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





    public function verificarIccPortaExterna(Request $request)
    {



        $icc = Icc::where('icc', $request->icc)->with('company', 'type', 'inventario.inventarioable')->first();

        if ($icc === null) {
            $response = [
                'success' => false,
                'message' => 'Icc no existe en la base de datos'
            ];

            return $response;
        }

        if ($icc->linea()->first() == null) {

            if (!in_array($icc->inventario->inventarioable_type, array('App\Grupo', 'App\User'), true)) {
                $response = [
                    "success" => false,
                    "message" => "Funcion solo disponible para usuarios externos" ,

                ];

                return $response;
            } else {
                $response = [
                    "success" => true,
                    "data" => $icc,
                ];
            }
        } else {
            $response = [
                "success" => false,
                "message" => "Icc ya tiene linea activa: " . $icc->linea->dn,

            ];
        }

        return  json_encode($response);
    }







    public function getExportadas(Request $request)
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

            $exportadas = Linea::whereBetween('updated_at', [$initialDate, $finalDate])

                ->currentStatus('Exportada')

                ->whereHas('icc.inventario', function ($query) use ($inventariosIds) {
                    $query->whereIn('inventario_id', $inventariosIds);
                })
                ->orderBy('updated_at', 'asc')
                ->get();


            $response = ExportadaResource::collection($exportadas);


            return $response;
        }
    }

    public function exportExcelLineas(Request $request)
    {

        $user = Auth::user();

        $initialDate = Carbon::parse($request->initial_date)->startOfDay()->toDateTimeString();

        $finalDate = Carbon::parse($request->final_date)->endOfDay()->toDateTimeString();



        if ($request->inventario_id == 'all') {
            $inventariosIds = $user->getInventariosForUserIds();
        } else {
            if (in_array($request->inventario_id, $user->getInventariosForUserIds()->toArray())) {
                $inventariosIds = [$request->inventario_id];
            } else {
                $inventariosIds = [];
            }
        }


        $linea = Linea::currentStatus(['Porta subida', 'Porta Exitosa', 'Activado', 'Sin Saldo', 'Pospago', 'Telemarketing', 'Exportada'])

            ->whereHas('icc.inventario', function ($query) use ($inventariosIds) {
                $query->whereIn('id', $inventariosIds);
            })

            ->whereHasMorph(
                'productoable',
                [Porta::class, Chip::class, Pospago::class],
                function ($query, $type)  use ($initialDate, $finalDate) {

                    $column = $type === Porta::class ? 'created_at' : 'activated_at';

                    $query->whereBetween($column, [$initialDate, $finalDate]);
                }
            )->get();

        return Excel::download(new LineaExport($linea), 'invoices.xlsx');
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

        if ($user->can('destroy stock')) {

            $linea = $linea;

            $chip = $linea->productoable();

            $chip->delete();

            $linea->deleteStatus($linea->statuses);

            $linea->forceDelete();
        }
    }
}
