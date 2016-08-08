<?php
Route::group(array('prefix' => 'stocker'), function() {

    Route::get('/info/{stkbarcode}', [
        'as' => 'aoicollector.stocker.view.info',
        'uses' => 'Aoicollector\Stocker\View\StockerView@view_stockerInfo'
    ]);

    Route::get('/controldeplacas/{stkbarcode}', [
        'as' => 'aoicollector.stocker.view.controldeplacas',
        'uses' => 'Aoicollector\Stocker\View\StockerView@view_stockerControldeplacas'
    ]);

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
    });

    Route::group(array('prefix' => 'trazabilidad'), function() {

        Route::match(['get', 'post'], '/find/{element?}', [
            'as' => 'aoicollector.stocker.trazabilidad.view.find',
            'uses' => 'Aoicollector\Stocker\View\TrazaStockerView@view_findElement'
        ]);
    });
});
