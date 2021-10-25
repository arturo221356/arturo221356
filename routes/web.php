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

use App\Exports\IccsClieanExport;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use App\Porta;

use App\Linea;

use App\Taecel;

use App\Transaction;

Auth::routes([
    'register' => false,
    //  'reset' => false, 
    //  'password.reset' => false
]);

Route::view('/home', 'home')->name('home')->middleware('auth');



Route::view('/', 'home')->name('root')->middleware('auth');

Route::view('/privacidad', 'privacidad')->name('privacidad');


Route::view('/activa-chip', 'linea.activa-chip')->name('activa-chip');

Route::view('/porta-express', 'linea.porta-express')->name('porta-express');

Route::post('/recarga-chip', 'ChipController@recargaChip');

Route::post('/linea/verificar-icc-externo', 'LineaController@verificarIccPortaExterna');

Route::post('/new/porta-externo', 'PortaController@newExternoPorta');


Route::group(['middleware' => ['role:super-admin|administrador']], function () {

    Route::resource('/comisiones',  'ComisionController');

    Route::view('/inventario/cargar', 'admin.inventario.cargarInv');
});



Route::get('/venta/comprobante', 'VentaController@getInvoice');


Route::group(['middleware' => ['auth']], function () {


    Route::view('/cuentas', 'cuentas.index')->middleware('can:ver cuentas');

    Route::post('/preactivar-prepago', 'ChipController@preactivarPrepago');

    Route::get('/get/icctypes', 'IccTypeController@index');

    Route::get('/get/companies', 'CompaniesController@index');

    Route::get('/get/equipos', 'EquiposController@index');

    Route::post('/ventas/perday', 'VentaController@totalPerDay');


    Route::post('/get/cajas', 'CajaController@getCajas');

    Route::post('/own/caja', 'CajaController@getOwnCaja');

    Route::post('/get/cortes', 'CorteController@getAll');

    Route::view('/linea/preactivar', 'linea.preactivar')->middleware('can:preactivar masivo');

    Route::view('/linea/reporte', 'linea.reporte');

    Route::post('/linea/verificar-icc', 'LineaController@verificarIcc')->middleware('can:preactivar masivo');

    Route::post('/linea/exportar/excel', 'LineaController@exportExcelLineas');



    Route::post('/icc/restore', 'IccController@restore')->middleware('can:destroy stock');

    Route::post('/imei/restore', 'ImeisController@restore')->middleware('can:destroy stock');

    Route::post('/chip/activated', 'ChipController@getActivated');

    Route::post('/get/porta', 'PortaController@getPortas');

    Route::post('/get/exportada', 'LineaController@getExportadas');


    Route::resource('/inventario/traspasos', 'TraspasoController')->middleware('can:ver traspasos');

    Route::resource('/inventario', 'InventarioController');

    Route::resource('/imei', 'ImeisController');

    Route::resource('/otros', 'OtroController');

    Route::resource('/icc', 'IccController');

    Route::resource('/linea', 'LineaController');

    Route::resource('/chip', 'ChipController');

    Route::resource('/gasto', 'GastoController');

    Route::resource('/income', 'IncomeController');

    Route::resource('/corte', 'CorteController');

    Route::post('/get/gastos', 'GastoController@getAll');

    Route::post('/get/incomes', 'IncomeController@getAll');

    Route::resource('/ventas', 'VentaController');

    Route::resource('/users', 'UsersController')->middleware('can:create user');

    Route::resource('/sucursales', 'SucursalController')->middleware('can:create sucursal');

    Route::resource('/grupos', 'GrupoController')->middleware('can:create sucursal');

    Route::resource('/recargas', 'Admin\RecargasController');

    Route::resource('/equipos', 'EquiposController');

    Route::resource('/transaction', 'TransactionController');


    // searchs


    Route::get('/search/traspaso-prediction', 'SearchController@traspasoPrediction');

    Route::get('/search/traspaso-exact', 'SearchController@traspasoExact');


    Route::get('/search/venta-prediction', 'SearchController@ventaPrediction');

    Route::get('/search/venta-exact', 'SearchController@ventaExact');


    // apis 

    Route::get('/get/icc-products', 'Admin\IccProductController@index');

    Route::get('/get/icc-subproducts', 'Admin\IccSubProductController@index');

    Route::get('/get/recargas', 'Admin\RecargasController@index');

    Route::get('/get/roles', 'RoleController@getRoles');

    Route::get('/get/otros', 'OtroController@getOtros');

    Route::post('/delete/otros', 'OtroController@deleteInventario');

    Route::post('/update/otros', 'OtroController@updateInventario');

    Route::post('/add/otros', 'OtroController@addOtros');

    Route::get('/get/status', 'Admin\StatusController@getStatus');

    Route::get('/get/icctypes', 'IccTypeController@index');

    Route::get('/get/icc-subproducts', 'Admin\IccSubProductController@index');
    
});


use App\Otro;
use App\Http\Resources\OtroResource;



Route::get('/pruebas', function (Request $request) {
    

    $inventarrio->otros()->withTrashed()->get();


});



