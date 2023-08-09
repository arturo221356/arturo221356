<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\TelcelUser;

class ProcessTelmexLogOut implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $urlapi = 'https://portabilidad.telcel.com/PortabilidadCambaceo4.7/rest/ConsumeServicios?fmt=json';

        $telcelUser = TelcelUser::find(5);

        $consulta1 = TelcelUser::checkIn($urlapi, $telcelUser, "20.352061", "-102.775223", "2");

        $consulta2 = TelcelUser::posicion($urlapi, $telcelUser, "20.352061", "-102.775223", "CheckOut");

        $consulta3 = TelcelUser::checkInOutReport($urlapi, $telcelUser);

        $consulta4 = TelcelUser::logOut($urlapi, $telcelUser);
    }
}
