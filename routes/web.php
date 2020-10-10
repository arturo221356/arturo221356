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
use App\Http\Resources\InventarioResource;
use App\Distribution;
use App\IccProduct;
use App\User;
use App\Chip;
use App\Porta;
use App\Recarga;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\IccResource;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use SebastianBergmann\CodeUnit\TraitMethodUnit;

Auth::routes(['register' => false, 'reset' => false, 'password.reset' => false]);

Route::view('/home','home')->name('home')->middleware('auth');



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

    Route::view('/linea/preactivar', 'linea.preactivar')->middleware('can:preactivar linea');

    Route::view('/linea/reporte', 'linea.reporte');

    Route::post('/linea/verificar-icc', 'LineaController@verificarIcc')->middleware('can:preactivar linea');

    Route::post('/icc/restore', 'IccController@restore')->middleware('can:destroy stock');

    Route::post('/imei/restore', 'ImeisController@restore')->middleware('can:destroy stock');

    Route::post('/chip/activated', 'ChipController@getActivated');
    
    
    Route::resource('/inventario/traspasos', 'TraspasoController')->middleware('can:ver traspasos');

    Route::resource('/inventario','InventarioController');

    Route::resource('/imei', 'ImeisController');

    Route::resource('/icc', 'IccController');

    Route::resource('/linea', 'App\Http\Controllers\LineaController');

    Route::resource('/chip', 'ChipController');

    Route::resource('/venta', 'VentaController');


  

});

Route::get('/pruebas', function (Request $request) {

    Http::fake(function ($request) {
        return Http::response('Hello World', 500);
    });
   
   
    $response = Http::timeout(50)->asForm()->post('https://taecel.com/app/api/statusTXN', [
        'key' => 'c490127ff864a719bd89877f32a574de',
        'nip' => '0c4ae19986107edd5ebcec3c6e08a0d0',
         'transID'=>'200901909623'
    ]);
    

    

        return $response;
});




// searchs


Route::get('/search/traspaso-prediction', 'SearchController@traspasoPrediction')->middleware('auth');

Route::get('/search/traspaso-exact', 'SearchController@traspasoExact')->middleware('auth');


Route::get('/search/venta-prediction', 'SearchController@ventaPrediction')->middleware('auth');





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
