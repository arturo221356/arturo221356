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

Auth::routes();

Route::get("/", "baseController@index");

//Route::resource("/admin/users", "Admin\UsersController");





Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function(){
    Route::resource('/','UsersController');
    Route::resource('/users','UsersController');
    Route::resource('/sucursales','SucursalController');
});




Route::get('/home', 'HomeController@index')->name('home');



Route::get('/home', 'HomeController@index')->name('home');
