<?php
Route::group([
    'prefix' => 'stat',
    'namespace' => 'Aoicollector\Stat'
    ], function() {

    Route::get('/', [
        'as' => 'aoicollector.stat.index',
        'uses' => 'StatView@index'
    ]);
    Route::get('/resume', [
        'as' => 'aoicollector.stat.resume',
        'uses' => 'StatView@resume'
    ]);
    Route::get('/show/{id_maquina}/{turno}/{fecha}/{resume_type?}/{programa?}/{op?}', [
        'as' => 'aoicollector.stat.show',
        'uses' => 'StatView@indexWithFilter'
    ]);
    Route::get('/export/{linea}/{turno}/{fecha}/{resume_type}/{programa?}', [
        'as' => 'aoicollector.stat.export',
        'uses' => 'StatExport@toExcel'
    ]);
});