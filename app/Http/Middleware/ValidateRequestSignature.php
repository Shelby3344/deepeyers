<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de validação de assinatura HMAC para rotas críticas
 * 
 * Implementa:
 * - Validação de assinatura HMAC
 * - Verificação de timestamp (previne replay attacks)
 * - Nonce único (previne reutilização)
 */
class ValidateRequestSignature
{
    private const TIMESTAMP_TOLERANCE = 300; // 5 minutos

    public function handle(Request $request, Closure $next): Response
    {
        // Apenas para rotas críticas (admin)
        if (!$this->requiresSignature($request)) {
            return $next($request);
        }

        $signature = $request->header('X-Signature');
        $timestamp = $request->header('X-Timestamp');
        $nonce = $request->header('X-Nonce');

        // Verifica se headers necessários estão presentes
        if (!$signature || !$timestamp || !$nonce) {
            Log::channel('security')->warning('Missing signature headers', [
                'ip' => $request->ip(),
                'path' => $request->path(),
            ]);
            return response()->json(['error' => 'Invalid request'], 400);
        }

        // Verifica timestamp (previne replay attacks)
        $requestTime = (int) $timestamp;
        $currentTime = time();
        
        if (abs($currentTime - $requestTime) > self::TIMESTAMP_TOLERANCE) {
            Log::channel('security')->warning('Expired request timestamp', [
                'ip' => $request->ip(),
                'request_time' => $requestTime,
                'current_time' => $currentTime,
                'diff' => abs($currentTime - $requestTime),
            ]);
            return response()->json(['error' => 'Request expired'], 400);
        }

        // Verifica nonce único (previne replay)
        $nonceKey = "nonce:{$nonce}";
        if (Cache::has($nonceKey)) {
            Log::channel('security')->warning('Nonce reuse attempt', [
                'ip' => $request->ip(),
                'nonce' => substr($nonce, 0, 16) . '...',
            ]);
            return response()->json(['error' => 'Invalid request'], 400);
        }
        
        // Armazena nonce para prevenir reutilização
        Cache::put($nonceKey, true, self::TIMESTAMP_TOLERANCE);

        // Valida assinatura HMAC
        $payload = $this->buildPayload($request, $timestamp, $nonce);
        $expectedSignature = hash_hmac('sha256', $payload, config('app.key'));

        if (!hash_equals($expectedSignature, $signature)) {
            Log::channel('security')->warning('Invalid signature', [
                'ip' => $request->ip(),
                'path' => $request->path(),
            ]);
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        return $next($request);
    }

    /**
     * Verifica se a rota requer assinatura
     */
    private function requiresSignature(Request $request): bool
    {
        $criticalPaths = [
            'api/admin',
            'admin/users',
            'admin/settings',
        ];

        foreach ($criticalPaths as $path) {
            if (str_starts_with($request->path(), $path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Constrói o payload para validação de assinatura
     */
    private function buildPayload(Request $request, string $timestamp, string $nonce): string
    {
        $body = $request->getContent();
        
        return implode(':', [
            $request->method(),
            $request->path(),
            $timestamp,
            $nonce,
            $body ?: '',
        ]);
    }
}
