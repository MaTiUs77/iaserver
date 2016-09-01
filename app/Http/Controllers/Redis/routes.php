<?php
Route::group(['prefix' => 'redistest'], function()
{
    Route::get('/', [
        'as' => 'redis.index',
        'uses' => 'Redis\RedisView@index'
    ]);
});


