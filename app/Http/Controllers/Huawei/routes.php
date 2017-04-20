<?php

Route::group(['prefix' => 'huawei'], function()
{
    Route::resource('/trazabilidad','Huawei\huaweiController@index');
});