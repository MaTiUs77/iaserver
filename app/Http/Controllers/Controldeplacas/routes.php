<?php
Route::group(['prefix' => 'controldeplacas'], function() {
    Route::get('/', [
        'as' => 'controldepladcas.index',
        'uses' => 'Controldeplacas\Controldeplacas@index'
    ]);

    Route::post('/filtrar', [
        'as' => 'controldeplacas.filtrar.submit',
        'uses' => 'Controldeplacas\Controldeplacas@filtrar'
    ]);

    Route::get('/filtrar/form', [
        'as' => 'controldeplacas.filtrar.form',
        'uses' => 'Controldeplacas\Controldeplacas@viewFiltrarForm'
    ]);
});
