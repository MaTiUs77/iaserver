<?php
Route::group(['prefix' => 'inspection'], function()
{
    // Pagina principal de inspecciones
    Route::get('/',  [
        'as' => 'aoicollector.inspection.index',
        'uses' => 'Aoicollector\Inspection\InspectionController@listDefault'
    ]);

    // Exportar inspecciones
    Route::get('/export/{id_maquina}/{fecha}/{minormax}',  [
        'as' => 'aoicollector.inspection.export',
        'uses' => 'Aoicollector\Inspection\InspectionController@exportarLista'
    ]);

    // Lista de inspecciones filtradas por maquina
    Route::get('/show/{id_maquina}/{pagina?}',  [
        'as' => 'aoicollector.inspection.show',
        'uses' => 'Aoicollector\Inspection\InspectionController@listWithFilter'
    ]);

    // Lista de inspecciones filtradas por op
    Route::get('/showop/{op}/{pagina?}',  [
        'as' => 'aoicollector.inspection.showop',
        'uses' => 'Aoicollector\Inspection\InspectionController@listWithOpFilter'
    ]);

    Route::group(['prefix' => 'search'], function()
    {
        Route::get('/reference/{reference}/{id_maquina}/{turno}/{fecha}/{programa}', [
            'as' => 'aoicollector.inspection.search.reference',
            'uses' => 'Aoicollector\Inspection\InspectionController@searchReference'
        ]);

        Route::post('/', [
            'as' => 'aoicollector.inspection.search',
            'uses' => 'Aoicollector\Inspection\InspectionController@searchBarcode'
        ]);

        Route::get('/{barcode}', [
            'as' => 'aoicollector.inspection.search.get',
            'uses' => 'Aoicollector\Inspection\InspectionController@searchBarcode'
        ]);
    });

    // Lista bloques de un panel
    Route::get('/blocks/{id_panel}',  [
        'as' => 'aoicollector.inspection.blocks',
        'uses' => 'Aoicollector\Inspection\InspectionController@listBlocks'
    ]);

    // Lista detalles de un bloque
    Route::get('/detail/{id_block}',  [
        'as' => 'aoicollector.inspection.detail',
        'uses' => 'Aoicollector\Inspection\InspectionController@listDetail'
    ]);
});