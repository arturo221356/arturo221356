<?php

namespace App\Http\Controllers;

use App\TelcelPorta;
use Illuminate\Http\Request;
use App\Icc;
use Illuminate\Support\Facades\Auth;

class TelcelPortaController extends Controller
{
    public function checarPromoTelcel(Request $request)
    {
        $nombre = strtoupper($request->nombre);
        $apaterno = strtoupper($request->apaterno);
        $amaterno = strtoupper($request->amaterno);
        $curp = strtoupper($request->curp);
        $numero = $request->numero;
        $pdv = '37746';

        $telcelPorta = TelcelPorta::newTelcelPorta($numero, $nombre, $apaterno, $amaterno, $curp, $pdv);

        return $telcelPorta;
    }




    
    public function confirmarPortaTelcel(Request $request)
    {
        $icc = $request->icc;
        $nip = $request->nip;
        $idcop = $request->idcop;
        $pdv = '37746';
        $promo = $request->promo;

        $telcelPorta =  TelcelPorta::confirmTelcelPorta($icc, $idcop, $promo, $nip, $pdv);

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
