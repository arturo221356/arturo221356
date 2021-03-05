<?php

namespace App\Http\Controllers;

use App\Gasto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Caja;
use App\Http\Resources\GastoResource;
use Illuminate\Support\Carbon;

class GastoController extends Controller
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
    public function getAll(Request $request){
        
        $caja = Caja::find($request->caja_id);

        if ($request->customDates == true) {

            $initialDate = Carbon::parse($request->initial_date)->startOfDay()->toDateTimeString();

            $finalDate = Carbon::parse($request->final_date)->endOfDay()->toDateTimeString();
        } else {


            if(isset($caja->lastcorte->created_at)){

                $initialDate = $caja->lastcorte->created_at;

            }else{

                $initialDate = $caja->lastcorte;
            }  
            
            

            $finalDate = Carbon::now();
        }
        

        return GastoResource::collection($caja->gastos()->whereBetween('created_at', [$initialDate, $finalDate])->get());

        

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

        $caja = Caja::find($request->caja_id);

        if($request->gasto_monto > $caja->total){
            $response = [
                'success' => false,
                'message' => "El monto no puede ser mayor al total en caja",
                'title' => 'Error'
    
            ];
    
            return json_encode($response);
        }

        $gasto = new Gasto(
            [
                'name' => $request->gasto_name,
                'description' => $request->gasto_description,
                'monto' => $request->gasto_monto,
                'user_id' => $user->id,
            ]
        );
        

        $caja->gastos()->save($gasto);

        $caja->total -= $gasto->monto;

        $caja->save();

        $response = [
            'success' => true,
            'message' => $request->gasto_name." $".$request->gasto_monto,
            'title' => 'Gasto Agregado'

        ];

        return json_encode($response);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Gasto  $gasto
     * @return \Illuminate\Http\Response
     */
    public function show(Gasto $gasto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Gasto  $gasto
     * @return \Illuminate\Http\Response
     */
    public function edit(Gasto $gasto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Gasto  $gasto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gasto $gasto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Gasto  $gasto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gasto $gasto)
    {
        //
    }
}
