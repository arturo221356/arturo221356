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

use Illuminate\Support\Carbon;

use App\User;
use Spatie\Permission\Models\Permission;
use App\Icc;


use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;




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

    Route::post('/linea/verificar-icc-externo', 'LineaController@verificarIccPortaExterna');

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

    Route::resource('/ventas', 'VentaController');

    Route::resource('/users', 'UsersController')->middleware('can:create user');

    Route::resource('/sucursales', 'SucursalController')->middleware('can:create sucursal');

    Route::resource('/recargas', 'Admin\RecargasController');

    Route::resource('/equipos', 'EquiposController');

    Route::resource('/transaction', 'TransactionController');
});

Route::get('/pruebas', function (Request $request) {

    $lineas = [
        
        3320207158,
3320207248,
3329636063,
3321561043,
3329259759,
3329674433,
3318417004,
3329620117,
3316950533,
3317388645,
3329480403,
3334756512,
3326313842,
3311511126,
3320207168,
3320207287,
3320207290,
3312552303,
3320207201,
3320207418,
3329279030,
3328303288,
3315113753,
3317459070,
3314073428,
3311401308,
3317510880,
3329636643,
3329275667,
3314342498,
3320207375,
3311541043,
3329260058,
3331953157,
3329667315,
3339532601,
3310915153,
3315133365,
3319015813,
3310639005,
3329615410,
3329247057,
3329608920,
3329680230,
3325973343,
3320303842,
3334491119,
3329227290,
3320540739,
3337278399,
3320207412,
3921594108,
3320207431,
3328376688,
3332562321,
3327837258,
3332562621,
3331831082,
3327837129,
3332562493,
3332562318,
3327927373,
3322075707,
3327837014,
3325972991,
3320567270,
3328382513,
3332562554,
3327836954,
3327838663,
3312512519,
3329259045,
3328120089,
3331075019,
3921594065,
3332562200,
3327811101,
3330338884,
3321888610,
3328396243,
3330327040,
3329247225,
3326249451,
3332562512,
3332562356,
3332562569,
3317358423,
3329246407,
3312546824,
3322441734,
3318701379,
3332562214,
3327820205,
3327837154,
3332562210,
3314861995,
3317479332,
3313667646,
3310066560,
3312953209,
3315133006,
3327814525,
3327837026,
3327837300,
3332562357,
3921594089,
3921098886,
3313452867,
3327820176,
3327837146,
3327820204,
3322501318,
3332562201,
3327837027,
3310959672,
3319165752,
3327820180,
3327837301,
3327820175,
3327820170,
3329148447,
3327817175,
3327817197,
3327839855,
3334936739,
3329634105,
3313592334,
3332562384,
3327835662,
3327825420,
3327817133,
3327829015,
3327927370,
3326000075,
3332562495,
3328382374,
3328097763,
3330235785,
3322208714,
3330266839,
3328106637,
3330182281,
3330218566,
3330235788,
3327835692,
3314361856,
3318271344,
3335076582,
3314156893,
3327836944,
3312523233,
3330260779,
3330260807,
3331310546,
3317609546,

        








    ];


    foreach ($lineas as $linea) {

        $consulta = Http::asForm()->post('http://promoviles.herokuapp.com/api/revisar-exportadas', [
            'linea' => $linea,
            
        ]);

        echo $consulta;
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
