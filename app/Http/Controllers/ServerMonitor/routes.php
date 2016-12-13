<?php
Route::group(['prefix' => 'servermonitor'], function()
{
    Route::get('/','ServerMonitor\ServerMonitor@index');
    Route::get('/redis','ServerMonitor\ServerMonitor@redis');
});
