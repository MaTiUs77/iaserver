<?php
Route::group(['prefix' => 'cogiscan'], function()
{

    Route::match(['get', 'post'],'/graficos/carga', [
        'as' => 'cogiscan.graficos.carga',
        'uses' => 'Cogiscan\CogiscanGraficos@carga'
    ]);

    Route::get('/db2/{command}', 'Cogiscan\CogiscanDB2@dynamicCommands')->where('command','.*');
    Route::get('/{command}', 'Cogiscan\Cogiscan@dynamicCommands')->where('command','.*');
});