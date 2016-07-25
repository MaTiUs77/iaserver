<?php
Route::group(['prefix' => 'componentes'], function() {
    Route::post('/buscar', [
        'as' => 'smtdatabase.componentes.buscar',
        'uses' => 'SMTDatabase\Componentes\ComponentesView@findComp'
    ]);

    Route::post('/buscar/semielaborado', [
        'as' => 'smtdatabase.componentes.buscar.semielaborado',
        'uses' => 'SMTDatabase\Componentes\ComponentesView@findSemi'
    ]);
});