<?php
Route::group(['prefix' => 'transport'], function() {
    Route::get('/', [
        'as' => 'smtdatabase.transport.index',
        'uses' => 'SMTDatabase\Transport\TransportView@index'
    ]);

    Route::post('/', [
        'as' => 'smtdatabase.transport.form',
        'uses' => 'SMTDatabase\Transport\TransportView@form'
    ]);

    Route::post('/submit', [
        'as' => 'smtdatabase.transport.submit',
        'uses' => 'SMTDatabase\Transport\TransportView@submit'
    ]);
});
