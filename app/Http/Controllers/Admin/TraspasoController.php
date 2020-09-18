<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traspaso;
use App\Imei;
use App\Icc;
use App\Inventario;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

use App\Http\Resources\TraspasoCollection as TraspasoCollection;
use App\Http\Resources\TraspasoResource as TraspasoResource;

use PHPUnit\Framework\MockObject\Stub\ReturnStub;

class TraspasoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $userDistribution = Auth::User()->distribution->id;

            $initialDate = Carbon::createFromFormat('Y-m-d', $request->initial_date)->startOfDay()->toDateTimeString();

            $finalDate = Carbon::createFromFormat('Y-m-d', $request->final_date)->endOfDay()->toDateTimeString();

            $accepted = json_decode($request->accepted);

            $traspasos = Traspaso::where([['accepted', '=', $accepted], ['distribution_id', '=', $userDistribution]])->whereBetween('created_at', [$initialDate, $finalDate])->get();

            return TraspasoCollection::collection($traspasos);
        } else {
            return view('admin.inventario.traspasos');
        }
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
        $aceptacionRequired =  json_decode($request->aceptacion_required);

        $userDistribution = Auth::User()->distribution->id;

        // $sucursal = Sucursal::findOrFail($request->sucursal_id);

        $inventario = Inventario::findOrFail($request->inventario_id);

        $accepted = !$aceptacionRequired;

        $traspaso = new Traspaso;
        $traspaso->aceptacion_required = $aceptacionRequired;
        $traspaso->accepted = $accepted;
        $traspaso->distribution_id = $userDistribution;
        $traspaso->inventario()->associate($inventario);
        if ($aceptacionRequired == false) {
            $traspaso->user_id = Auth::user()->id;
        }
        $traspaso->save();



        $items = json_decode($request->data);




        foreach ($items as $item) {
            switch ($item->type) {

                case "iccs":

                    $icc =  Icc::findorfail($item->id);

                    $traspaso->iccs()->attach(
                        $icc,
                        ['old_inventario_id' => $icc->inventario->id,]
                    );



                    if ($aceptacionRequired == true) {
                        $icc->setStatus('Traslado');
                    } else {
                        //arreglar esto de aqui
                        $icc->inventario()->associate($inventario);
                    }

                    $icc->save();
                    break;

                case "imeis":

                    $imei =  Imei::findorfail($item->id);

                    $traspaso->imeis()->attach(
                        $imei,
                        ['old_inventario_id' => $imei->inventario_id,]
                    );



                    if ($aceptacionRequired == true) {
                        $imei->setStatus('Traslado');
                    } else {
                        //arreglar esto de aqui 

                        $imei->inventario()->associate($inventario);
                    }
                    $imei->save();

                    break;
            }
        }



        return $traspaso;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        if ($request->ajax()) {

            $traspaso = Traspaso::find($id);

            return  TraspasoResource::make($traspaso);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $traspaso = Traspaso::findOrFail($id);

        $traspaso->user_id = Auth::user()->id;

        $traspaso->accepted = true;


        foreach ($traspaso->imeis as $imei) {
            $imei->deleteStatus('Traslado');
            $imei->inventario_id = $traspaso->inventario_id;
            $imei->save();
        }

        foreach ($traspaso->iccs as $icc) {

            $icc->deleteStatus('Traslado');
            $icc->inventario_id = $traspaso->inventario_id;
            $icc->save();
        }

        $traspaso->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $traspaso = Traspaso::findOrFail($id);

        $traspaso->user_id = Auth::user()->id;

        $traspaso->save();

        foreach ($traspaso->imeis as $imei) {

            $imei->deleteStatus('Traslado');

            $imei->inventario_id = $imei->pivot->old_inventario_id;

            $imei->save();
        }

        foreach ($traspaso->iccs as $icc) {

            $icc->deleteStatus('Traslado');

            $icc->inventario_id = $icc->pivot->old_inventario_id;

            $icc->save();
        }

        $traspaso->delete();
    }


}
