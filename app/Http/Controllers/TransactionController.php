<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Resources\TransactionResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){

            $user = Auth::user();

            $userDistribution = $user->distribution;

            $initialDate = Carbon::parse($request->initial_date)->startOfDay()->toDateTimeString();

            $finalDate = Carbon::parse($request->final_date)->endOfDay()->toDateTimeString();


            if ($user->can('distribution inventarios')) {

                $transactions =  $userDistribution->transactions()->whereBetween('updated_at',[$initialDate,$finalDate])->orderBy('updated_at', 'asc')->get();
            }else{


                $inventariosIds =  $user->InventariosAsignados()->pluck('inventarios.id')->toArray();

                $transactions = Transaction::whereIn('inventario_id', $inventariosIds)->whereBetween('updated_at',[$initialDate,$finalDate])->orderBy('updated_at', 'asc')->get();
            }

                if($request->inventario_id == "all"){
                    $response = TransactionResource::collection($transactions);
                }else{
                    $response = TransactionResource::collection($transactions->where('inventario_id',$request->inventario_id));
                }   

                
           

            return $response;
        }else{
            return view('transaction.index');
        }

        // $transactions = Transaction::all();

        // return $transactions;
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
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
