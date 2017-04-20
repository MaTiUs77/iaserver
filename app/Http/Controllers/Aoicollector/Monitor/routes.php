<?php
Route::group(['prefix' => 'monitor'], function() {
    Route::get('/', 'Aoicollector\Monitor\AoicollectorMonitor@index');
});
