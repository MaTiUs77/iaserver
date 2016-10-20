<?php
Route::group(['prefix' => 'api'], function() {
    // Pagina principal del configurador de produccion
    Route::get('/verify/placa/{barcode}/{stage}', 'Aoicollector\Api\ApiResponse@verifyPlacaResponse');

    Route::get('/fullinfo/{barcode}', 'Aoicollector\Api\ApiResponse@fullInfoResponse');
});