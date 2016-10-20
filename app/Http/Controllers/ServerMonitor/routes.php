<?php
Route::group(['prefix' => 'servermonitor'], function()
{
    Route::get('/','ServerMonitor\ServerMonitor@index');
    Route::get('/lista','ServerMonitor\ServerMonitor@lista');
});
