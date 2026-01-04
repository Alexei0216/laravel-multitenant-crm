<?php

use App\Http\Middleware\CookieTokenAuth;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\IdentifyTenant;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\Middleware\StartSession;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'tenant' => IdentifyTenant::class,
        ]);

        $middleware->alias([
            'cookie.auth' => CookieTokenAuth::class,
        ]);

        $middleware->web(append: [
            HandleInertiaRequests::class,
            StartSession::class,
        ]);
    })
    ->withExceptions(function ($exceptions): void {
        //
    })
    ->create();
