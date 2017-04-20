<?php
Route::group(['prefix' => 'molinete'], function()
{
    Route::get('/','Molinete\Molinete@index');
    Route::get('/add','Molinete\Molinete@add');
    Route::get('/check','Molinete\Molinete@check');
});
