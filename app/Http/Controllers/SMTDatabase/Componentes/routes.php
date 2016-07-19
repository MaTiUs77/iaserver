<?php
Route::group(['prefix' => 'componentes'], function() {
    Route::post('/buscar', [
        'as' => 'smtdatabase.componentes.buscar',
        'uses' => 'SMTDatabase\Componentes\ComponentesView@buscar'
    ]);
});