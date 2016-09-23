<?php
Route::group(['prefix' => 'service'], function() {
    // Pagina principal del configurador de produccion

    Route::group(['prefix' => 'proccess'], function()
    {
        Route::get('/', [
            'as' => 'aoicollector.service.process',
            'uses' => 'Aoicollector\Service\ServiceMultiProccess@view_process'
        ]);

        Route::post('/', [
            'as' => 'aoicollector.service.process.post',
            'uses' => 'Aoicollector\Service\ServiceMultiProccess@view_process_post'
        ]);
    });

    Route::get('/last/{barcode}', [
        'as' => 'aoicollector.service',
        'uses' => 'Aoicollector\Service\ServiceView@view_barcodeStatusLast'
    ]);

    Route::get('/production/{aoibarcode}', [
        'as' => 'aoicollector.service.view.production',
        'uses' => 'Aoicollector\Service\ServiceView@view_produccion'
    ]);

    Route::get('/{barcode}/declare', [
        'as' => 'aoicollector.service.declare',
        'uses' => 'Aoicollector\Service\ServiceView@view_declarar'
    ]);

    Route::get('/{barcode}/wip', [
        'as' => 'aoicollector.service.wip',
        'uses' => 'Aoicollector\Service\ServiceView@view_barcodeStatusWithWip'
    ]);

    Route::get('/{barcode}/backup', [
        'as' => 'aoicollector.service.backup',
        'uses' => 'Aoicollector\Service\ServiceView@view_barcodeInBackup'
    ]);

    Route::get('/{barcode}', [
        'as' => 'aoicollector.service',
        'uses' => 'Aoicollector\Service\ServiceView@view_barcodeStatus'
    ]);
});