<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Serverless\V1\Service\FunctionInstance;
use Illuminate\Support\Facades\Http;

class MovistarApiController extends Controller
{
    public function getITX(Request $request){

        if($request->linea){

            $consulta = Http::contentType("application/json-rpc")->bodyFormat('json')->post('https://pcportabilidad.movistar.com.mx:4082/PCMOBILE/catalogMobile', [
                'id' => mt_rand(100000, 999999),
                'method' => "checkITX",
                'params' => [$request->linea]
        
            ]);
        
            return $consulta;
        }
    
    
    }

    public function getCarrier(Request $request){

        if($request->linea){

            $consulta = Http::contentType("application/json-rpc")->bodyFormat('json')->post('https://pcportabilidad.movistar.com.mx:4082/PCMOBILE/catalogMobile', [
                'id' => mt_rand(100000, 999999),
                'method' => "getOperatorByMsisdn",
                'params' => [$request->linea]
        
            ]);
        
            return $consulta;
        }
    
    }
    public function portaStatus(Request $request){

        if($request->linea){

            $consulta = Http::contentType("application/json-rpc")->bodyFormat('json')->post('https://pcportabilidad.movistar.com.mx:4082/PCMOBILE/catalogMobile', [
                'id' => mt_rand(100000, 999999),
                'method' => "getImportDataV2",
                'params' => [null,null,null,$request->linea,null,null,null,null,true]
        
            ]);
        
            return $consulta;
        }
    
    }


}
