<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Linea;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Throwable;
use Illuminate\Support\Facades\Log;

class checkExportado implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $linea;


    public function __construct(Linea $linea)
    {
        $this->linea = $linea;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $consulta = Http::asForm()->post('http://promoviles.herokuapp.com/api/movistar/getCarrier', [
            'linea' => $this->linea->dn,
            
        ]);

        $response = json_decode(substr($consulta, 4));

        if (isset($response->result[0]->key) && $response->result[0]->key != $this->linea->icc->company->code) {

            $this->linea->setStatus('Exportada',"Linea fue exportada a".$response->result[0]->value);

            $this->linea->updated_at = Carbon::now();

            $this->linea->save();

           
        }
    }
    public function failed(Throwable $exception)
    {
        Log::info($exception);
    }
}
