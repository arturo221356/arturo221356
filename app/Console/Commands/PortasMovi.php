<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Linea;

use Illuminate\Support\Facades\Http;

class PortasMovi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'portas:movistar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revisa el estatus de las portas de movistar';

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
        $lineas = Linea::currentStatus('Porta subida')->whereHas('icc', function ($query) {
            $query->where('company_id', 2);
        })


        ->get();

        
        foreach ($lineas as $linea) {

            $consulta = Http::asForm()->post('http://promoviles.herokuapp.com/api/movistar/portaStatus', [
                'linea' => $linea->dn,
                
            ]);

            $response = json_decode(substr($consulta, 4));

            if (isset($response->result[0]->stateDescription)) {
                
                $linea->deleteStatus('Porta subida');

                $linea->setStatus('Porta subida', $response->result[0]->stateDescription);           

                $this->info("$linea->dn ".$response->result[0]->stateDescription);
            }else{
                $linea->deleteStatus('Porta subida');
                $linea->setStatus('Porta subida', 'Sin Tramite');     
            }
        }
    }
}
