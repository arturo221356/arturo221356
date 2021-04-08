<?php

use App\Http\Controllers\ApiLoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Auth;
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

Route::middleware('auth:sanctum')->post('/user', function (Request $request) {
    return Auth::user();
});


Route::post('/sanctum/login', 'ApiLoginController@login'); 



Route::post('/revisar-exportadas', function (Request $request) {

    if($request->linea){
        $consulta = Http::contentType("application/json-rpc")->bodyFormat('json')->post('https://pcportabilidad.movistar.com.mx:4082/PCMOBILE/catalogMobile', [
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
        $consulta = Http::contentType("application/json-rpc")->bodyFormat('json')->post('https://pcportabilidad.movistar.com.mx:4082/PCMOBILE/catalogMobile', [
            'id' => mt_rand(100000, 999999),
            'method' => "checkITX",
            'params' => [$request->linea]
    
        ]);
    
        return $consulta;
    }else{
        return 'no hay request';
    }


});