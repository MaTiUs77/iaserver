<?php
Route::group(['prefix' => 'pizarra'], function() {
    Route::get('/', [
        'as' => 'aoicollector.pizarra.index',
        'uses' => 'Aoicollector\Pizarra\PizarraByLine@index'
    ]);

    Route::get('/linea/{linea}', [
        'as' => 'aoicollector.pizarra.linea',
        'uses' => 'Aoicollector\Pizarra\PizarraByLine@indexLinea'
    ]);

    Route::group(['prefix' => 'general'], function() {
        Route::get('/', [
            'as' => 'aoicollector.pizarra.general',
            'uses' => 'Aoicollector\Pizarra\PizarraGeneral@index'
        ]);

        Route::post('/filter/{remove?}', [
            'as' => 'aoicollector.pizarra.general.filter',
            'uses' => 'Aoicollector\Pizarra\PizarraGeneral@renderFilter'
        ]);
    });
});
