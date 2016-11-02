<?php
Route::group(['prefix' => 'api'], function() {
    Route::get('/verify/placa/{barcode}/{stage}', 'Aoicollector\Api\ApiResponse@verifyPlacaResponse');
    Route::get('/fullinfo/{barcode}', 'Aoicollector\Api\ApiResponse@fullInfoResponse');

    Route::get('/collectorinfo/{aoibarcode}', 'Aoicollector\Api\ApiResponse@collectorInfoResponse');
});