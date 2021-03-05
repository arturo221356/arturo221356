<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Income;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Carbon;

use App\Http\Resources\IncomeResource;

use App\Caja;

class IncomeController extends Controller
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
        

        return IncomeResource::collection($caja->incomes()->whereBetween('created_at', [$initialDate, $finalDate])->get());

        

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


        $income = new Income(
            [
                'name' => $request->income_name,
                'description' => $request->income_description,
                'monto' => $request->income_monto,
                'user_id' => $user->id,
            ]
        );
        

        $caja->incomes()->save($income);

        $caja->total += $income->monto;

        $caja->save();

        $response = [
            'success' => true,
            'message' => $request->income_name." $".$request->income_monto,
            'title' => 'Ingreso Agregado'

        ];

        return json_encode($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
