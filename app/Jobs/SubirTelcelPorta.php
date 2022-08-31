<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\PortaClient;
use App\TelcelPorta;
use Illuminate\Support\Facades\Auth;


class SubirTelcelPorta implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $numero, $nip, $telcelUser, $apiUrl, $icc, $promo;



    public function __construct($numero, $nip, $telcelUser, $apiUrl, $icc, $promo)
    {
        $this->numero = $numero;

        $this->nip = $nip;

        $this->telcelUser = $telcelUser;

        $this->apiUrl = $apiUrl;

        $this->icc = $icc;

        $this->promo = $promo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $portaClient = PortaClient::where('counter','<',5)->inRandomOrder()->first();
        $nombre = strtoupper($portaClient->nombre);
        $apaterno = strtoupper($portaClient->apaterno);
        $amaterno = strtoupper($portaClient->amaterno);
        $curp = strtoupper($portaClient->curp);

         $telcelPorta = TelcelPorta::newTelcelPorta($this->apiUrl,$this->numero, $nombre, $apaterno, $amaterno, $curp, $this->telcelUser);

        $object = json_decode(json_encode($telcelPorta), FALSE);

        if($object->respuesta->error == false){

            $idcop = $object->respuesta->datosPorta->idCop;

            $confirmTelcelPorta = TelcelPorta::confirmTelcelPorta($this->icc, $idcop, $this->promo, $this->nip,  $this->telcelUser, $this->apiUrl);
        
        }
    }
}
