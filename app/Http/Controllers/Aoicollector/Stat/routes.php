<?php
Route::group(['prefix' => 'stat'], function() {
    // Pagina principal de estadisticas
    Route::get('/', [
        'as' => 'aoicollector.stat.index',
        'uses' => 'Aoicollector\Stat\StatView@index'
    ]);
    Route::get('/resume', [
        'as' => 'aoicollector.stat.resume',
        'uses' => 'Aoicollector\Stat\StatView@resume'
    ]);
    Route::get('/show/{id_maquina}/{turno}/{fecha}/{resume_type?}/{programa?}/{op?}', [
        'as' => 'aoicollector.stat.show',
        'uses' => 'Aoicollector\Stat\StatView@indexWithFilter'
    ]);
    Route::get('/export/{linea}/{turno}/{fecha}/{resume_type}/{programa?}', [
        'as' => 'aoicollector.stat.export',
        'uses' => 'Aoicollector\Stat\StatExport@toExcel'
    ]);
});