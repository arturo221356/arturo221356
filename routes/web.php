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

use App\Venta;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\VentaComprobante;




Auth::routes([
    'register' => false,
    //  'reset' => false, 
    //  'password.reset' => false
]);

Route::view('/home', 'home')->name('home')->middleware('auth');



Route::view('/', 'home')->name('root')->middleware('auth');


Route::view('/activa-chip', 'linea.activa-chip')->name('activa-chip');

Route::view('/porta-express', 'linea.porta-express')->name('porta-express');

Route::post('/recarga-chip', 'ChipController@recargaChip');

Route::post('/linea/verificar-icc-externo', 'LineaController@verificarIccPortaExterna');

Route::post('/new/porta-externo', 'PortaController@newExternoPorta');


Route::group(['middleware' => ['role:super-admin|administrador']], function () {

    Route::view('/inventario/cargar', 'admin.inventario.cargarInv');
});


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



    Route::post('/icc/restore', 'IccController@restore')->middleware('can:destroy stock');

    Route::post('/imei/restore', 'ImeisController@restore')->middleware('can:destroy stock');

    Route::post('/chip/activated', 'ChipController@getActivated');

    Route::post('/get/porta', 'PortaController@getPortas');

    Route::post('/get/exportada', 'LineaController@getExportadas');


    Route::resource('/inventario/traspasos', 'TraspasoController')->middleware('can:ver traspasos');

    Route::resource('/inventario', 'InventarioController');

    Route::resource('/imei', 'ImeisController');

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

    Route::resource('/recargas', 'Admin\RecargasController');

    Route::resource('/equipos', 'EquiposController');

    Route::resource('/transaction', 'TransactionController');
});

Route::get('/pruebas', function (Request $request) {

   $venta = Venta::find(2);

    // $seller = new Party([
    //     'name'          => $venta->inventario->distribution->name,
    //     'address'       => $venta->inventario->inventarioable->address,

    //     'custom_fields' => [
    //         'sucursal' =>  $venta->inventario->inventarioable->name,
    //         'vendedor'          => $venta->user->name,

    //     ],
    // ]);



    // $customer = new Buyer([
    //     'name'          => $venta->cliente->name,
    //     'custom_fields' => [
    //         'Correo' => $venta->cliente->email,
    //         'RFC'  => $venta->cliente->rfc,
    //         'CURP' =>  $venta->cliente->curp,
    //     ],
    // ]);

    // $items = [];

    // $imeis = $venta->imeis()->with('equipo')->get();

    // foreach($imeis as $imei){
        
    //     array_push($items, (new InvoiceItem())->title($imei->equipo->marca.'  '.$imei->equipo->modelo.'  '.$imei->imei)->pricePerUnit($imei->pivot->price));
        
    // }

    // $iccs = $venta->iccs()->with('linea','company','linea.product','linea.subProduct')->get();

    // foreach($iccs as $icc){
        
    //     array_push($items, (new InvoiceItem())->title($icc->linea->dn.' '.$icc->linea->subProduct->name.'  '.$icc->linea->product->name.'  '.$icc->company->name.'  '.$icc->icc)->pricePerUnit($icc->pivot->price));
        
    // }

    // $transactions = $venta->transactions()->with('recarga')->get();

    // foreach($transactions as $transaction){
        
    //     array_push($items, (new InvoiceItem())->title($transaction->company->name.'  '. $transaction->recarga->name.'  '.$transaction->dn)->pricePerUnit($transaction->pivot->price));
        
    // }

    // $generales = $venta->generalProducts;

    // foreach($generales as $vtageneral){
        
    //     array_push($items, (new InvoiceItem())->title($vtageneral->name.'  '. $vtageneral->description)->pricePerUnit($vtageneral->pivot->price));
        
    // }





    // // $item = (new InvoiceItem())->title('Service 1')->pricePerUnit(2);

    // $invoice = Invoice::make('Comprobante')
    //     ->buyer($customer)
    //     ->seller($seller)
    //     ->date($venta->created_at)
    //     ->sequence($venta->id)
    //     ->filename('invoices/Comprobante_'.$venta->id)
    //     ->addItems($items);

    // return $invoice->save('local')->url();

    //  Mail::to('arturo@aosd.com')->send(new VentaComprobante($venta));

    return Storage::download('invoices/Comprobante_2.pdf');



   
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
