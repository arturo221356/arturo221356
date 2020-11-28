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

use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Mail;

use App\Sucursal;

use App\Inventario;
use App\Linea;
use App\Mail\VentaComprobante;

use App\User;
use Spatie\Permission\Models\Permission;


use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;




Auth::routes(['register' => false, 'reset' => false, 'password.reset' => false]);

// Route::view('/home','home')->name('home')->middleware('auth');



Route::view('/','home')->name('home')->middleware('auth');


Route::view('/activa-chip','linea.activa-chip')->name('activa-chip');

Route::post('/recarga-chip','ChipController@recargaChip');


Route::group(['middleware' => ['role:super-admin|administrador']], function () {
   
        Route::view('/inventario/cargar', 'admin.inventario.cargarInv');

        
   
});


Route::group(['middleware' => ['auth']], function () {

    
    
    
    Route::post('/preactivar-prepago', 'ChipController@preactivarPrepago');

    Route::get('/get/icctypes', 'IccTypeController@index');

    Route::get('/get/companies', 'CompaniesController@index');

    Route::get('/get/equipos', 'EquiposController@index');

    Route::view('/linea/preactivar', 'linea.preactivar')->middleware('can:preactivar masivo');

    Route::view('/linea/reporte', 'linea.reporte');

    Route::post('/linea/verificar-icc', 'LineaController@verificarIcc')->middleware('can:preactivar masivo');

    Route::post('/icc/restore', 'IccController@restore')->middleware('can:destroy stock');

    Route::post('/imei/restore', 'ImeisController@restore')->middleware('can:destroy stock');

    Route::post('/chip/activated', 'ChipController@getActivated');
    
    
    Route::resource('/inventario/traspasos', 'TraspasoController')->middleware('can:ver traspasos');

    Route::resource('/inventario','InventarioController');

    Route::resource('/imei', 'ImeisController');

    Route::resource('/icc', 'IccController');

    Route::resource('/linea', 'LineaController');

    Route::resource('/chip', 'ChipController');

    Route::resource('/ventas', 'VentaController');

    Route::resource('/users', 'UsersController')->middleware('can:create user');

    Route::resource('/sucursales', 'SucursalController')->middleware('can:create sucursal');

    Route::resource('/recargas', 'RecargasController');

    Route::resource('/equipos', 'EquiposController');

    Route::resource('/transaction', 'TransactionController');
  

});

Route::get('/pruebas', function (Request $request) {

    $dns= [
        3327581876,
        3327628630,
        3324415433,
        3327636679,
        3316848943,
        3320777868,
        3327591515,
        3324118766,
        3314944689,
        3324366404,
        3327603115,
        3327570366,
        3327517416,
        3321180128,
        3314209760,
        3311003743,
        3327543742,
        3316379959,
        3320854148,
        3327593809,
        3327617097,
        3323943788,
        3327620365,
        3327598269,
        3316297424,
        3327597428,
        3327592429,
        3320868719,
        3312122993,
        3327623074,
        3316348321,
        3327632836,
        3324377443,
        3324364635,
        3327594334,
        3322974257,
        3322951300,
        3310512007
        
    ];

    // $response = [];
    foreach ($dns as $dn){

        $linea = Http::contentType("application/json-rpc")->bodyFormat('json')->post('http://pcportabilidad.movistar.com.mx:4080/PCMOBILE/catalogMobile', [
            'id' => mt_rand(100000, 999999),
            'method' => "getOperatorByMsisdn",
            'params' => [$dn]
    
        ]);

      echo "$linea <br>";
    }
        
    



     


});




// searchs


Route::get('/search/traspaso-prediction', 'SearchController@traspasoPrediction')->middleware('auth');

Route::get('/search/traspaso-exact', 'SearchController@traspasoExact')->middleware('auth');


Route::get('/search/venta-prediction', 'SearchController@ventaPrediction')->middleware('auth');

Route::get('/search/venta-exact', 'SearchController@ventaExact')->middleware('auth');


// apis 

Route::get('/get/icc-products', 'Admin\IccProductController@index')->middleware('auth');

Route::get('/get/icc-subproducts', 'Admin\IccSubProductController@index')->middleware('auth');

Route::get('/get/recargas', 'Admin\RecargasController@index')->middleware('auth');

Route::get('/get/roles', 'RoleController@getRoles')->middleware('auth');

// Route::namespace('Admin')->middleware('auth', 'role:admin',)->prefix('admin')->name('admin.')->group(function () {

  

//     



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



Route::get('/get/icctypes', 'IccTypeController@index')->middleware('auth');

// Route::get('/get/companies', 'Admin\CompaniesController@index')->middleware('auth');

// Route::post('/post/imeis/', 'InventarioController@getimeis')->middleware('auth');

// Route::get('/export/imeis/', 'InventarioController@exportImei')->middleware('auth');

// Route::get('/export/iccs/', 'InventarioController@exportIcc')->middleware('auth');

// Route::post('/post/iccs/', 'InventarioController@geticcs')->middleware('auth');



Route::get('/get/icc-subproducts', 'Admin\IccSubProductController@index')->middleware('auth');
//apis
