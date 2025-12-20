<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware que garante que o usuário autenticado via Sanctum
 * esteja disponível via $request->user()
 */
class SetSanctumUser
{
    public function handle(Request $request, Closure $next): Response
    {
        // Se o guard sanctum tem um usuário, seta ele na request
        if ($user = auth('sanctum')->user()) {
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
        }
        
        return $next($request);
    }
}
