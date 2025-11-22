<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Tymon\JWTAuth\JWTGuard;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // sorting middleware
        // $middleware->priority([
        //     ''=> '',
        // ]);
        
        // $middleware->api(prepend: [
        //     JWTGuard::class,
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // manage exception handling here
    })->create();
