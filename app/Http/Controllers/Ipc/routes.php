<?php
Route::group(['prefix' => 'ipc'], function()
{
    Route::get('/', function() {
        return redirect('/ipc/certificacion');
    });

    Route::resource('/certificacion', 'Ipc\CertificacionController',['except' => ['show']]);
});



