<?php

namespace IAServer\Providers;

use IAServer\Exceptions\XmlExceptionHandler;
use IAServer\Http\Controllers\IAServer\Util;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class LocalServiceProvider extends ServiceProvider
{

    protected $providers = [
        'Barryvdh\Debugbar\ServiceProvider'
    ];
    protected $aliases = [
        'Debugbar' => 'Barryvdh\Debugbar\Facade'
    ];

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
        //register the service providers for local environment

        if ($this->app->isLocal() && !empty($this->providers)) {
            foreach ($this->providers as $provider) {
                $this->app->register($provider);
            }
            //register the alias
            if (!empty($this->aliases)) {
                foreach ($this->aliases as $alias => $facade) {
                    $this->app->alias($alias, $facade);
                }
            }
        }
    }
}
