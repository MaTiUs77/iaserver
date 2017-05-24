<?php
Route::group(['prefix' => 'scrap'], function() {
    Route::get('/add/{barcode}', 'Aoicollector\Scrap\ScrapController@add');
});
