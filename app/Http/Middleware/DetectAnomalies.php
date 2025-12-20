<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para detecção de anomalias em sessões
 * 
 * Detecta:
 * - Session hijacking (fingerprint mismatch)
 * - Mudanças drásticas de IP/UserAgent
 * - Comportamento suspeito
 */
class DetectAnomalies
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return $next($request);
        }

        $fingerprint = $this->generateFingerprint($request);
        $cacheKey = "user_fingerprint:{$user->id}";
        $storedFingerprint = Cache::get($cacheKey);

        if ($storedFingerprint && $storedFingerprint !== $fingerprint) {
            // Fingerprint mudou - possível session hijacking
            $this->logAnomaly($user, $request, 'fingerprint_mismatch', [
                'stored' => substr($storedFingerprint, 0, 16) . '...',
                'current' => substr($fingerprint, 0, 16) . '...',
            ]);
            
            // Conta mudanças de fingerprint
            $changeCountKey = "fingerprint_changes:{$user->id}";
            $changeCount = (int) Cache::get($changeCountKey, 0) + 1;
            Cache::put($changeCountKey, $changeCount, now()->addHours(1));
            
            // Se mudou mais de 3 vezes em 1 hora, força re-auth
            if ($changeCount > 3) {
                $user->tokens()->delete();
                Cache::forget($changeCountKey);
                
                return response()->json([
                    'error' => 'Session expired',
                    'code' => 'REAUTH_REQUIRED',
                ], 401);
            }
        }

        // Armazena/atualiza fingerprint
        Cache::put($cacheKey, $fingerprint, now()->addHours(24));

        // Detecta requests em velocidade suspeita
        $this->detectRapidRequests($user, $request);

        return $next($request);
    }

    /**
     * Gera fingerprint único baseado em características do cliente
     */
    private function generateFingerprint(Request $request): string
    {
        return hash('sha256', implode('|', [
            $request->ip(),
            $request->userAgent() ?? 'unknown',
            $request->header('Accept-Language', 'unknown'),
            $request->header('Accept-Encoding', 'unknown'),
        ]));
    }

    /**
     * Detecta requests muito rápidos (bot behavior)
     */
    private function detectRapidRequests($user, Request $request): void
    {
        $key = "request_times:{$user->id}";
        $times = Cache::get($key, []);
        $now = microtime(true);
        
        // Mantém apenas os últimos 60 segundos
        $times = array_filter($times, fn($t) => ($now - $t) < 60);
        $times[] = $now;
        
        Cache::put($key, $times, now()->addMinutes(2));
        
        // Mais de 100 requests por minuto é suspeito
        if (count($times) > 100) {
            $this->logAnomaly($user, $request, 'rapid_requests', [
                'count' => count($times),
                'period' => '60s',
            ]);
        }
    }

    /**
     * Loga atividade anômala para análise
     */
    private function logAnomaly($user, Request $request, string $type, array $extra = []): void
    {
        Log::channel('security')->warning("Anomaly detected: {$type}", array_merge([
            'user_id' => $user->id,
            'user_email' => $user->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'path' => $request->path(),
            'method' => $request->method(),
            'timestamp' => now()->toIso8601String(),
        ], $extra));
    }
}
