<?php
Route::group(['prefix' => 'aoicollector'], function()
{

    include('Pizarra/routes.php');
    include('Prod/routes.php');
    include('Service/routes.php');
    include('Stat/routes.php');
    include('Inspection/routes.php');
    include('Stocker/routes.php');
});


include('Api/routes.php');

