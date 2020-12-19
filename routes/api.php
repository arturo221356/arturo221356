<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/revisar-exportadas', function (Request $request) {

    if($request->linea){
        $consulta = Http::contentType("application/json-rpc")->bodyFormat('json')->post('http://pcportabilidad.movistar.com.mx:4080/PCMOBILE/catalogMobile', [
            'id' => mt_rand(100000, 999999),
            'method' => "getOperatorByMsisdn",
            'params' => [$request->linea]
    
        ]);
    
        return $consulta;
    }else{
        return 'no hay request';
    }


});

Route::post('/revisar-trafico', function (Request $request) {

    if($request->linea){
        $consulta = Http::contentType("application/json-rpc")->bodyFormat('json')->post('http://pcportabilidad.movistar.com.mx:4080/PCMOBILE/catalogMobile', [
            'id' => mt_rand(100000, 999999),
            'method' => "checkITX",
            'params' => [$request->linea]
    
        ]);
    
        return $consulta;
    }else{
        return 'no hay request';
    }


});

