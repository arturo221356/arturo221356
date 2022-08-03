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

use App\Chip;
use App\Http\Resources\IccResource;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;


Auth::routes([
    'register' => false,
    //  'reset' => false, 
    //  'password.reset' => false
]);

Route::view('/home', 'home')->name('home')->middleware('auth');



Route::view('/', 'home')->name('root')->middleware('auth');

Route::view('/privacidad', 'privacidad')->name('privacidad');


Route::view('/activa-chip', 'linea.activa-chip')->name('activa-chip');

Route::view('/porta-express', 'linea.porta-express')->middleware('auth')->name('porta-express');

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

    Route::get('/equipo/reporte', 'EquiposController@reporte');

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

    Route::post('/get/imeis-vendidos', 'ImeisController@getImeiVendidos');

    Route::resource('/otros', 'OtroController');

    Route::get('/reporte/otros', 'OtroController@reporte');

    Route::post('/get/accesorios-vendidos', 'OtroController@getOtrosVendidos');

    Route::resource('/icc', 'IccController');

    Route::post('/calculator/icc', 'IccController@calculator');

    Route::resource('/linea', 'LineaController');

    Route::resource('/chip', 'ChipController');

    Route::resource('/gasto', 'GastoController');

    Route::resource('/income', 'IncomeController');

    Route::resource('/corte', 'CorteController');

    Route::post('/get/gastos', 'GastoController@getAll');

    Route::post('/get/incomes', 'IncomeController@getAll');

    Route::post('/get/ventas', 'VentaController@getVentas');

    Route::resource('/ventas', 'VentaController');

    Route::resource('/users', 'UsersController')->middleware('can:create user');

    Route::resource('/sucursales', 'SucursalController')->middleware('can:create sucursal');

    Route::resource('/grupos', 'GrupoController')->middleware('can:create sucursal');

    Route::resource('/recargas', 'Admin\RecargasController');

    Route::resource('/equipos', 'EquiposController');

    Route::resource('/transaction', 'TransactionController');


    // searchs
    Route::post('/search/navbar-search', 'SearchController@navbarSearch');

    Route::get('/search/traspaso-prediction', 'SearchController@traspasoPrediction');

    Route::get('/search/traspaso-exact', 'SearchController@traspasoExact');

    Route::post('/search/traspaso-excel', 'SearchController@traspasoFromExcel');


    Route::get('/search/venta-prediction', 'SearchController@ventaPrediction');

    Route::get('/search/venta-exact', 'SearchController@ventaExact');


    // apis 
    Route::post('/check/Itx', 'LineaController@checkItx');



    Route::post('/check/company', 'LineaController@checkCompany');

    Route::get('/get/icc-products', 'Admin\IccProductController@index');

    Route::get('/get/icc-subproducts', 'Admin\IccSubProductController@index');

    Route::get('/get/recargas', 'Admin\RecargasController@index');

    Route::get('/get/roles', 'RoleController@getRoles');

    Route::get('/get/otros', 'OtroController@getOtros');

    Route::post('/otros/check-stock', 'OtroController@checkStock');

    Route::post('/delete/otros', 'OtroController@deleteInventario');

    Route::post('/update/otros', 'OtroController@updateInventario');

    Route::post('/add/otros', 'OtroController@addOtros');

    Route::get('/get/status', 'Admin\StatusController@getStatus');

    Route::get('/get/icctypes', 'IccTypeController@index');

    Route::get('/get/icc-subproducts', 'Admin\IccSubProductController@index');
});

use MarvinLabs\Luhn\Facades\Luhn;

Route::get('/genera-imeis', function (Request $request) {



    $i = 1;
    while ($i <= 180) {
        $i++;

        $inicio = '86881003';

        $random = mt_rand(100000, 999999);

        $checkDigit = Luhn::computeCheckDigit($inicio . $random);

        echo ($inicio . $random . $checkDigit . '<br>');
    }
});

use App\Icc;

use Illuminate\Support\Facades\DB;

use App\Linea;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpParser\Node\Scalar\MagicConst\Line;

