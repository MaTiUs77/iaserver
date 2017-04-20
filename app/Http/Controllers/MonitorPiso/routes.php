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

    /**** ALMACEN IA ****/
    Route::match(['get','post'],'/almacenia/{type?}',[
        'as'=>'monitorpiso.almacenia',
        'uses'=>'MonitorPiso\AlmacenIA@show'
    ]);

//    Route::post('/almacenia/find',[
//        'as'=>'monitorpiso.almacenia.find',
//        'uses'=>'MonitorPiso\AlmacenIA@getFiltered'
//    ]);
    /********************/

    /****** EXPORTAR EXCEL ******/
    Route::get('/excel/almacen',[
        'as'=>'monitorpiso.almacenia.excel',
        'uses'=>'MonitorPiso\ExportToExcel@almacen'
    ]);

    Route::get('/excel/cuarentena',[
        'as'=>'monitorpiso.cuarentena.excel',
        'uses'=>'MonitorPiso\ExportToExcel@cuarentena'
    ]);
    /****************************/

    /**** CUARENTENA ****/
    Route::get('/cuarentena',[
        'as'=>'monitorpiso.cuarentena',
        'uses'=>'MonitorPiso\Quarantine@show'
    ]);

    Route::post('/cuarentena/filter',[
        'as'=>'monitorpiso.cuarentena.filter',
        'uses'=>'MonitorPiso\Quarantine@filter'
    ]);
    /********************/

    /**** RECUPERO DE MATERIALES ****/
    Route::get('/recupero/getLine',[
        'as'=>'monitorpiso.recupero.getline',
        'uses'=>'MonitorPiso\RecuperoController@getLine'
    ]);

    Route::get('/recupero',[
        'as'=>'monitorpiso.recupero',
        'uses'=>'MonitorPiso\RecuperoController@show'
    ]);

    Route::get('/recupero/reporte',[
        'as'=>'monitorpiso.recupero.reporte',
        'uses'=>'MonitorPiso\RecuperoReporteController@show'
    ]);

    Route::get('/recupero/reporte/find',[
        'as'=>'monitorpiso.recupero.reporte.find',
        'uses'=>'MonitorPiso\RecuperoReporteController@find'
    ]);

    Route::get('/recupero/reporte/exportar',[
        'as'=>'monitorpiso.recupero.reporte.exportar',
        'uses'=>'MonitorPiso\RecuperoReporteController@export'
    ]);

    Route::get('/recupero/queryItem/{itemId}/{qty?}','MonitorPiso\RecuperoController@queryItem');

    Route::match(['get', 'post'],'/recupero/find',[
        'as'=>'monitorpiso.find',
        'uses'=>'MonitorPiso\RecuperoController@obtenerMaterial'
    ]);

    Route::get('/recupero/session','MonitorPiso\RecuperoController@getSession');

    Route::match(['get', 'post'],'/recupero/recuperar', [
        'as'=>'monitorpiso.recuperar',
        'uses'=>'MonitorPiso\RecuperoController@recuperarMaterial'
    ]);
    /********************************/
});
