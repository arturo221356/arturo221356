<?php

use App\Http\Controllers\ApiLoginController;
use Illuminate\Http\Request;



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


///login routes 

Route::post('/sanctum/logout', 'ApiLoginController@logout'); 

Route::post('/sanctum/login', 'ApiLoginController@login'); 

///////////////////


Route::post('/activa-chip', 'ChipController@recargaChip')->middleware('auth:sanctum');















//movistar Api
Route::post('/movistar/getCarrier', 'MovistarApiController@getCarrier');

Route::post('/movistar/getITX', 'MovistarApiController@getITX');

Route::post('/movistar/portaStatus', 'MovistarApiController@portaStatus');