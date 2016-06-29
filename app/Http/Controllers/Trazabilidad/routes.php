<?php
Route::group(array('prefix' => 'trazabilidad'), function() {
    Route::get('/', [
        'as' => 'trazabilidad.index',
        'uses' => 'Trazabilidad\Trazabilidad@index'
    ]);

    Route::get('/show/{op}/{modo}/{trans_ok}', [
        'as' => 'trazabilidad.form.trans_ok',
        'uses' => 'Trazabilidad\Trazabilidad@formTransOk'
    ]);

    Route::match(['get', 'post'], '/find/{op?}', [
        'as' => 'trazabilidad.find.op',
        'uses' => 'Trazabilidad\Trazabilidad@findOp'
    ]);

    // FORM DECLARAR
    Route::get('/declarar/{op}',[
        'as' => 'trazabilidad.form.declarar',
        'uses' => 'Trazabilidad\Trazabilidad@formDeclarar'
    ]);

    Route::post('/declarar/{op}', [
        'middleware' => 'role:admin',
        'as' => 'trazabilidad.form.declarar.send',
        'uses' => 'Trazabilidad\Trazabilidad@formDeclararSend'
    ]);
    // END FORM
});
