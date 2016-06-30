<?php
Route::group(['prefix' => 'cogiscan'], function()
{
    Route::get('/db2/{command}', 'Cogiscan\CogiscanDB2@dynamicCommands')->where('command','.*');
    Route::get('/{command}', 'Cogiscan\Cogiscan@dynamicCommands')->where('command','.*');
});