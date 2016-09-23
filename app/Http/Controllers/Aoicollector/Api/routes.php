<?php
Route::group(['prefix' => 'api'], function() {
    // Pagina principal del configurador de produccion

    Route::get('/fullverify/{barcode}/{stage}', 'Aoicollector\Api\Api@fullVerifyBarcodeAndSaveStage');
    Route::get('/verify/placa/{barcode}/{stage}', 'Aoicollector\Api\Api@verifyPlaca');
});