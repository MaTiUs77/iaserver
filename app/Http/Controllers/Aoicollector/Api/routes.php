<?php
Route::group([
    'prefix' => 'api',
    'namespace'=>'Aoicollector\Api'], function() {

    Route::get('/verify/placa/{barcode}/{stage}', 'CollectorClient\CollectorClientApi@verifyBarcode');

    Route::group([
        'prefix' => 'planta5',
        'middleware' => 'responselog:planta5'], function() {

        Route::get('/{barcode}/{stage}', 'Planta5\Planta5Api@estadoDePlaca');
    });

    Route::group([
        'prefix' => 'aoicollector'], function() {

        Route::get('/placa/{barcode}/{verifyDeclared?}', 'CollectorClient\CollectorClientApi@findBarcode');
        Route::get('/prodinfo/{aoibarcode}', 'CollectorClient\CollectorClientApi@prodInfo');
        Route::get('/prodlist', 'CollectorClient\CollectorClientApi@prodList');
    });
});