Route::get('/duplicados', function (Request $request) {

    $duplicados = Linea::whereIn('dn', array_column( DB::select('select dn from lineas group by dn having count(*) > 1'), 'dn'))->orderBy('dn', 'desc')->get();

    
    foreach($duplicados as $duplicado){

        $activatedAt = $duplicado->productoable->activated_at ?? '';

        $PreactivatedAt = $duplicado->productoable->preactivated_at ?? '';

        echo ($duplicado->dn.' , '.$duplicado->icc->icc.'F , '.$duplicado->icc->inventario->inventarioable->name.', '.$duplicado->product->name.', '.$duplicado->icc->company->name.', '.Date::stringToExcel($PreactivatedAt).' , '.Date::stringToExcel($activatedAt).'<br>');
    }
});

use Illuminate\Support\Facades\Http;

use Illuminate\Support\Carbon;

Route::get('/pruebas', function (Request $request) {


    //iniciar proceso 
    // $consulta = Http::contentType("application/json")->bodyFormat('json')->post('http://portabilidad.telcel.com/PortabilidadCambaceo4.4/rest/ConsumeServicios?fmt=json', [
    //     'EndPoint' => 3,
    //     "Entrada" => "{\"ApMaterno\":\"MARTINEZ\",\"ApPaterno\":\"ANDRADE\",\"AsentaId\":\"0\",\"AsentaNom\":\"\",\"CURP\":\"MAAD010829MJCRNNA5\",\"Direccion\":\"\",\"EdoId\":\"0\",\"EdoNom\":\"\",\"FzaVtaPospagoPadre\":\"OCOAAF\",\"FzaVtaPospagoPersonal\":\"OCOAA1\",\"FzaVtaPospagoReporte\":\"OCOAAF\",\"FzaVtaPrepagoPadre\":\"40820\",\"FzaVtaPrepagoPersonal\":\"40821\",\"FzaVtaPrepagoReporte\":\"40820\",\"IDRegion\":\"5\",\"Latitud\":\"20.659698300\",\"Longitud\":\"-103.349608300\",\"MnpioId\":\"0\",\"MnpioNom\":\"\",\"Nombre\":\"DIANA JACQUELINE\",\"OnLine\":\"1\",\"Plataforma\":\"2\",\"SistemaOrigen\":\"CAMBACEO\",\"Telefono\":\"3324238366\",\"TipoPlan\":\"1\",\"Usuario\":\"37746\"}",
    //     "Metodo" => "20",
    //     "Pantalla"=> "0",
    //     "Usuario" => "37746"

    // ]);

    // return $consulta;



    ////CONFIRMAR porta 
    // $consulta = Http::contentType("application/json")->bodyFormat('json')->post('http://portabilidad.telcel.com/PortabilidadCambaceo4.4/rest/ConsumeServicios?fmt=json', [
    //     'EndPoint' => 3,
    //     "Entrada" => "{\"Iccid\":\"8952020021457600472\",\"idCop\":\"18822062370712\",\"IDPromocion\":\"548\",\"IMEI\":\"546464565444344\",\"Nip\":\"9689\",\"NumeroKit\":\"\",\"SistemaOrigen\":\"CAMBACEO\"}",
    //     "Metodo" => "21",
    //     "Pantalla"=> "0",
    //     "Usuario" => "37746"

    // ]);

    // return $consulta;


    ////TERMINAR PORTA
    // $consulta = Http::contentType("application/json")->bodyFormat('json')->post('http://portabilidad.telcel.com/PortabilidadCambaceo4.4/rest/ConsumeServicios?fmt=json', [
    //     'EndPoint' => 3,
    //     "Entrada" => " {\"AsentaId\":\"0\",\"AsentaNom\":\"\",\"Direccion\":\"\",\"EdoId\":\"0\",\"EdoNom\":\"\",\"idCop\":\"18822062370712\",\"IDRegion\":\"5\",\"Latitud\":\"20.659698300\",\"Longitud\":\"-103.349608300\",\"MnpioId\":\"0\",\"MnpioNom\":\"\",\"OnLine\":\"1\",\"Plataforma\":\"2\",\"SistemaOrigen\":\"CAMBACEO\",\"Usuario\":\"37746\"}",
    //     "Metodo" => "23",
    //     "Pantalla"=> "0",
    //     "Usuario" => "37746"

    // ]);

   

    // return $consulta;

    $linea = Linea::where('dn',3921007942)->first();

    $date = Carbon::createFromFormat('d/m/Y','14/07/2022',)->format('Y-m-d');

    $date2 = $linea->comisiones->updated_at;

    echo($date.'   '.$date2);

    return $date > $date2 ? 'true' : 'false'; 
});
