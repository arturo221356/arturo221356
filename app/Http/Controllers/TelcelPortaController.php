<?php

namespace App\Http\Controllers;

use App\TelcelPorta;
use Illuminate\Http\Request;
use App\Icc;
use App\Imports\TelcelPortaImport;
use Illuminate\Support\Facades\Auth;
use App\TelcelUser;
use App\Jobs\SubirTelcelPorta;
use Maatwebsite\Excel\Facades\Excel;


class TelcelPortaController extends Controller
{
    private $apiUrl;

    public function __construct()
    {       


        $this->apiUrl = 'http://portabilidad.telcel.com/PortabilidadCambaceo4.5/rest/ConsumeServicios?fmt=json';

        
    }

    public function checarPromoTelcel(Request $request)
    {

        $nombre = strtoupper($request->nombre);
        $apaterno = strtoupper($request->apaterno);
        $amaterno = strtoupper($request->amaterno);
        $curp = strtoupper($request->curp);
        $numero = $request->numero;
        $loggedUser = Auth::user();
        $telcelUser = TelcelUser::where('distribution_id', $loggedUser->distribution_id)->first();

        $telcelPorta = TelcelPorta::newTelcelPorta($this->apiUrl,$numero, $nombre, $apaterno, $amaterno, $curp, $telcelUser);

        return $telcelPorta;
    }

    public function portaTelcelExcelRandomClient(Request $request)
    {
        
        if ($request->hasFile('portas')) {

            foreach ($request->portas as $file) {

                $import = new TelcelPortaImport($this->apiUrl);

                Excel::import($import, $file);
            }
        }


    }




    
    public function confirmarPortaTelcel(Request $request)
    {
        $icc = $request->icc;
        $nip = $request->nip;
        $idcop = $request->idcop;
        $promo = $request->promo;
        $telcelUser = TelcelUser::where('distribution_id', Auth::user()->distribution_id)->first();

        $telcelPorta =  TelcelPorta::confirmTelcelPorta($icc, $idcop, $promo, $nip,  $telcelUser, $this->apiUrl);

        return $telcelPorta;

        
        
    }
    public function checkNumber(Request $request)
    {
        
        $number = $request->numero;

        $telcelPorta = TelcelPorta::where('dn', $number)->whereRaw('created_at >= now() - interval ? day', [4])->first();

        return $telcelPorta;
    }

    public function checkIcc(Request $request)
    {
       

        $user = Auth::user();

        $inventariosIds = $user->getInventariosForUserIds();

        $icc = Icc::where('icc', $request->icc)->where('company_id',1)->whereIn('inventario_id', $inventariosIds)->first();

        if ($icc === null) {
            $response = [
                'success' => false,
                'message' => 'Icc no existe en la base de datos'
            ];

            return $response;
        }
        if(!$icc->linea()->first() == null){

            $response = [
                "success" => false,
                "message" => "Icc ya tiene linea activa: " . $icc->linea->dn,

            ];

            return $response;
        }
        $response = [
            "success" => true,

        ];

        return $response;
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TelcelPorta  $telcelPorta
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $telcelPorta = TelcelPorta::find($id);

        $telcelPorta->delete();

    }
}
