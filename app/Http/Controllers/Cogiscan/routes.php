<?php
Route::group(['prefix' => 'cogiscan'], function()
{
    Route::get('/{command}', 'Cogiscan\Cogiscan@dynamicCommands')->where('command','.*');
});