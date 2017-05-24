<?php


Route::group(['prefix' => 'lavado'], function()
{

    /****** BUSCAR ******/
    Route::get('/placas',[
        'as'=>'lavado.placas',
        'uses'=>'ControlDeStencil\PlacasController@init'
    ]);
    Route::post('/placas/export',[
        'as'=>'lavado.placas.export',
        'uses'=>'ControlDeStencil\PlacasController@exportToExcel'
    ]);
    Route::get('/save/{linea}/{codigo}',[
        'as'=>'lavado.save',
        'uses'=>'ControlDeStencil\ABMLavado@store'
    ]);
    Route::get('/placas/get/all/{linea?}',[
        'as'=>'lavado.get.all',
        'uses'=>'ControlDeStencil\ABMLavado@getAll'
    ]);


});