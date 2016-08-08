<?php
Route::group(['prefix' => 'prod'], function() {
    // Pagina principal del configurador de produccion
    Route::get('/', [
        'as' => 'aoicollector.prod.index',
        'uses' => 'Aoicollector\Prod\ProdController@index'
    ]);

    Route::get('/info/{aoibarcode}/{first?}', [
        'as' => 'aoicollector.prod.info',
        'uses' => 'Aoicollector\Prod\ProdController@aoiProductionInfo'
    ]);

    Route::get('/infoop/{op}/{aoibarcode?}', [
        'as' => 'aoicollector.prod.infoop',
        'uses' => 'Aoicollector\Prod\ProdController@infoOp'
    ]);

    Route::post('/infoop/submit', [
        'as' => 'aoicollector.prod.infoop.submit',
        'uses' => 'Aoicollector\Prod\ProdController@infoOpSubmit'
    ]);

    Route::get('/removeop/{aoibarcode}', [
        'as' => 'aoicollector.prod.infoopremove.submit',
        'uses' => 'Aoicollector\Prod\ProdController@infoOpRemove'
    ]);

    Route::resource('/routeop', 'Aoicollector\Prod\RouteOpController');

    Route::group(['prefix' => 'user'], function()
    {
        Route::post('/login', [
            'as' => 'aoicollector.prod.user.login',
            'uses' => 'Aoicollector\Prod\ProdController@userLogin'
        ]);

        Route::get('/logout', [
            'as' => 'aoicollector.prod.user.logout',
            'uses' => 'Aoicollector\Prod\ProdController@userLogout'
        ]);
    });
});
