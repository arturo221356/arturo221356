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
});




Route::get('/home', function(){
    
    $redir = Auth::user()->roleName();
    return redirect("$redir/");
})->name('home');

Route::get('/', function(){
   
    $redir = Auth::user()->roleName();
    return redirect("$redir/");
})->name('home')->middleware('auth');







