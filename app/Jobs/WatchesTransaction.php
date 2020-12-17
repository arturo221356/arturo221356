<?php

namespace App\Jobs;

use App\Transaction;
use App\Porta;
use Illuminate\Support\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WatchesTransaction implements ShouldQueue
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
            $porta->activated_at = now();
            $porta->transaction_id = $this->transaction->id;
            if($porta->preactivated_at == null){
                $porta->preactivated_at == $porta->fvc;
            }
            $porta->save();
            $porta->linea->setStatus('Activado');
            }
            
        }
    }

    
}
