<?php
Route::group(['prefix' => 'smtdatabase'], function()
{
    Route::get('/', [
        'as' => 'smtdatabase.index',
        'uses' => 'SMTDatabase\SMTDatabaseView@index'
    ]);

    require('Componentes\routes.php');
    require('Transport\routes.php');
    require('AbmOrdenTrabajo\routes.php');
});
