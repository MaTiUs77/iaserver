<?php
Route::group(['prefix' => 'aoicollector'], function()
{
    Route::get('/', function() {
        return redirect(route('aoicollector.inspection.index'));
    });

    include('Pizarra/routes.php');
    include('Prod/routes.php');
    include('Service/routes.php');
    include('Stat/routes.php');
    include('Inspection/routes.php');
});
