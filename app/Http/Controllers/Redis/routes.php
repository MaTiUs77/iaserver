<?php
Route::group(['prefix' => 'redis'], function()
{
    Route::get('/', [
        'as' => 'redis.index',
        'uses' => 'Redis\RedisView@index'
    ]);
});


