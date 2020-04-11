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

use App\Http\Controllers\Admin\RecargasController;
use App\Sucursal;

Auth::routes(['register' => false , 'reset' => false , 'password.reset' => false]);

// Route::get("/", "baseController@index")->middleware('auth');




Route::namespace('Admin')->middleware('auth','role:admin',)->prefix('admin')->name('admin.')->group(function(){
    Route::get('/',function(){
        return view('admin.index');
    });
    Route::resource('/users','UsersController');
    Route::resource('/sucursales','SucursalController');
    Route::resource('/productos/recargas','RecargasController');
    Route::resource('/productos/equipos','EquiposController');
    Route::get('/productos',function(){
        return view('admin.productos.index');
    });
    Route::resource('/imei','ImeisController');
    Route::resource('/icc','IccController');
    Route::get('/inventario/cargar',function(){
        return view('admin.inventario.cargarInv');
    });

    
});



Route::get('/inventario', 'InventarioController@index')->middleware('auth');



//apissssss
Route::get('/get/sucursales', 'Admin\SucursalController@getSucursales')->middleware('auth');

Route::get('/get/status', 'Admin\StatusController@getStatus')->middleware('auth');

Route::get('/get/equipos', 'Admin\EquiposController@getEquipos')->middleware('auth');

Route::post('/post/imeis/', 'InventarioController@getimeis')->middleware('auth');

Route::get('/export/imeis/', 'InventarioController@exportImei')->middleware('auth');

Route::get('/export/iccs/', 'InventarioController@exportIcc')->middleware('auth');

Route::post('/post/iccs/', 'InventarioController@geticcs')->middleware('auth');

//apis




Route::get('/home', function(){
    
    $redir = Auth::user()->roleName();
    return redirect("$redir/");
})->name('home');

Route::get('/', function(){
   
    $redir = Auth::user()->roleName();
    return redirect("$redir/");
})->name('home')->middleware('auth');







