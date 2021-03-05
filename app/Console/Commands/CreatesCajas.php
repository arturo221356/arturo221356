<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Sucursal;

use App\User;

class CreatesCajas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:cajas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea las Cajas de los usuarios y sucursales existentes';

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
       
        $sucursales = Sucursal::all();

        $users = User::all();

        foreach($users as $user){
            if($user->hasAnyRole(['administrador', 'supervisor'])){

                $user->caja()->firstOrCreate(['total' => 0]);
                
            }
           
            
        }

        foreach($sucursales as $sucursal){

            $sucursal->inventario->caja()->firstOrCreate(['total' => 0]);
            
        }


    }
}
