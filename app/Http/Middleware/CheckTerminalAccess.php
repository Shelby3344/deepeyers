<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTerminalAccess
{
    /**
     * Verifica se o usuário tem acesso ao terminal (apenas Full Attack ou admin)
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Se não tiver token, deixa o JS redirecionar para login
        $token = $request->bearerToken() ?? $request->cookie('token');
        
        if (!$token) {
            // Retorna a view que vai verificar no JS
            return $next($request);
        }

        // Tenta pegar o usuário do token
        try {
            $user = \Laravel\Sanctum\PersonalAccessToken::findToken($token)?->tokenable;
            
            if ($user) {
                // Admin sempre tem acesso
                if ($user->role === 'admin') {
                    return $next($request);
                }
                
                // Verifica o plano
                $plan = $user->plan;
                $planSlug = $plan ? $plan->slug : 'free';
                
                if ($planSlug !== 'fullattack') {
                    // Redireciona para o chat com mensagem
                    return redirect('/chat')->with('error', 'Terminal disponível apenas no plano Full Attack');
                }
            }
        } catch (\Exception $e) {
            // Se der erro, deixa o JS lidar
        }

        return $next($request);
    }
}
