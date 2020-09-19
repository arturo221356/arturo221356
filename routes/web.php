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

use Illuminate\Support\Facades\Auth;

//borrar
use App\Icc;
use App\Inventario;
use App\Distribution;

use Illuminate\Http\Request;

Auth::routes(['register' => false, 'reset' => false, 'password.reset' => false]);

Route::view('/home','home')->name('home')->middleware('auth');



    Route::view('/','home')->name('home')->middleware('auth');


Route::group(['middleware' => ['role:super-admin|administrador|supervisor']], function () {
   
        Route::view('/inventario/cargar', 'admin.inventario.cargarInv');
   
});
Route::group(['middleware' => ['auth',]], function () {
    Route::resource('/inventario','Admin\InventarioController');

});

// Route::namespace('Admin')->middleware('auth', 'role:admin',)->prefix('admin')->name('admin.')->group(function () {

//     Route::resource('/users', 'UsersController');

//     Route::resource('/sucursales', 'SucursalController');

//     Route::resource('/productos/recargas', 'RecargasController');

//     Route::resource('/productos/equipos', 'EquiposController');

//     Route::resource('/productos/sims', 'IccProductController');

//     Route::resource('/imei', 'ImeisController');

//     Route::resource('/icc', 'IccController');

//     Route::resource('/roles', 'RoleController');

//     Route::view('/productos', 'admin.productos');

//     Route::view('/inventario/cargar', 'admin.inventario.cargarInv');

//     Route::resource('/inventarios', 'inventarioController');

//     Route::view('/productos', 'admin.productos.index');

//     Route::resource('/inventario/traspasos', 'TraspasoController');


//     Route::view('/', 'admin.index');
// });


// Route::resource('/inventario', 'Admin\InventarioController')->middleware('auth');




// Route::get('/pruebas', function (Request $request) {
   
//     $icc = Icc::find(1001);

//     $allPendingModels = Icc::currentStatus('Perdido')->get();

//     $respone = $icc->statuses; 
//     return $respone;
    

// });
// searchs

// Route::get('/search/venta-prediction', 'SearchController@ventaPrediction')->middleware('auth');

// Route::get('/search/venta-exact', 'SearchController@ventaExact')->middleware('auth');

// Route::get('/search/traspaso-prediction', 'SearchController@traspasoPrediction')->middleware('auth');

// Route::get('/search/traspaso-exact', 'SearchController@traspasoExact')->middleware('auth');



//apissssss

Route::get('/get/status', 'Admin\StatusController@getStatus')->middleware('auth');

Route::get('/get/equipos', 'Admin\EquiposController@index')->middleware('auth');

Route::get('/get/recargas', 'Admin\RecargasController@index')->middleware('auth');

Route::get('/get/icctypes', 'IccTypeController@index')->middleware('auth');

Route::get('/get/companies', 'Admin\CompaniesController@index')->middleware('auth');

// Route::post('/post/imeis/', 'InventarioController@getimeis')->middleware('auth');

// Route::get('/export/imeis/', 'InventarioController@exportImei')->middleware('auth');

// Route::get('/export/iccs/', 'InventarioController@exportIcc')->middleware('auth');

// Route::post('/post/iccs/', 'InventarioController@geticcs')->middleware('auth');

// Route::get('/get/icc-products', 'Admin\IccProductController@index')->middleware('auth');

// Route::get('/get/icc-subproducts', 'Admin\IccSubProductController@index')->middleware('auth');
//apis
