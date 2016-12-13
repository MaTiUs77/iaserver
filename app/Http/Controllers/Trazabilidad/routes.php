<?php
Route::group(array('prefix' => 'trazabilidad'), function() {
    Route::get('/', [
        'as' => 'trazabilidad.index',
        'uses' => 'Trazabilidad\Trazabilidad@index'
    ]);

    Route::get('/show/{op}/{modo}/{trans_ok}/{manual}/{ebs_error_trans?}', [
        'as' => 'trazabilidad.form.trans_ok',
        'uses' => 'Trazabilidad\Trazabilidad@formTransOk'
    ]);

    Route::match(['get', 'post'], '/find/{op?}', [
        'as' => 'trazabilidad.find.op',
        'uses' => 'Trazabilidad\Trazabilidad@findOp'
    ]);

    Route::match(['get', 'post'], '/transport', [
        'as' => 'trazabilidad.transport.op',
        'uses' => 'Trazabilidad\Trazabilidad@transportOp'
    ]);

    Route::group(array('prefix' => 'form'), function() {
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

        Route::get('/allprodstocker/{op}', [
            'as' => 'trazabilidad.form.allprodstocker',
            'uses' => 'Trazabilidad\Trazabilidad@formAllProdStocker'
        ]);
    });


    // END FORM
});
