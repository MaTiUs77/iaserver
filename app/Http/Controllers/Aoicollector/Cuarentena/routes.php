<?php

Route::resource('/cuarentena', 'Aoicollector\Cuarentena\CuarentenaAbm');

Route::group([
    'prefix' => 'cuarentena',
    'namespace'=>'Aoicollector\Cuarentena'], function() {

    Route::post('/librear/multiple',  [
        'as' => 'aoicollector.cuarentena.liberar.multiple',
        'uses' => 'LiberarCuarentena@multiple'
    ]);

    Route::post('/agregar/multiple',  [
        'as' => 'aoicollector.cuarentena.agregar.multiple',
        'uses' => 'AgregarCuarentena@multiple'
    ]);

});
