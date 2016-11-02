<?php
Route::group(['prefix' => 'inventario'], function()
{
    Route::get('/actualizar','Inventario\viewController@updateLabel');
    Route::post('/imprimir','EtiquetasNpm\EtiquetasController@getUltimoId');

});