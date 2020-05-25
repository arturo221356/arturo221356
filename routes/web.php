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
use App\Distribution;
use App\Sucursal;
use App\Role;
use Illuminate\Support\Facades\Auth;

Auth::routes(['register' => false, 'reset' => false, 'password.reset' => false]);

Route::get('/home', function () {

    $redir = Auth::user()->role->name;
    return redirect("$redir/");
})->name('home');

Route::get('/', function () {

    $redir = Auth::user()->role->name;
    return redirect("$redir/");
})->name('home')->middleware('auth');





Route::namespace('Admin')->middleware('auth', 'role:admin',)->prefix('admin')->name('admin.')->group(function () {

    Route::resource('/users', 'UsersController');
    Route::resource('/sucursales', 'SucursalController');
    Route::resource('/productos/recargas', 'RecargasController');
    Route::resource('/productos/equipos', 'EquiposController');
    Route::resource('/imei', 'ImeisController');
    Route::resource('/icc', 'IccController');
    Route::resource('/roles', 'RoleController');

    Route::view('/productos', 'admin.productos');

    Route::view('/inventario/cargar', 'admin.inventario.cargarInv');

    Route::view('/productos', 'admin.productos.index');

    Route::view('/', 'admin.index');
});



Route::get('/inventario', 'InventarioController@index')->middleware('auth');


Route::get('/pruebas', function()
{

    $userDistribution = Auth::User()->distribution()->id;

    $distribution = Distribution::find($userDistribution);

    $equipos = $distribution->equipos()->get();

    return response()->json($equipos);
});


//apissssss
Route::get('/get/sucursales', 'Admin\SucursalController@getSucursales')->middleware('auth');

Route::get('/get/status', 'Admin\StatusController@getStatus')->middleware('auth');

Route::get('/get/equipos', 'Admin\EquiposController@index')->middleware('auth');

Route::post('/post/imeis/', 'InventarioController@getimeis')->middleware('auth');

Route::get('/export/imeis/', 'InventarioController@exportImei')->middleware('auth');

Route::get('/export/iccs/', 'InventarioController@exportIcc')->middleware('auth');

Route::post('/post/iccs/', 'InventarioController@geticcs')->middleware('auth');

//apis
