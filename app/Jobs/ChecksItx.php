<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Porta;
use Illuminate\Support\Facades\Http;

class ChecksItx implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     * 
     */

    protected $porta;

    public function __construct(Porta $porta)
    {
        $this->porta = $porta;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (isset($this->porta->linea->dn)) {

            $consulta = Http::asForm()->post('http://promoviles.herokuapp.com/api/revisar-trafico', [
                'linea' => $this->porta->linea->dn,

            ]);

            $response = json_decode(substr($consulta, 4));

            if (isset($response->result->code)) {

                if ($response->result->code == 1) {
                    $this->porta->trafico_real = true;
                } else {
                    $this->porta->trafico_real = false;
                }


                $this->porta->save();

               
            }
        }
    }
}
