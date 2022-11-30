<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\TelcelUser;

class TelcelLogOut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telcel:logout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'does logout at telcel api';

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
        $urlapi = 'http://portabilidad.telcel.com/PortabilidadCambaceo4.6/rest/ConsumeServicios?fmt=json';

        $telcelUsers = TelcelUser::whereIn('id', [1, 2, 3])->get();

        $working = app('valuestore')->get('telcel_porta_working') ?? false;

        if ($working == true) {
            foreach ($telcelUsers as $telceUser) {

                $consulta = TelcelUser::logOut($urlapi, $telceUser);
                $this->info($consulta);
            }
        }



        return 0;
    }
}
