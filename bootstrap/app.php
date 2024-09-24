<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'payment/*/notification/handler',
        ]);
        $middleware->alias([
            'optimizeImages' => \App\Http\Middleware\OptimizeImages::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {})
    ->create();
