<?php
Route::group(['prefix' => 'lavado'], function() {

    Route::match(['get', 'post'], '/', [
        'as' => 'aoicollector.stocker.lavado.index',
        'uses' => 'Lavado\LavadoController@index'
    ]);

    Route::match(['get', 'post'], '/search', [
        'as' => 'aoicollector.stocker.lavado.search',
        'uses' => 'Lavado\LavadoController@search'
    ]);

    Route::get('/imprimir/{etiqueta}/{qty}', [
        'as' => 'aoicollector.stocker.lavado.imprimir.etiqueta',
        'uses' => 'Lavado\LavadoController@imprimir'
    ]);

    Route::get('/finish/{etiqueta}', [
        'as' => 'aoicollector.stocker.lavado.imprimir.etiqueta.finish',
        'uses' => 'Lavado\LavadoController@finishClean'
    ]);

    Route::match(['get', 'post'], '/etiquetar', [
        'as' => 'aoicollector.stocker.lavado.etiquetar',
        'uses' => 'Lavado\LavadoController@etiquetar'
    ])->middleware('role:stocker_lavado|admin');
});
