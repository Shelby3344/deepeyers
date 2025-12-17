<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middleware global de segurança - executa em TODAS as requisições
        $middleware->prepend(\App\Http\Middleware\SecurityShield::class);
        
        $middleware->alias([
            'rate.limit.ai' => \App\Http\Middleware\RateLimitAI::class,
            'ensure.not.banned' => \App\Http\Middleware\EnsureUserNotBanned::class,
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'security.shield' => \App\Http\Middleware\SecurityShield::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
