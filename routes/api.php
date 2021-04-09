<?php

use App\Http\Controllers\ApiLoginController;
use Illuminate\Http\Request;


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

//movistar Api
Route::post('/movistar/getCarrier', 'MovistarApiController@getCarrier');

Route::post('/movistar/getITX', 'MovistarApiController@getITX');

Route::post('/movistar/portaStatus', 'MovistarApiController@portaStatus');