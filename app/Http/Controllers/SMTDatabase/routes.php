<?php
Route::group(['prefix' => 'smtdatabase'], function()
{

    Route::get('/', [
        'as' => 'smtdatabase.index',
        'uses' => 'SMTDatabase\SMTDatabaseView@index'
    ]);

    Route::post('/buscar', [
        'as' => 'smtdatabase.componente.buscar',
        'uses' => 'SMTDatabase\SMTDatabaseView@buscarComponente'
    ]);

    Route::group(['prefix' => 'transport'], function() {
        Route::get('/', [
            'as' => 'smtdatabase.transport.index',
            'uses' => 'SMTDatabase\SMTDatabaseView@transportIndex'
        ]);

        Route::post('/', [
            'as' => 'smtdatabase.transport.form',
            'uses' => 'SMTDatabase\SMTDatabaseView@transportForm'
        ]);

        Route::post('/submit', [
            'as' => 'smtdatabase.transport.submit',
            'uses' => 'SMTDatabase\SMTDatabaseView@transportSubmit'
        ]);
    });
});
