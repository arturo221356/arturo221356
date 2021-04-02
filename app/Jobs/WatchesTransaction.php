<?php

namespace App\Jobs;

use App\Transaction;
use App\Porta;
use Illuminate\Support\Carbon;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\PocoSaldoRecargas;
use Illuminate\Support\Facades\Notification;

use App\Taecel;

class WatchesTransaction  
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $porta = Porta::whereNull('activated_at')->whereHas('linea', function ($query) {
            $query->where('dn', $this->transaction->dn);
        })->first();
        
        if(isset($porta)){
            if($porta->linea->icc->company->id == $this->transaction->company_id){
            
            $porta->transaction_id = $this->transaction->id;
            
            if($this->transaction->taecel_success == true){
                $porta->activated_at = now();
                if($porta->preactivated_at == null){
                    $porta->preactivated_at == $porta->fvc;
                }
                $porta->linea->setStatus('Activado');
            }
            
            $porta->save();
           
            }
            
        }

       $distribution = $this->transaction->inventario->distribution;

       $users = User::where('distribution_id',$distribution->id)->role('Administrador')->get();
   
        $taecelKey = $distribution->taecel_key;
    
        $taecelNip = $distribution->taecel_nip;
    
        $balance = (new Taecel())->getBalance($taecelKey, $taecelNip);
    
         $response = json_decode($balance);
    
        $saldo = (float) str_replace(',', '', $response->data[0]->Saldo);
    
        if($saldo < 500){
        
            Notification::send($users, new PocoSaldoRecargas($response->data[0]->Saldo));
            
        }


       
       

      
    }

    
}
