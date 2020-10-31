<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Taecel;
use Illuminate\Support\Facades\Auth;

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


    public function newTaecelTransaction($taecelKey, $taecelNip, $dn, $recargaId)
    {
        $user = Auth::user();

        $inventario = $user->inventariosAsignados()->first();

        $dn = $dn;

        $recarga = Recarga::findOrFail($recargaId);

        $requestTXN =  (new Taecel)->taecelRequestTXN($taecelKey, $taecelNip, $recarga->taecel_code, $dn);

        $taecelRequest = json_decode($requestTXN);

        $transaction = Transaction::create([

            'taecel' => true,

            'monto' => $recarga->monto,

            'dn' => $dn,

            'company_id' => $recarga->company_id,

            'recarga_id' => $recarga->id,

            'inventario_id' => $inventario->id,

            'taecel_success' => $taecelRequest->success,

            'taecel_message' => $taecelRequest->message,

        ]);
        if ($taecelRequest->data) {
            $transaction->taecel_transID = $taecelRequest->data->transID;

            $transaction->save();
        }
        if ($taecelRequest->success == false) {

            $response =  [
                
                'success' =>  false,
                'message' => $taecelRequest->message,
            ];
        } else if ($taecelRequest->success == true) {

            $transID = $taecelRequest->data->transID;

            $statusTXN =  (new Taecel)->TaecelStatusTXN($taecelKey, $taecelNip, $transID);

            $taecelStatusTXN = json_decode($statusTXN);

            if ($taecelStatusTXN->data) {

                $transaction->taecel_status = $taecelStatusTXN->data->Status;

                $transaction->taecel_message = $taecelStatusTXN->message;

                $transaction->taecel_timeout = $taecelStatusTXN->data->Timeout;

                $transaction->taecel_folio = $taecelStatusTXN->data->Folio;

                $transaction->taecel_nota = $taecelStatusTXN->data->Nota;
            }

            if ($taecelStatusTXN->success  == true) {

                $response =  [
                    'success' =>  true,
                    'message' => $taecelRequest->message . ",  Folio: " . $taecelStatusTXN->data->Folio . " Monto: " . $taecelStatusTXN->data->Monto,
                ];

                
            } else if ($taecelStatusTXN->success  == false) {



                $response =  [
                    'success' =>  false,
                    'message' => $taecelStatusTXN->message,
                ];
            }
        }



        $transaction->save();

        $response['transaction_id'] = $transaction->id;


        return json_encode($response);
    }
}
