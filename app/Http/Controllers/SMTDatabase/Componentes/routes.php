<?php
Route::group(['prefix' => 'componentes'], function() {
    Route::post('/buscar', [
        'as' => 'smtdatabase.componentes.buscar',
        'uses' => 'SMTDatabase\Componentes\ComponentesView@findComponente'
    ]);

    Route::post('/buscar/semielaborado', [
        'as' => 'smtdatabase.componentes.buscar.semielaborado',
        'uses' => 'SMTDatabase\Componentes\ComponentesView@findSemielaborado'
    ]);

    Route::post('/buscar/modelo', [
        'as' => 'smtdatabase.componentes.buscar.semielaborado.modelo',
        'uses' => 'SMTDatabase\Componentes\ComponentesView@allSemielaboradoByModelo'
    ]);
});