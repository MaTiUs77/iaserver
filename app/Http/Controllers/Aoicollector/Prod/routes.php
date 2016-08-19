<?php
Route::group(['prefix' => 'prod'], function() {
    // Pagina principal del configurador de produccion
    Route::get('/', [
        'as' => 'aoicollector.prod.index',
        'uses' => 'Aoicollector\Prod\View\ProdView@index'
    ]);

    Route::get('/info/{aoibarcode}/{first?}', [
        'as' => 'aoicollector.prod.info',
        'uses' => 'Aoicollector\Prod\ProdController@aoiProductionInfo'
    ]);

    Route::group(['prefix' => 'infoop'], function()
    {
        Route::post('/submit', [
            'as' => 'aoicollector.prod.infoop.submit',
            'uses' => 'Aoicollector\Prod\ProdController@opInfoSubmit'
        ]);

        Route::get('/{op}/{aoibarcode?}', [
            'as' => 'aoicollector.prod.infoop',
            'uses' => 'Aoicollector\Prod\ProdController@opInfo'
        ]);
    });

    Route::get('/removeop/{aoibarcode}', [
        'as' => 'aoicollector.prod.infoopremove.submit',
        'uses' => 'Aoicollector\Prod\ProdController@opRemove'
    ]);

    Route::resource('/routeop', 'Aoicollector\Prod\RouteOp\RouteOpAbm');

    Route::group(['prefix' => 'user'], function()
    {
        Route::match(['get', 'post'], '/login', [
            'as' => 'aoicollector.prod.user.login',
            'uses' => 'Aoicollector\Prod\View\ProdView@userLogin'
        ]);

        Route::get('/logout', [
            'as' => 'aoicollector.prod.user.logout',
            'uses' => 'Aoicollector\Prod\View\ProdView@userLogout'
        ]);
    });
});
