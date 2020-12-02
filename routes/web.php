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




Auth::routes([
    'register' => false,
    //  'reset' => false, 
    //  'password.reset' => false
     ]
    );

 Route::view('/home','home')->name('home')->middleware('auth');



Route::view('/','home')->name('root')->middleware('auth');


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

    Route::resource('/recargas', 'Admin\RecargasController');

    Route::resource('/equipos', 'EquiposController');

    Route::resource('/transaction', 'TransactionController');
  

});

Route::get('/pruebas', function (Request $request) {

    $dns= [
        3321233580,   
        
    ];

    // $response = [];
    foreach ($dns as $dn){

        $linea = Http::contentType("application/json-rpc")->bodyFormat('json')->post('http://pcportabilidad.movistar.com.mx:4080/PCMOBILE/catalogMobile', [
            'id' => mt_rand(100000, 999999),
            'method' => "getOperatorByMsisdn",
            'params' => [$dn]
    
        ]);
    
        $response = json_decode(substr($linea, 4));
     
        echo $response->result[0]->key;
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
