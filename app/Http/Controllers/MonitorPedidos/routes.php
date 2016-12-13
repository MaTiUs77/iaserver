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
    Route::get('/consultar','MonitorPedidos\ViewPedidos@verHistorialPartNumber');
    Route::get('/traza_pedido/{insert_id}','MonitorPedidos\ViewPedidos@traza_pedido');
    Route::get('/trazabilidad/{id}/{item_code}','MonitorPedidos\ViewPedidos@traza_complete');
    Route::get('/parciales/almacen/{smt}','MonitorPedidos\ViewPedidos@showReservaXLinea');
    Route::get('/parciales/almacen','MonitorPedidos\ViewPedidos@viewMysql');
    Route::get('/parciales', 'MonitorPedidos\ViewPedidos@getMaterialError');
    Route::post('/parciales/pedir','MonitorPedidos\CogiscanPedidos@store');
    Route::get('/pedidos/nuevos', 'MonitorPedidos\ViewPedidos@getRequestNew');
    Route::get('/pedidos/procesados','MonitorPedidos\ViewPedidos@index');
    Route::get('/pedidos/procesados/{smt}','MonitorPedidos\ViewPedidos@requestXLinea');
    Route::post('/entregar/{id}','MonitorPedidos\CogiscanPedidos@changeStatus');
    Route::post('/cancelar/{id}','MonitorPedidos\CogiscanPedidos@cancelRequest');
    Route::get('/pedidos/transito','MonitorPedidos\ViewPedidos@reservaTransito');
    Route::get('/pedidos/transito/{smt}','MonitorPedidos\ViewPedidos@reservaTransitoXlinea');
    Route::get('/gestionop','MonitorPedidos\ViewGestionOp@getMaterialOp');
});
