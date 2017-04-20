<?php

namespace IAServer\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \IAServer\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
//        \IAServer\Http\Middleware\VerifyCsrfToken::class,
        \IAServer\Http\Middleware\Cors::class
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \IAServer\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \IAServer\Http\Middleware\RedirectIfAuthenticated::class,
        'role' => \IAServer\Http\Middleware\RoleMiddleware::class,
        'responselog' => \IAServer\Http\Middleware\ResponseLog::class
//        'cors' => \IAServer\Http\Middleware\Cors::class,
    ];
}
