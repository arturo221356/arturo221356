<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//para pruebas
use App\Transaction;
use App\Icc;
use App\Linea;
use App\Imei;
use App\Inventario;
use App\Distribution;
use App\IccProduct;
use App\User;
use App\Chip;
use App\Porta;
use App\Recarga;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use SebastianBergmann\CodeUnit\TraitMethodUnit;

Auth::routes(['register' => false, 'reset' => false, 'password.reset' => false]);

Route::view('/home','home')->name('home')->middleware('auth');



Route::view('/','home')->name('home')->middleware('auth');


Route::view('/activa-chip','linea.activa-chip')->name('activa-chip');


Route::group(['middleware' => ['role:super-admin|administrador']], function () {
   
        Route::view('/inventario/cargar', 'admin.inventario.cargarInv');

        
   
});


Route::group(['middleware' => ['auth']], function () {

    Route::resource('/inventario/traspasos', 'TraspasoController')->middleware('can:ver traspasos');

    Route::resource('/inventario','InventarioController');

    Route::resource('/imei', 'ImeisController');

    Route::resource('/icc', 'IccController');

    Route::post('/preactivar-prepago', 'LineaController@preactivarPrepago');

    Route::get('/get/icctypes', 'IccTypeController@index');

    Route::get('/get/companies', 'CompaniesController@index');

    Route::get('/get/equipos', 'EquiposController@index');

    Route::view('/linea/preactivar', 'linea.preactivar')->middleware('can:preactivar linea');

    Route::post('/linea/verificar-icc', 'LineaController@verificarIcc')->middleware('can:preactivar linea');
  

});

Route::get('/pruebas', function (Request $request) {
    

    // $message = [];
    
    // $monto = 100;

    // $user = Auth::user();

    // $dn = '3310512007';

    // $linea = Linea::where('dn',$dn)->first();

    // $taecelKey = $linea->icc->inventario->distribution->taecel_key; 

    // $taecelNip = $linea->icc->inventario->distribution->taecel_nip;



    

  

    //     if($linea == null){

    //         $message = [
    //             'success' => false,
    //             'message' => 'Numero no existe en la base de datos',

    //         ];

    //         return json_encode($message);
    //     }
    //     if($linea->status() == 'asdasd'){
    //         $message = [
    //             'success' => false,
    //             'message' => 'Numero ya activado anteriormente',

    //         ];

    //         return json_encode($message);
    //     }


    // $recarga = Recarga::where([['monto','=',$monto],['company_id','=',$linea->icc->company_id]])->first();

       

    // $res = Http::asForm()->post('https://taecel.com/app/api/RequestTXN', [
    //     'key' => $taecelKey,
    //     'nip' => $taecelNip,
    //     'producto'=> $recarga->taecel_code,
    //     'referencia' => $dn
    // ]);
    // $response = json_decode($res);

    // $trasnsaction = new Transaction;

    // $trasnsaction->taecel = true;

    // $trasnsaction->taecel_success = $response->success;

    // if($response->data){
    //     $trasnsaction->taecel_transID = $response->data->transID;
    // }
    

    // $trasnsaction->taecel_message =  $response->message;

    // $trasnsaction->monto = $recarga->monto;

    // $trasnsaction->dn = $dn;

    // $trasnsaction->company_id = $recarga->company_id;

    // $trasnsaction->recarga_id = $recarga->id;

    // $trasnsaction->inventario_id = $linea->icc->inventario_id;

    // $trasnsaction->save();



    // return $trasnsaction;

        $inventarios = Inventario::all();

        return $inventarios;

});




// searchs


Route::get('/search/traspaso-prediction', 'SearchController@traspasoPrediction')->middleware('auth');

Route::get('/search/traspaso-exact', 'SearchController@traspasoExact')->middleware('auth');





// Route::namespace('Admin')->middleware('auth', 'role:admin',)->prefix('admin')->name('admin.')->group(function () {

//     Route::resource('/users', 'UsersController');

//     Route::resource('/sucursales', 'SucursalController');

//     Route::resource('/productos/recargas', 'RecargasController');

//     Route::resource('/productos/equipos', 'EquiposController');

//     Route::resource('/productos/sims', 'IccProductController');



//    

//     Route::view('/productos', 'admin.productos');

//     

//     Route::resource('/inventarios', 'inventarioController');

//     Route::view('/productos', 'admin.productos.index');

//     


//     Route::view('/', 'admin.index');
// });


// Route::resource('/inventario', 'Admin\InventarioController')->middleware('auth');





    

// });




//apissssss

Route::get('/get/status', 'Admin\StatusController@getStatus')->middleware('auth');



Route::get('/get/recargas', 'Admin\RecargasController@index')->middleware('auth');

Route::get('/get/icctypes', 'IccTypeController@index')->middleware('auth');

// Route::get('/get/companies', 'Admin\CompaniesController@index')->middleware('auth');

// Route::post('/post/imeis/', 'InventarioController@getimeis')->middleware('auth');

// Route::get('/export/imeis/', 'InventarioController@exportImei')->middleware('auth');

// Route::get('/export/iccs/', 'InventarioController@exportIcc')->middleware('auth');

// Route::post('/post/iccs/', 'InventarioController@geticcs')->middleware('auth');

// Route::get('/get/icc-products', 'Admin\IccProductController@index')->middleware('auth');

// Route::get('/get/icc-subproducts', 'Admin\IccSubProductController@index')->middleware('auth');
//apis
