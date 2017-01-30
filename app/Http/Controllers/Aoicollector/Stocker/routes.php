<?php
Route::group(array('prefix' => 'stocker'), function() {

    Route::get('/info/{stkbarcode}', [
        'as' => 'aoicollector.stocker.view.info',
        'uses' => 'Aoicollector\Stocker\View\StockerView@view_stockerInfo'
    ]);

    Route::get('/declared/{stkbarcode}', [
        'as' => 'aoicollector.stocker.view.info',
        'uses' => 'Aoicollector\Stocker\View\StockerView@view_stockerInfoDeclared'
    ]);

    Route::get('/pocket/{stkbarcode?}', [
        'as' => 'aoicollector.stocker.view.pocket',
        'uses' => 'Aoicollector\Stocker\View\StockerView@view_findStockerPocketPc'
    ]);

    Route::group(array('prefix' => 'abm'), function() {

        Route::get('/', [
            'as' => 'aoicollector.stocker.abm.index',
            'uses' => 'Aoicollector\Stocker\Controller\AbmStocker@index'
        ]);
    });

    Route::group(array('prefix' => 'lavado'), function()
    {
        Route::match(['get', 'post'], '/', [
            'as' => 'aoicollector.stocker.lavado.index',
            'uses' => 'Aoicollector\Stocker\Controller\LavadoController@index'
        ]);

        Route::get('/imprimir/{etiqueta}/{qty}', [
            'as' => 'aoicollector.stocker.lavado.imprimir.etiqueta',
            'uses' => 'Aoicollector\Stocker\Controller\LavadoController@imprimir'
        ]);

        Route::get('/finish/{etiqueta}', [
            'as' => 'aoicollector.stocker.lavado.imprimir.etiqueta.finish',
            'uses' => 'Aoicollector\Stocker\Controller\LavadoController@finishClean'
        ]);

        Route::match(['get', 'post'], '/etiquetar', [
            'as' => 'aoicollector.stocker.lavado.etiquetar',
            'uses' => 'Aoicollector\Stocker\Controller\LavadoController@etiquetar'
        ]);
    });

    Route::group(array('prefix' => 'route'), function() {

        Route::get('/controldeplacas/{stkbarcode}', [
            'as' => 'aoicollector.stocker.view.controldeplacas',
            'uses' => 'Aoicollector\Stocker\View\StockerView@view_stockerControldeplacas'
        ]);
    });

    Route::group(array('prefix' => 'prod'), function() {

        Route::get('/set/{stkbarcode}/{aoibarcode}', [
            'as' => 'aoicollector.stocker.prod.view.set',
            'uses' => 'Aoicollector\Stocker\View\StockerView@view_setStockerToAoi'
        ]);

        Route::get('/remove/{stkbarcode}', [
            'as' => 'aoicollector.stocker.prod.view.remove',
            'uses' => 'Aoicollector\Stocker\View\StockerView@view_removeStocker'
        ]);
    });

    Route::group(array('prefix' => 'panel'), function() {

        Route::get('/add/{panelbarcode}/{aoibarcode}', [
            'as' => 'aoicollector.stocker.panel.view.add',
            'uses' => 'Aoicollector\Stocker\View\PanelStockerView@view_addPanel'
        ]);

        Route::get('/addmanual/{panelbarcode}/{aoibarcode}', [
            'as' => 'aoicollector.stocker.panel.view.addmanual',
            'uses' => 'Aoicollector\Stocker\View\PanelStockerView@view_addPanelManual'
        ]);

        Route::get('/remove/{panelbarcode}', [
            'as' => 'aoicollector.stocker.panel.view.remove',
            'uses' => 'Aoicollector\Stocker\View\PanelStockerView@view_removePanel'
        ]);

        Route::get('/declare/{panelbarcode}', [
            'as' => 'aoicollector.stocker.panel.view.declare',
            'uses' => 'Aoicollector\Stocker\View\PanelStockerView@view_declarePanel'
        ]);
    });

    Route::group(array('prefix' => 'trazabilidad'), function() {

        Route::match(['get', 'post'], '/rastrear/{op?}', [
            'as' => 'aoicollector.stocker.trazabilidad.rastrearop.view',
            'uses' => 'Aoicollector\Stocker\View\TrazaStockerView@view_rastrearOpView'
        ]);

        Route::match(['get', 'post'], '/{element?}', [
            'as' => 'aoicollector.stocker.trazabilidad.view',
            'uses' => 'Aoicollector\Stocker\View\TrazaStockerView@view_findElement'
        ]);
    });
});
