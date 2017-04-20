<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['prefix' => 'scrap'], function()
{

    /****** BUSCAR ******/
    Route::get('/find',[
        'as'=>'scrap.find',
        'uses'=>'Scrap\ScrapController@find'
    ]);

    Route::get('/',[
        'as'=>'scrap.index',
        'uses'=>'Scrap\ScrapController@index'
    ]);

    Route::post('/export',[
        'as'=>'scrap.export',
        'uses'=>'Scrap\ExportToExcel@index'
    ]);

    Route::post('/update',[
        'as'=>'scrap.update',
        'uses'=>'Scrap\ScrapController@update'
    ]);
});
