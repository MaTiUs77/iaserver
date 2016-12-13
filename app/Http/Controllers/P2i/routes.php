<?php
Route::group(['prefix' => 'p2i'], function() {

    Route::get('/', 'P2i\CargaController@index');
    Route::resource('/carga', 'P2i\CargaController',['except' => ['show']]);
    Route::resource('/limpieza', 'P2i\LimpiezaController',['except' => ['show']]);
    Route::resource('/secador', 'P2i\SecadorController',['except' => ['show']]);

    Route::get('/carga/last_monomero/{camara}', [
        'as' => 'p2i.carga.last_monomero',
        'uses' => 'P2i\CargaController@lastMonomero'
    ]);

    Route::get('/carga/terminar/{id_carga}', [
        'as' => 'p2i.carga.terminar',
        'uses' => 'P2i\CargaController@terminarProceso'
    ]);

    Route::get('/carga/stat', [
        'as' => 'p2i.carga.stat',
        'uses' => 'P2i\CargaController@monomeroStat'
    ]);

    Route::get('/secador/terminar/{id_carga}', [
        'as' => 'p2i.secador.terminar',
        'uses' => 'P2i\SecadorController@terminarProceso'
    ]);
});



