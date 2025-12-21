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
        // ✅ SECURITY LAYER 1: Block sensitive paths FIRST
        $middleware->prepend(\App\Http\Middleware\BlockSensitivePaths::class);
        
        // ✅ SECURITY LAYER 2: Security headers on all responses
        $middleware->prepend(\App\Http\Middleware\SecurityHeaders::class);
        
        // ✅ SECURITY LAYER 3: Rate limiting
        $middleware->prepend(\App\Http\Middleware\RateLimitRequests::class);
        
        // ✅ Middleware global de segurança - executa em TODAS as requisições
        $middleware->prepend(\App\Http\Middleware\SecurityShield::class);
        
        // ✅ Middleware para setar usuário Sanctum na request (API stateless com Bearer token)
        $middleware->api(append: [
            \App\Http\Middleware\SetSanctumUser::class,
        ]);
        
        // ✅ Aliases para middlewares de segurança
        $middleware->alias([
            'rate.limit.ai' => \App\Http\Middleware\RateLimitAI::class,
            'ensure.not.banned' => \App\Http\Middleware\EnsureUserNotBanned::class,
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'security.shield' => \App\Http\Middleware\SecurityShield::class,
            'detect.anomalies' => \App\Http\Middleware\DetectAnomalies::class,
            'validate.signature' => \App\Http\Middleware\ValidateRequestSignature::class,
            'security.headers' => \App\Http\Middleware\SecurityHeaders::class,
            'block.sensitive' => \App\Http\Middleware\BlockSensitivePaths::class,
            'rate.limit' => \App\Http\Middleware\RateLimitRequests::class,
            'auth.web' => \App\Http\Middleware\EnsureAuthenticated::class,
        ]);
        
        // ✅ Middleware de detecção de anomalias para rotas autenticadas
        $middleware->appendToGroup('auth:sanctum', [
            \App\Http\Middleware\DetectAnomalies::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
