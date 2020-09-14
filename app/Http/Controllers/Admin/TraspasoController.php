<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traspaso;
use App\Imei;
use App\Icc;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

use App\Http\Resources\TraspasoCollection as TraspasoResource;
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

            $userDistribution = Auth::User()->distribution()->id;
            
            $initialDate = Carbon::createFromFormat('Y-m-d', $request->initial_date)->startOfDay()->toDateTimeString();

            $finalDate = Carbon::createFromFormat('Y-m-d', $request->final_date)->endOfDay()->toDateTimeString();

            $accepted = json_decode($request->accepted);

            $traspasos = Traspaso::where([['accepted','=',$accepted],['distribution_id','=',$userDistribution]])->whereBetween('created_at', [$initialDate, $finalDate])->with('user')->get();

            return TraspasoResource::collection($traspasos);


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

        $userDistribution = Auth::User()->distribution()->id;

        $accepted = !$aceptacionRequired;

        $traspaso = Traspaso::create([

            'aceptacion_required' => $aceptacionRequired,
            'sucursal_id' => $request->sucursal_id,
            'accepted' => $accepted,
            'distribution_id' => $userDistribution,
            

        ]);



        $items = json_decode($request->data);

        foreach ($items as $item) {
            switch ($item->type) {

                case "iccs":

                    $icc =  Icc::findorfail($item->id);

                    $traspaso->iccs()->attach(
                        $icc,
                        ['old_sucursal_id' => $icc->sucursal_id,
                         'old_status_id' => $icc->status_id
                        ]
                    );

                    

                    if ($aceptacionRequired == true) {
                        $icc->status_id = 3;
                    }else{
                        $icc->sucursal_id = $request->sucursal_id;
                    }

                    $icc->save();
                    break;

                case "imeis":

                    $imei =  Imei::findorfail($item->id);

                    $traspaso->imeis()->attach(
                        $imei,
                        ['old_sucursal_id' => $imei->sucursal_id,
                        'old_status_id' => $imei->status_id
                       ]
                    );

                    
                    
                    if ($aceptacionRequired == true) {
                        $imei->status_id = 3;
                    }else{
                        $imei->sucursal_id = $request->sucursal_id;
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
            
            $traspaso = Traspaso::findOrFail($id)->load('sucursal', 'imeis.sucursal', 'imeis.equipo', 'iccs.sucursal', 'iccs.type', 'iccs.company');

            return $traspaso;
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


       foreach($traspaso->imeis as $imei){
           $imei->status_id = $imei->pivot->old_status_id;
           $imei->sucursal_id = $traspaso->sucursal_id;
           $imei->save();
       
       }
       
       foreach($traspaso->iccs as $icc){
        
        $icc->status_id = $icc->pivot->old_status_id;
        $icc->sucursal_id = $traspaso->sucursal_id;
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

        foreach($traspaso->imeis as $imei){

            $imei->status_id = $imei->pivot->old_status_id;

            $imei->status_id = $imei->pivot->old_sucursal_id;

            $imei->save();
        
        }
        
        foreach($traspaso->iccs as $icc){
         
         $icc->sucursal_id = $icc->pivot->old_sucursal_id;

         $icc->status_id = $icc->pivot->old_status_id;
         
         $icc->save();
         
     }

        $traspaso->delete();
    }
}
