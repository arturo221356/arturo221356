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

Auth::routes(['register' => false]);

// Route::get("/", "baseController@index")->middleware('auth');




Route::namespace('Admin')->middleware('auth','role:admin')->prefix('admin')->name('admin.')->group(function(){
    Route::resource('/','UsersController');
    Route::resource('/users','UsersController');
    Route::resource('/sucursales','SucursalController');
});




Route::get('/home', function(){
    
    $redir = Auth::user()->roleName();
    return redirect("$redir/");
})->name('home');

Route::get('/', function(){
   
    $redir = Auth::user()->roleName();
    return redirect("$redir/");
})->name('home')->middleware('auth');







