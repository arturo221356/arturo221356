<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Linea;

use Illuminate\Support\Carbon;

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
    protected $description = 'Revisa todas las lineas tanto como vendidas y preactivas se encuentren en el mismo operador todos los dias';

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

        $preactivas =  Linea::currentStatus(['Preactiva', 'Recargable'])->get();

        $chipsActivados = Linea::currentStatus('Activado')->whereHasMorph('productoable', ['App\Chip', 'App\Porta', 'App\Pospago'], function ($query) {
                $query->whereBetween('activated_at', [Carbon::now()->subDays(15), Carbon::now()])
                    ->orWhereDate('activated_at', Carbon::now()->subDays(30))
                    ->orWhereDate('activated_at', Carbon::now()->subDays(45));
            })


            ->get();




        foreach ($preactivas as $linea) {

            $consulta = Http::contentType("application/json-rpc")->bodyFormat('json')->post('http://pcportabilidad.movistar.com.mx:4080/PCMOBILE/catalogMobile', [
                'id' => mt_rand(100000, 999999),
                'method' => "getOperatorByMsisdn",
                'params' => [$linea->dn]

            ]);

            $response = json_decode(substr($consulta, 4));

            if (isset($response->result[0]->key) && $response->result[0]->key != $linea->icc->company->code) {

                $linea->setStatus('Exportada');

                $linea->updated_at = Carbon::now();

                $linea->save();
            }
        }
        // foreach ($chipsActivados as $linea) {

        //     $consulta = Http::contentType("application/json-rpc")->bodyFormat('json')->post('http://pcportabilidad.movistar.com.mx:4080/PCMOBILE/catalogMobile', [
        //         'id' => mt_rand(1000000, 9999999),
        //         'method' => "getOperatorByMsisdn",
        //         'params' => [$linea->dn]

        //     ]);

        //     $response = json_decode(substr($consulta, 4));

        //     if (isset($response->result[0]->key) && $response->result[0]->key != $linea->icc->company->code) {

        //         $linea->setStatus('Exportada');

        //         $linea->updated_at = Carbon::now();

        //         $linea->save();
        //     }
        // }
    }
}
