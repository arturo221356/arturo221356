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

        $montos = [15,20,30,50,70,100,120,150,200];

    


        foreach($montos as $monto){

            if($monto >= 100){
                $codigo = $monto;
            }else{
                $codigo = '0'.$monto;
            }

            $recarga = new Recarga;

            $recarga->name = 'Recarga Unefon '.$monto;

            $recarga->monto = $monto;

            $recarga->codigo =  'une'.$codigo;

            $recarga->taecel_code = 'une'.$codigo;

            $recarga->company_id = 4;

            $recarga->save();



          
        }

        return 0;
    }
}
