<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Porta;
use Illuminate\Support\Facades\Http;
use App\Transaction;

class TransactionPortas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'revisar:itx';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'revisa la interconexion';

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

         $portas = Porta::all();

        foreach ($portas as $porta) {
            if(isset($porta->linea->dn)){
               
                $consulta = Http::asForm()->post('http://promoviles.herokuapp.com/api/revisar-trafico', [
                    'linea' => $porta->linea->dn,
                    
                ]);
               
                $response = json_decode(substr($consulta, 4));
                
                if(isset($response->result->code)){

                    if($response->result->code == 1){
                        $porta->trafico_real = true;
                    }else{
                        $porta->trafico_real = false;
                    }
                   

                    $porta->save();

                    info(json_encode($response->result->code));
                }
               
                
    
                

            }
            

        }
    }
}
