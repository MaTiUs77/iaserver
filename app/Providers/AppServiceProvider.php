<?php

namespace IAServer\Providers;

use IAServer\Exceptions\XmlExceptionHandler;
use IAServer\Http\Controllers\IAServer\Util;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        require_once app_path('Http\Controllers\IAServer\Provider\IAHelpers.php');
    }
}
