<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::group(['prefix' => 'amr'], function()
{
    Route::get('/parciales/almacen/{smt}','MonitorPedidos\ViewPedidos@showReservaXLinea');
    Route::get('/parciales/almacen','MonitorPedidos\ViewPedidos@viewMysql');
    Route::get('/parciales', 'MonitorPedidos\ViewPedidos@getMaterialError');
    Route::post('/parciales/pedir','MonitorPedidos\CogiscanPedidos@store');
    Route::get('/pedidos/nuevos', 'MonitorPedidos\ViewPedidos@getRequestNew');
    Route::get('/pedidos/procesados','MonitorPedidos\ViewPedidos@index');
    Route::post('/entregar/{id}','MonitorPedidos\CogiscanPedidos@changeStatus');
});


//
//Route::resource('/excel','ExcelController@index');
//Route::get('/login', 'Auth\AuthController@getLogin');
//Route::post('/login', 'Auth\AuthController@postLogin');
//
//Route::get('/register', 'Auth\AuthController@getRegister');
//Route::post('/register', 'Auth\AuthController@postRegister');
//
//// Password reset link request routes...
//Route::get('password/email', 'Auth\PasswordController@getEmail');
//Route::post('password/email', 'Auth\PasswordController@postEmail');
//
//// Password reset routes...
//Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
//Route::post('password/reset', 'Auth\PasswordController@postReset');
//
//Route::get('logout', function(){
//    Auth::logout();
//    return redirect('/');
//});
