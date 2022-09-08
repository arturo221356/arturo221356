<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Taecel;
use Illuminate\Support\Facades\Auth;
use App\Jobs\WatchesTransaction;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ["taecel", "taecel_success", "taecel_timeout", "taecel_folio", "taecel_message", "taecel_transID", "taecel_nota", "taecel_status", "monto", "dn", 'company_id', 'recarga_id', 'inventario_id'];

    public function recarga()
    {
        return $this->belongsTo('App\Recarga');
    }
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
    public function inventario()
    {
        return $this->belongsTo('App\Inventario');
    }


    public function scopeDistributionTransactions($transaction, $initialDate, $finalDate)
    {
        $transactions = $transaction

            ->whereBetween('created_at', [$initialDate, $finalDate])
            ->whereHas('inventario', function ($query) {
                $user = Auth::user();
                $query->where('distribution_id', $user->distribution->id);
            })
            ->orderBy('created_at', 'asc')->get();

        return $transactions;
    }

    public function scopeTransactionInInventario($transaction, $initialDate, $finalDate, $inventario_id)
    {
        $transactions = $transaction

            ->whereBetween('created_at', [$initialDate, $finalDate])

            ->whereHas('inventario', function ($query) use ($inventario_id) {
                $user = Auth::user();
                $inventariosIds =  $user->InventariosAsignados()->pluck('inventarios.id')->toArray();
                $query->whereIn('inventario_id', $inventariosIds)
                    ->where('inventario_id', $inventario_id);
            })

            ->orderBy('created_at', 'asc')
            ->get();

        return $transactions;
    }


    public function newTaecelTransaction($taecelKey, $taecelNip, $dn, $recargaId, $inventarioID,  $activachip = false)
    {

        $dn = $dn;

        $recarga = Recarga::findOrFail($recargaId);

        // // si la recarga es movistar y  activachip es falso
        // if ($recarga->company_id == 2 && $activachip == false) {
        //     $aplicarRecarga = false;
        // } else {
        //     $aplicarRecarga = true;
        // }

        // if ($aplicarRecarga == true) {

        //     $requestTXN =  (new Taecel)->taecelRequestTXN($taecelKey, $taecelNip, $recarga->taecel_code, $dn);

        //     $taecelRequest = json_decode($requestTXN);
        // }

        $requestTXN =  (new Taecel)->taecelRequestTXN($taecelKey, $taecelNip, $recarga->taecel_code, $dn);

        $taecelRequest = json_decode($requestTXN);



        $transaction = Transaction::create([

            'taecel' =>  true,

            'monto' => $recarga->monto,

            'dn' => $dn,

            'company_id' => $recarga->company_id,

            'recarga_id' => $recarga->id,

            'inventario_id' => $inventarioID,

            'taecel_success' =>  $taecelRequest->success ?? '',

            'taecel_message' =>  $taecelRequest->message ?? '',



        ]);

        // // si la recarga no se aplica por taecel retorna la transaccion
        // if ($aplicarRecarga == false) {

        //     return $transaction;
        // }


        if (isset($taecelRequest->data) && $taecelRequest->data) {

            $transaction->taecel_transID = $taecelRequest->data->transID;

            $transaction->save();
        }


        /// si falla la primera consula a taecel, retorna la transaccion
        if (isset($taecelRequest->success) && $taecelRequest->success == false) {

            return $transaction;
        }



        if (isset($taecelRequest->success) && $taecelRequest->success == true) {

            $transID = $taecelRequest->data->transID;

            //hace una segunda consulta de estatus a taecel 

            $statusTXN =  (new Taecel)->TaecelStatusTXN($taecelKey, $taecelNip, $transID);

            $taecelStatusTXN = json_decode($statusTXN);




            $transaction->taecel_message = $taecelStatusTXN->message;
            

            if (isset($taecelStatusTXN->data)) {

                $transaction->taecel_success = $taecelStatusTXN->success;

                $transaction->taecel_status = $taecelStatusTXN->data->Status;

                $transaction->taecel_timeout = $taecelStatusTXN->data->Timeout;

                $transaction->taecel_folio = $taecelStatusTXN->data->Folio;

                $transaction->taecel_nota = $taecelStatusTXN->data->Nota;

                
            }
            
            $transaction->save();

            if ($taecelStatusTXN->success  == true) {

                WatchesTransaction::dispatch(Transaction::findOrFail($transaction->id));
            }

            return $transaction;;
        }
    }
}
