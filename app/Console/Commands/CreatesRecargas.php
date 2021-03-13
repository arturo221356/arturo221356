<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Recarga;

class CreatesRecargas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crear:recargas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        $montos = [20,30,50,100,150,200];

    


        foreach($montos as $monto){

            if($monto >= 100){
                $codigo = $monto;
            }else{
                $codigo = '0'.$monto;
            }

            $recarga = new Recarga;

            $recarga->name = 'Internet Telcel '.$monto;

            $recarga->monto = $monto;

            $recarga->codigo =  'tia'.$codigo;

            $recarga->taecel_code = 'tia'.$codigo;

            $recarga->company_id = 1;

            $recarga->save();



          
        }

        return 0;
    }
}
