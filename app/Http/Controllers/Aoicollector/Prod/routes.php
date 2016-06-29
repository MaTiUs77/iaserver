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

    Route::group(['prefix' => 'stocker'], function()
    {
        Route::get('/set/{stkbarcode}/{aoibarcode}', [
            'as' => 'aoicollector.prod.stocker.view.set',
            'uses' => 'Aoicollector\Prod\Stocker\StockerController@view_setStockerToAoi'
        ]);

        Route::get('/info/{stkbarcode}', [
            'as' => 'aoicollector.prod.stocker.info',
            'uses' => 'Aoicollector\Prod\Stocker\StockerController@view_stockerInfo'
        ]);

        Route::get('/controldeplacas/{stkbarcode}', [
            'as' => 'aoicollector.prod.stocker.controldeplacas',
            'uses' => 'Aoicollector\Prod\Stocker\StockerController@view_stockerControldeplacas'
        ]);

        Route::get('/remove/{stkbarcode}', [
            'as' => 'aoicollector.prod.stocker.view.remove',
            'uses' => 'Aoicollector\Prod\Stocker\StockerController@view_removeStocker'
        ]);

        Route::get('/panel/add/{panelbarcode}/{aoibarcode}', [
            'as' => 'aoicollector.prod.stocker.panel.view.add',
            'uses' => 'Aoicollector\Prod\Stocker\PanelStockerController@view_addPanel'
        ]);

        Route::get('/panel/addmanual/{panelbarcode}/{aoibarcode}', [
            'as' => 'aoicollector.prod.stocker.panel.view.addmanual',
            'uses' => 'Aoicollector\Prod\Stocker\PanelStockerController@view_addPanelManual'
        ]);

        Route::get('/panel/remove/{panelbarcode}', [
            'as' => 'aoicollector.prod.stocker.panel.view.remove',
            'uses' => 'Aoicollector\Prod\Stocker\PanelStockerController@view_removePanel'
        ]);
    });
});
