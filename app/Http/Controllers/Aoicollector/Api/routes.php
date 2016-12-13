<?php
Route::group(['prefix' => 'api'], function() {
    Route::get('/verify/placa/{barcode}/{stage}', 'Aoicollector\Api\ApiResponse@verifyPlacaResponse');

    Route::group(['prefix' => 'p5'], function() {
        Route::get('/{barcode}/{stage}', 'Aoicollector\Api\ApiResponse@p5Response');
    });

    Route::group(['prefix' => 'aoicollector'], function() {
        Route::get('/placa/{barcode}/{verifyDeclared?}', 'Aoicollector\Api\ApiResponse@aoicollectorPlacaResponse');
        Route::get('/prodinfo/{aoibarcode}', 'Aoicollector\Api\ApiResponse@aoicollectorProdInfoResponse');
        Route::get('/prodlist', 'Aoicollector\Api\ApiResponse@prodListResponse');
    });
});
