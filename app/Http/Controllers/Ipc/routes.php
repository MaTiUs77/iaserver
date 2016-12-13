<?php
Route::group(['prefix' => 'ipc'], function()
{
    Route::get('/','Ipc\CertificacionController@index');

    Route::resource('/certificacion', 'Ipc\CertificacionController',['except' => ['show']]);
});



