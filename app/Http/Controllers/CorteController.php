<?php

namespace App\Http\Controllers;

use App\Corte;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Caja;
use App\Http\Resources\CorteResource;
use Illuminate\Support\Facades\Auth;
use App\Income;

class CorteController extends Controller
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
        $user = Auth::user();

        $caja = Caja::find($request->caja_id);

        if($request->monto_corte > $caja->total){
            $response = [
                'success' => false,
                'message' => "El monto no puede ser mayor al total en caja",
                'title' => 'Error'
    
            ];
    
            return json_encode($response);
        }

        $corte = new Corte(
            [
               
                'monto' => $request->monto_corte,
                'user_id' => $user->id,
                'restante' => $caja->total - $request->monto_corte,
            ]
        );
        

        $caja->cortes()->save($corte);

        $caja->total -= $corte->monto;

        $caja->save();

       
        $userCaja = $user->caja;

        $corteDiscription = "";

        switch($caja->cajable_type){
            case "App\\Inventario":
    
                $corteDiscription = "Corte ".$caja->cajable->inventarioable->name;
                
            break;
            case "App\\User":

                $corteDiscription = "Corte ".$caja->cajable->name;
            
            break;
        }

        $income = new Income(
            [
                'name' => 'Corte',
                'description' => $corteDiscription,
                'monto' => $corte->monto,
                'user_id' => $user->id,
            ]
        );

        $userCaja->incomes()->save($income);

        $userCaja->total += $corte->monto;

        $userCaja->save();


        $response = [
            'success' => true,
            'message' => 'Monto recolectado $'.$request->monto_corte,
            'title' => 'Corte Agregado',
            'last_corte' => isset($corte->created_at) ? Carbon::parse($corte->created_at)->format('d/m/y h:i:s') : '',

        ];

        return json_encode($response);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Corte  $corte
     * @return \Illuminate\Http\Response
     */
    public function show(Corte $corte)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Corte  $corte
     * @return \Illuminate\Http\Response
     */
    public function edit(Corte $corte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Corte  $corte
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Corte $corte)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Corte  $corte
     * @return \Illuminate\Http\Response
     */
    public function destroy(Corte $corte)
    {
        //
    }

    public function getAll(Request $request)
    {


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


        return CorteResource::collection($caja->cortes()->whereBetween('created_at', [$initialDate, $finalDate])->get());
    }
}
