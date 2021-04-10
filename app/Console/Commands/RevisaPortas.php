<?php

namespace App\Console\Commands;

use App\Porta;
use App\Linea;
use Illuminate\Support\Facades\Http;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class RevisaPortas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'revisa:portas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revisa en el api de numero nacional las portas que se hayan hecho correctamente';

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
        // $lineas = Linea::currentStatus('Porta subida')


        // ->get();

        
        // foreach ($lineas as $linea) {

        //     $consulta = Http::asForm()->post('http://promoviles.herokuapp.com/api/movistar/getCarrier', [
        //         'linea' => $linea->dn,
                
        //     ]);

        //     $response = json_decode(substr($consulta, 4));

        //     if (isset($response->result[0]->key) && $response->result[0]->key == $linea->icc->company->code) {

        //         $linea->setStatus('Porta Exitosa');

        //         $linea->productoable->preactivated_at = Carbon::now();

        //         $linea->push();

               

        //         $this->info("$linea->dn ");
        //     }
        // }
        $lineas = Linea::currentStatus('Preactiva')  
        
        ->where('productoale_type','App\\Porta')->get();
    
         foreach($lineas as $linea){
            $linea->setStatus('Porta Exitosa');

         }
    }
}
