<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Linea;

use Illuminate\Support\Facades\Http;

class DailyExportados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exportados:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'REvisa todas las lineas tanto como vendidas y preactivas se encuentren en el mismo operador todos los dias';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $lineas = Linea::currentStatus('Exportada')->get();

        foreach ($lineas as $linea) {

            $consulta = Http::contentType("application/json-rpc")->bodyFormat('json')->post('http://pcportabilidad.movistar.com.mx:4080/PCMOBILE/catalogMobile', [
                'id' => mt_rand(100000, 999999),
                'method' => "getOperatorByMsisdn",
                'params' => [$linea->dn]

            ]);

            $response = json_decode(substr($consulta, 4));

            if ($response->result[0]->key != $linea->icc->company->code) {

                $linea->setStatus('Exportada');

                $this->info($linea->status());
            } else {
                $this->info('todo bien pariente');
            }
        }



       
    }
}
