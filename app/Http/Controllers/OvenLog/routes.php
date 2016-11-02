<?php

Route::group(['prefix'=>'ovenlog'],function()
{
    /** Soldadoras **/
    Route::get('/soldadoras/perfiles',[
        'as'=>'soldadoras.perfiles',
        'uses'=>'OvenLog\Soldadoras@perfiles'
    ]);

    Route::get('/soldadoras/muestras',[
        'as'=>'soldadoras.muestras',
        'uses'=>'OvenLog\Soldadoras@muestras'
    ]);

});