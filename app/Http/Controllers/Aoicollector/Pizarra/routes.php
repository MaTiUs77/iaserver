<?php
Route::group(['prefix' => 'pizarra'], function() {
    // Pagina principal del configurador de produccion
    Route::get('/', [
        'as' => 'aoicollector.pizarra.index',
        'uses' => 'Aoicollector\Pizarra\PizarraView@index'
    ]);

    Route::get('/general', [
        'as' => 'aoicollector.pizarra.general',
        'uses' => 'Aoicollector\Pizarra\PizarraView@indexGeneral'
    ]);

    Route::get('/linea/{linea}', [
        'as' => 'aoicollector.pizarra.linea',
        'uses' => 'Aoicollector\Pizarra\PizarraView@indexLinea'
    ]);
});
