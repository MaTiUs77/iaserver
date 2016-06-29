<?php
Route::group(['prefix' => 'memorias'], function() {
    Route::get('/', [
        'as' => 'memorias.index',
        'uses' => 'Memorias\MemoriasView@index'
    ]);

    Route::match(['get', 'post'],'/reporte', [
        'as' => 'memorias.reporte',
        'uses' => 'Memorias\MemoriasView@reporte'
    ]);

    Route::match(['get', 'post'], '/search/{op?}',  [
        'as' => 'memorias.search',
        'uses' => 'Memorias\MemoriasView@search'
    ]);

    Route::get('/form/declarar/{op}', [
        'as' => 'memorias.form.declarar',
        'uses' => 'Memorias\MemoriasView@formDeclarar'
    ]);

    Route::match(['get', 'post'], '/form/declarar/{op}/{redir?}',  [
        'as' => 'memorias.form.declarar.submit',
        'uses' => 'Memorias\MemoriasView@formDeclararSubmit'
    ]);

    Route::post('/zebra/{op?}/{cantidad?}', [
        'as' => 'memorias.zebra.print',
        'uses' => 'Memorias\Memorias@zebraPrint'
    ]);
});