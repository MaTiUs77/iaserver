<?php
Route::group([
    'prefix' => 'stocker',
    'namespace' => 'Aoicollector\Stocker'], function() {
    Route::get('/info/{stkbarcode}', [
        'as' => 'aoicollector.stocker.view.info',
        'uses' => 'View\StockerView@view_stockerInfo'
    ]);

    Route::get('/declared/{stkbarcode}', [
        'as' => 'aoicollector.stocker.view.info',
        'uses' => 'View\StockerView@view_stockerInfoDeclared'
    ]);

    Route::group(['prefix' => 'lavado'], function(){
        Route::match(['get', 'post'], '/', [
            'as' => 'aoicollector.stocker.lavado.index',
            'uses' => 'Lavado\LavadoController@index'
        ]);

        Route::get('/imprimir/{etiqueta}/{qty}', [
            'as' => 'aoicollector.stocker.lavado.imprimir.etiqueta',
            'uses' => 'Lavado\LavadoController@imprimir'
        ]);

        Route::get('/finish/{etiqueta}', [
            'as' => 'aoicollector.stocker.lavado.imprimir.etiqueta.finish',
            'uses' => 'Lavado\LavadoController@finishClean'
        ]);

        Route::match(['get', 'post'], '/etiquetar', [
            'as' => 'stocker.lavado.etiquetar',
            'uses' => 'Lavado\LavadoController@etiquetar'
        ]);
    });

    Route::group(['prefix' => 'route'], function() {

        Route::get('/controldeplacas/{stkbarcode}', [
            'as' => 'aoicollector.stocker.view.controldeplacas',
            'uses' => 'View\StockerView@view_stockerControldeplacas'
        ]);
    });

    Route::group(['prefix' => 'prod'], function() {
        Route::get('/set/{stkbarcode}/{aoibarcode}', [
            'as' => 'aoicollector.stocker.prod.view.set',
            'uses' => 'View\StockerView@view_setStockerToAoi'
        ]);

        Route::get('/remove/{stkbarcode}', [
            'as' => 'aoicollector.stocker.prod.view.remove',
            'uses' => 'View\StockerView@view_removeStocker'
        ]);
    });

    Route::group(['prefix' => 'panel'], function() {
            Route::get('/add/{panelbarcode}/{aoibarcode}', [
                'as' => 'aoicollector.stocker.panel.view.add',
                'uses' => 'PanelStockerController@addPanel'
            ]);

            Route::get('/remove/{panelbarcode}', [
                'as' => 'aoicollector.stocker.panel.view.remove',
                'uses' => 'PanelStockerController@removePanel'
            ]);

            Route::get('/declare/{panelbarcode}', [
                'as' => 'aoicollector.stocker.panel.view.declare',
                'uses' => 'PanelStockerController@declarePanel'
            ]);

            Route::get('/declare/force/{panelbarcode}', [
                'as' => 'aoicollector.stocker.panel.view.declare.force',
                'uses' => 'PanelStockerController@forceTransaccionWip'
            ]);
        }
    );

    Route::group(['prefix' => 'trazabilidad'], function() {
        Route::match(['get', 'post'], '/rastrear/{op?}', [
            'as' => 'aoicollector.stocker.trazabilidad.rastrearop.view',
            'uses' => 'View\TrazaStockerView@view_rastrearOpView'
        ]);

        Route::match(['get', 'post'], '/changeop', [
            'as' => 'aoicollector.stocker.trazabilidad.changeop',
            'uses' => 'View\TrazaStockerView@view_changeOp'
        ]);

        Route::match(['get', 'post'], '/{element?}', [
            'as' => 'aoicollector.stocker.trazabilidad.view',
            'uses' => 'View\TrazaStockerView@view_findElement'
        ]);
    });
});
