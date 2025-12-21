<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware que verifica se o usuário está autenticado.
 * Para rotas web, permite acesso mas injeta script de verificação no frontend.
 * Para rotas API, verifica token Sanctum.
 */
class EnsureAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        // Se for uma requisição AJAX/API, verifica token Sanctum
        if ($request->expectsJson() || $request->is('api/*')) {
            if (!auth('sanctum')->check()) {
                return response()->json(['message' => 'Não autenticado'], 401);
            }
        }
        
        // Para requisições web, permite acesso mas marca como rota protegida
        // A verificação real será feita no frontend via JavaScript
        $request->attributes->set('requires_auth', true);
        
        return $next($request);
    }
}
