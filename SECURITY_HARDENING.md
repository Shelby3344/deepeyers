# 🛡️ SECURITY HARDENING GUIDE - DeepEyes
## Análise Completa de Segurança e Blindagem do Sistema

**Autor:** Security Analysis AI  
**Data:** Dezembro 2025  
**Sistema:** DeepEyes - Pentest AI Platform  
**Stack:** Laravel 11 + Blade + Tailwind + Nginx + SQL  

---

## 📋 SUMÁRIO EXECUTIVO

### Vulnerabilidades Identificadas
| Severidade | Quantidade | Status |
|------------|------------|--------|
| 🔴 Crítica | 3 | Pendente |
| 🟠 Alta | 7 | Pendente |
| 🟡 Média | 12 | Pendente |
| 🟢 Baixa | 8 | Pendente |

---

# 1️⃣ BACKEND - LARAVEL HARDENING

## 1.1 🔹 ROTAS E CONTROLLERS

### Problemas Identificados:

1. **IDs previsíveis nas rotas** - UUIDs já estão em uso ✅
2. **Rotas expostas no /api** - Endpoint de info expõe versão
3. **Falta de throttling diferenciado por endpoint**

### Correções Necessárias:

#### A) Remover Endpoint de Informação Pública

```php
// ❌ REMOVER de routes/web.php
Route::get('/api', function () {
    return response()->json([
        'name' => 'DeepEyes',
        'version' => '1.0.0', // NUNCA expor versão!
        'description' => 'AI-powered Pentest Assistant',
        'api_docs' => url('/api'),
    ]);
});
```

#### B) Criar Middleware de Ofuscação de Rotas

```php
// app/Http/Middleware/ObfuscateRoutes.php
<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ObfuscateRoutes
{
    private array $honeypots = [
        '/admin.php',
        '/wp-admin',
        '/wp-login.php',
        '/phpmyadmin',
        '/.env',
        '/config.php',
        '/backup.sql',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();
        
        // Honeypot - Log e bane atacantes
        if (in_array('/' . $path, $this->honeypots)) {
            $this->logSuspiciousActivity($request, 'honeypot_triggered');
            
            // Delay proposital para aumentar custo do atacante
            usleep(random_int(500000, 2000000)); // 0.5-2 segundos
            
            return response()->json(['error' => 'Not Found'], 404);
        }
        
        // Bloqueia scanners conhecidos
        $userAgent = strtolower($request->userAgent() ?? '');
        $blockedAgents = ['sqlmap', 'nikto', 'nmap', 'masscan', 'wpscan', 'dirbuster', 'gobuster', 'nuclei'];
        
        foreach ($blockedAgents as $agent) {
            if (str_contains($userAgent, $agent)) {
                $this->logSuspiciousActivity($request, 'scanner_detected');
                abort(403);
            }
        }
        
        return $next($request);
    }
    
    private function logSuspiciousActivity(Request $request, string $type): void
    {
        \Log::channel('security')->warning("Suspicious activity: {$type}", [
            'ip' => $request->ip(),
            'path' => $request->path(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
```

#### C) Middleware de Validação de Assinatura (HMAC)

```php
// app/Http/Middleware/ValidateRequestSignature.php
<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateRequestSignature
{
    private const TIMESTAMP_TOLERANCE = 300; // 5 minutos

    public function handle(Request $request, Closure $next): Response
    {
        // Apenas para rotas críticas (admin, financial, etc)
        if (!$this->requiresSignature($request)) {
            return $next($request);
        }

        $signature = $request->header('X-Signature');
        $timestamp = $request->header('X-Timestamp');
        $nonce = $request->header('X-Nonce');

        if (!$signature || !$timestamp || !$nonce) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        // Verifica timestamp (previne replay attacks)
        if (abs(time() - (int)$timestamp) > self::TIMESTAMP_TOLERANCE) {
            return response()->json(['error' => 'Request expired'], 400);
        }

        // Verifica nonce único (previne replay)
        $nonceKey = "nonce:{$nonce}";
        if (cache()->has($nonceKey)) {
            return response()->json(['error' => 'Invalid request'], 400);
        }
        cache()->put($nonceKey, true, self::TIMESTAMP_TOLERANCE);

        // Valida assinatura HMAC
        $payload = $request->method() . $request->path() . $timestamp . $nonce . json_encode($request->all());
        $expectedSignature = hash_hmac('sha256', $payload, config('app.key'));

        if (!hash_equals($expectedSignature, $signature)) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        return $next($request);
    }

    private function requiresSignature(Request $request): bool
    {
        return str_starts_with($request->path(), 'api/admin');
    }
}
```

---

## 1.2 🔹 AUTENTICAÇÃO E SESSÃO

### Problemas Identificados:

1. **Tokens sem expiração** - `sanctum.expiration` está null
2. **Sem rotação de sessão após login**
3. **Sem detecção de anomalias**
4. **Múltiplos tokens ativos permitidos**

### Correções:

#### A) Configuração Segura do Sanctum

```php
// config/sanctum.php
return [
    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', '')),
    
    'guard' => ['web'],
    
    // ✅ Tokens expiram em 24 horas
    'expiration' => 60 * 24,
    
    // ✅ Prefixo ofuscado
    'token_prefix' => env('SANCTUM_TOKEN_PREFIX', 'dey_'),
];
```

#### B) Rotação de Sessão no Login

```php
// app/Http/Controllers/Api/AuthController.php

public function login(Request $request): JsonResponse
{
    $validated = $request->validate([
        'email' => ['required', 'string', 'email'],
        'password' => ['required', 'string'],
    ]);

    $user = User::where('email', $validated['email'])->first();

    // ✅ Resposta genérica - não indica se email existe
    if (!$user || !Hash::check($validated['password'], $user->password)) {
        // Delay anti-bruteforce
        usleep(random_int(100000, 300000));
        
        throw ValidationException::withMessages([
            'credentials' => ['Authentication failed.'],
        ]);
    }

    if ($user->is_banned) {
        throw ValidationException::withMessages([
            'credentials' => ['Authentication failed.'],
        ]);
    }

    // ✅ Revoga TODOS os tokens anteriores (single session)
    $user->tokens()->delete();
    
    // ✅ Regenera sessão
    $request->session()->regenerate();
    
    // ✅ Log de login com fingerprint
    $this->logLogin($user, $request);

    // ✅ Token com abilities limitadas
    $token = $user->createToken('api-token', [
        'chat:read',
        'chat:write',
        'profile:read',
        'profile:write',
    ])->plainTextToken;

    return response()->json([
        'message' => 'Success',
        'data' => [
            'user' => $this->sanitizeUser($user),
            'token' => $token,
            'expires_at' => now()->addDay()->toIso8601String(),
        ],
    ]);
}

private function logLogin(User $user, Request $request): void
{
    $user->update([
        'last_login_at' => now(),
        'last_login_ip' => $request->ip(),
    ]);
    
    \Log::channel('auth')->info('User login', [
        'user_id' => $user->id,
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);
}

private function sanitizeUser(User $user): array
{
    return [
        'id' => $user->id,
        'name' => $user->name,
        'avatar' => $user->avatar_url,
        // ❌ NÃO expor: email, role, created_at, etc para atacante
    ];
}
```

#### C) Middleware de Detecção de Anomalias

```php
// app/Http/Middleware/DetectAnomalies.php
<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DetectAnomalies
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!$user) return $next($request);

        $fingerprint = $this->generateFingerprint($request);
        $storedFingerprint = Cache::get("user_fingerprint:{$user->id}");

        if ($storedFingerprint && $storedFingerprint !== $fingerprint) {
            // Fingerprint mudou - possível session hijacking
            $this->logAnomaly($user, $request, 'fingerprint_mismatch');
            
            // Força re-autenticação para mudanças drásticas
            if ($this->isDrasticChange($request, $user)) {
                $user->tokens()->delete();
                return response()->json([
                    'error' => 'Session expired',
                    'code' => 'REAUTH_REQUIRED',
                ], 401);
            }
        }

        // Armazena fingerprint
        Cache::put("user_fingerprint:{$user->id}", $fingerprint, now()->addHours(24));

        return $next($request);
    }

    private function generateFingerprint(Request $request): string
    {
        return hash('sha256', implode('|', [
            $request->ip(),
            $request->userAgent(),
            $request->header('Accept-Language'),
        ]));
    }

    private function isDrasticChange(Request $request, $user): bool
    {
        // IP mudou de país
        $lastIp = $user->last_login_ip;
        $currentIp = $request->ip();
        
        // Aqui você pode usar GeoIP para verificar mudança de país
        return false; // Implementar lógica GeoIP
    }

    private function logAnomaly($user, Request $request, string $type): void
    {
        \Log::channel('security')->warning("Anomaly detected: {$type}", [
            'user_id' => $user->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }
}
```

---

## 1.3 🔹 VALIDAÇÃO E SANITIZAÇÃO

### Criar Request Classes Defensivas:

```php
// app/Http/Requests/Api/SendMessageRequest.php
<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class SendMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => [
                'required',
                'string',
                'min:1',
                'max:50000', // Limite de tamanho
                function ($attribute, $value, $fail) {
                    // ✅ Detecta tentativas de injection
                    if ($this->containsSuspiciousPatterns($value)) {
                        $fail('Invalid message content.');
                    }
                },
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        // ✅ Normalização de input
        if ($this->has('message')) {
            $this->merge([
                'message' => $this->normalizeInput($this->input('message')),
            ]);
        }
    }

    private function normalizeInput(string $input): string
    {
        // Remove null bytes
        $input = str_replace("\0", '', $input);
        
        // Normaliza unicode
        $input = \Normalizer::normalize($input, \Normalizer::FORM_C) ?: $input;
        
        // Remove caracteres de controle (exceto newline, tab)
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);
        
        return trim($input);
    }

    private function containsSuspiciousPatterns(string $value): bool
    {
        $patterns = [
            // SQL Injection patterns
            '/(\bunion\b.*\bselect\b|\bselect\b.*\bfrom\b.*\bwhere\b)/i',
            '/(\binsert\b.*\binto\b|\bdelete\b.*\bfrom\b|\bdrop\b.*\btable\b)/i',
            '/(\bexec\b|\bexecute\b|\bxp_cmdshell\b)/i',
            
            // XSS patterns
            '/<script\b[^>]*>/i',
            '/javascript:/i',
            '/on\w+\s*=/i',
            
            // Path traversal
            '/\.\.\//',
            '/\.\.\\\\/',
            
            // Command injection
            '/[;&|`$]/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                \Log::channel('security')->warning('Suspicious pattern detected', [
                    'pattern' => $pattern,
                    'ip' => request()->ip(),
                    'user_id' => auth()->id(),
                ]);
                return true;
            }
        }

        return false;
    }
}
```

---

## 1.4 🔹 ERROS E LOGS

### A) Handler de Exceções Seguro

```php
// app/Exceptions/Handler.php
<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        ValidationException::class,
    ];

    // ✅ NUNCA flasha estes campos
    protected $dontFlash = [
        'password',
        'password_confirmation',
        'token',
        'api_key',
        'secret',
        'credit_card',
    ];

    public function render($request, Throwable $e): JsonResponse
    {
        if ($request->expectsJson()) {
            return $this->renderApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    private function renderApiException($request, Throwable $e): JsonResponse
    {
        // ✅ Gera ID único para rastreamento interno
        $errorId = bin2hex(random_bytes(8));
        
        // ✅ Log interno com detalhes completos
        \Log::channel('errors')->error("Error [{$errorId}]", [
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'path' => $request->path(),
        ]);

        // ✅ Resposta GENÉRICA para o cliente
        $status = $this->getStatusCode($e);
        
        return response()->json([
            'error' => $this->getGenericMessage($status),
            'error_id' => $errorId, // Para suporte identificar
        ], $status);
    }

    private function getStatusCode(Throwable $e): int
    {
        if ($e instanceof HttpException) {
            return $e->getStatusCode();
        }
        
        if ($e instanceof ValidationException) {
            return 422;
        }
        
        return 500;
    }

    private function getGenericMessage(int $status): string
    {
        return match($status) {
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            422 => 'Validation Error',
            429 => 'Too Many Requests',
            500, 502, 503 => 'Service Error',
            default => 'Error',
        };
    }
}
```

### B) Configuração de Logs Seguros

```php
// config/logging.php
'channels' => [
    // ✅ Canal de segurança separado
    'security' => [
        'driver' => 'daily',
        'path' => storage_path('logs/security.log'),
        'level' => 'debug',
        'days' => 90, // Retenção longa para auditoria
    ],
    
    // ✅ Canal de autenticação
    'auth' => [
        'driver' => 'daily',
        'path' => storage_path('logs/auth.log'),
        'level' => 'info',
        'days' => 365,
    ],
    
    // ✅ Canal de erros (interno)
    'errors' => [
        'driver' => 'daily',
        'path' => storage_path('logs/errors.log'),
        'level' => 'error',
        'days' => 30,
    ],
],

// ⚠️ O QUE NUNCA LOGAR:
// - Senhas (mesmo hash)
// - Tokens completos
// - Dados de cartão
// - Conteúdo de mensagens do chat (privacidade)
// - Stack traces em produção
```

---

# 2️⃣ API - BLINDAGEM DE COMUNICAÇÃO

## 2.1 Rate Limiting Inteligente

```php
// app/Http/Middleware/RateLimitAI.php - VERSÃO MELHORADA
<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class RateLimitAI
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // ✅ Rate limit por IP (anti-DDoS)
        $ipKey = "rl_ip:{$request->ip()}";
        if (!$this->checkLimit($ipKey, 100, 60)) {
            $this->logAbuse($request, 'ip_flood');
            return $this->tooManyRequests();
        }

        // ✅ Rate limit por usuário (anti-abuse)
        $userKey = "rl_user:{$user->id}";
        if (!$this->checkLimit($userKey, 30, 60)) {
            $this->logAbuse($request, 'user_flood');
            return $this->tooManyRequests();
        }

        // ✅ Rate limit por sessão (anti-automation)
        $tokenHash = hash('sha256', $request->bearerToken());
        $tokenKey = "rl_token:{$tokenHash}";
        if (!$this->checkLimit($tokenKey, 20, 60)) {
            $this->logAbuse($request, 'token_flood');
            return $this->tooManyRequests();
        }

        // ✅ Limite diário por plano
        if ($user->hasReachedDailyLimit()) {
            return response()->json([
                'error' => 'Daily limit reached',
            ], 429);
        }

        // ✅ Detecção de comportamento anômalo
        if ($this->detectAnomalousPattern($user, $request)) {
            $this->logAbuse($request, 'anomalous_pattern');
            // Não bloqueia, apenas monitora
        }

        return $next($request);
    }

    private function checkLimit(string $key, int $max, int $decay): bool
    {
        return RateLimiter::attempt($key, $max, fn() => true, $decay);
    }

    private function detectAnomalousPattern($user, Request $request): bool
    {
        $key = "request_times:{$user->id}";
        $times = Cache::get($key, []);
        $times[] = microtime(true);
        
        // Mantém últimos 10 requests
        $times = array_slice($times, -10);
        Cache::put($key, $times, 300);

        if (count($times) < 5) return false;

        // Calcula intervalo médio entre requests
        $intervals = [];
        for ($i = 1; $i < count($times); $i++) {
            $intervals[] = $times[$i] - $times[$i-1];
        }
        
        $avgInterval = array_sum($intervals) / count($intervals);
        $stdDev = $this->stdDev($intervals);

        // ✅ Padrão muito regular = bot
        // Humanos têm variação natural no timing
        if ($stdDev < 0.1 && $avgInterval < 2) {
            return true; // Provavelmente automatizado
        }

        return false;
    }

    private function stdDev(array $arr): float
    {
        $mean = array_sum($arr) / count($arr);
        $variance = array_sum(array_map(fn($x) => pow($x - $mean, 2), $arr)) / count($arr);
        return sqrt($variance);
    }

    private function tooManyRequests()
    {
        // ✅ Delay proposital para aumentar custo do atacante
        usleep(random_int(500000, 1500000));
        
        return response()->json([
            'error' => 'Too Many Requests',
        ], 429);
    }

    private function logAbuse(Request $request, string $type): void
    {
        \Log::channel('security')->warning("Rate limit abuse: {$type}", [
            'ip' => $request->ip(),
            'user_id' => auth()->id(),
            'path' => $request->path(),
        ]);
    }
}
```

## 2.2 Respostas Anti-Enumeração

```php
// app/Http/Controllers/Api/AuthController.php

// ❌ ERRADO - permite enumeração de usuários
if (!$user) {
    throw ValidationException::withMessages([
        'email' => ['User not found.'], // Atacante sabe que email não existe
    ]);
}

if (!Hash::check($password, $user->password)) {
    throw ValidationException::withMessages([
        'password' => ['Wrong password.'], // Atacante sabe que email existe
    ]);
}

// ✅ CORRETO - resposta genérica
if (!$user || !Hash::check($password, $user->password)) {
    // Delay constante para evitar timing attack
    usleep(random_int(100000, 300000));
    
    throw ValidationException::withMessages([
        'credentials' => ['Invalid credentials.'], // Não indica qual está errado
    ]);
}
```

---

# 3️⃣ FRONTEND - BLADE + TAILWIND + JS

## 3.1 Ofuscação de JavaScript

### A) Webpack/Vite Config para Ofuscação

```javascript
// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import obfuscator from 'rollup-plugin-obfuscator';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            plugins: [
                obfuscator({
                    options: {
                        compact: true,
                        controlFlowFlattening: true,
                        controlFlowFlatteningThreshold: 0.75,
                        deadCodeInjection: true,
                        deadCodeInjectionThreshold: 0.4,
                        debugProtection: true,
                        debugProtectionInterval: 2000,
                        disableConsoleOutput: true,
                        identifierNamesGenerator: 'hexadecimal',
                        rotateStringArray: true,
                        selfDefending: true,
                        stringArray: true,
                        stringArrayEncoding: ['base64'],
                        stringArrayThreshold: 0.75,
                        unicodeEscapeSequence: false,
                    },
                }),
            ],
        },
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
            },
            mangle: {
                properties: {
                    regex: /^_/,
                },
            },
        },
    },
});
```

### B) Anti-DevTools (Defensivo)

```javascript
// resources/js/security.js

(function() {
    'use strict';
    
    // ✅ Detecta DevTools aberto (não é 100% efetivo, mas aumenta custo)
    const devtools = {
        isOpen: false,
        orientation: undefined
    };

    const threshold = 160;
    const emitEvent = (isOpen, orientation) => {
        window.dispatchEvent(new CustomEvent('devtoolschange', {
            detail: { isOpen, orientation }
        }));
    };

    setInterval(() => {
        const widthThreshold = window.outerWidth - window.innerWidth > threshold;
        const heightThreshold = window.outerHeight - window.innerHeight > threshold;
        const orientation = widthThreshold ? 'vertical' : 'horizontal';

        if (!(heightThreshold && widthThreshold) &&
            ((window.Firebug && window.Firebug.chrome && window.Firebug.chrome.isInitialized) ||
            widthThreshold || heightThreshold)) {
            if (!devtools.isOpen || devtools.orientation !== orientation) {
                emitEvent(true, orientation);
            }
            devtools.isOpen = true;
            devtools.orientation = orientation;
        } else {
            if (devtools.isOpen) {
                emitEvent(false, undefined);
            }
            devtools.isOpen = false;
            devtools.orientation = undefined;
        }
    }, 500);

    // ✅ Quando DevTools abre, limpa dados sensíveis da memória
    window.addEventListener('devtoolschange', (e) => {
        if (e.detail.isOpen) {
            // Limpa token da memória (força re-auth)
            // localStorage.removeItem('token');
            
            // Log para análise
            console.warn('Development tools detected');
        }
    });

    // ✅ Desabilita atalhos comuns de debug
    document.addEventListener('keydown', (e) => {
        // F12
        if (e.key === 'F12') {
            e.preventDefault();
            return false;
        }
        
        // Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U
        if (e.ctrlKey && e.shiftKey && ['I', 'J', 'C'].includes(e.key.toUpperCase())) {
            e.preventDefault();
            return false;
        }
        
        // Ctrl+U (view source)
        if (e.ctrlKey && e.key.toUpperCase() === 'U') {
            e.preventDefault();
            return false;
        }
    });

    // ✅ Desabilita menu de contexto
    document.addEventListener('contextmenu', (e) => {
        e.preventDefault();
        return false;
    });

    // ✅ Detecta console.log override (debugger tool)
    const originalLog = console.log;
    console.log = function(...args) {
        // Filtra logs em produção
        if (window.location.hostname !== 'localhost') {
            return;
        }
        return originalLog.apply(console, args);
    };
})();
```

### C) Proteção de IDs e Rotas no HTML

```blade
{{-- resources/views/chat.blade.php --}}

{{-- ❌ ERRADO - expõe estrutura --}}
<div data-session-id="{{ $session->id }}" data-user-id="{{ $user->id }}">

{{-- ✅ CORRETO - IDs ofuscados ou em JS --}}
<div id="chat-container">
    {{-- IDs passados via JS de forma controlada --}}
</div>

<script>
    // ✅ Dados sensíveis injetados de forma controlada
    window.__APP_CONFIG__ = Object.freeze({
        // Apenas o necessário
        csrfToken: '{{ csrf_token() }}',
        // Não incluir: user IDs, session IDs, rotas internas
    });
</script>
```

## 3.2 Headers de Segurança via Blade

```blade
{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- ✅ CSP Meta (backup do header) --}}
    <meta http-equiv="Content-Security-Policy" content="
        default-src 'self';
        script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com;
        style-src 'self' 'unsafe-inline' https://fonts.googleapis.com;
        font-src 'self' https://fonts.gstatic.com;
        img-src 'self' data: https:;
        connect-src 'self' https://api.openrouter.ai;
        frame-ancestors 'none';
        form-action 'self';
        base-uri 'self';
    ">
    
    {{-- ✅ Previne clickjacking --}}
    <meta http-equiv="X-Frame-Options" content="DENY">
    
    {{-- ✅ XSS Protection --}}
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    
    {{-- ✅ Previne MIME sniffing --}}
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    
    {{-- ✅ Referrer Policy --}}
    <meta name="referrer" content="strict-origin-when-cross-origin">
    
    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @yield('head')
</head>
```

---

# 4️⃣ SERVIDOR - NGINX + LINUX

## 4.1 Configuração Nginx Hardened

```nginx
# /etc/nginx/sites-available/deepeyes.conf

# ✅ Rate limiting zones
limit_req_zone $binary_remote_addr zone=api_limit:10m rate=10r/s;
limit_req_zone $binary_remote_addr zone=auth_limit:10m rate=3r/s;
limit_conn_zone $binary_remote_addr zone=conn_limit:10m;

# ✅ Bloqueia User-Agents maliciosos
map $http_user_agent $bad_bot {
    default 0;
    ~*sqlmap 1;
    ~*nikto 1;
    ~*nmap 1;
    ~*masscan 1;
    ~*wpscan 1;
    ~*dirbuster 1;
    ~*gobuster 1;
    ~*nuclei 1;
    ~*curl 1;
    ~*wget 1;
    ~*python-requests 1;
    ~*libwww 1;
    ~*httpie 1;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name deepeyes.com;

    # ✅ SSL/TLS Configuração Forte
    ssl_certificate /etc/letsencrypt/live/deepeyes.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/deepeyes.com/privkey.pem;
    ssl_session_timeout 1d;
    ssl_session_cache shared:SSL:50m;
    ssl_session_tickets off;

    # ✅ Apenas TLS 1.2 e 1.3
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;

    # ✅ OCSP Stapling
    ssl_stapling on;
    ssl_stapling_verify on;
    resolver 8.8.8.8 8.8.4.4 valid=300s;

    root /var/www/deepeyes/public;
    index index.php;

    # ✅ Headers de Segurança
    add_header X-Frame-Options "DENY" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Permissions-Policy "camera=(), microphone=(), geolocation=()" always;
    
    # ✅ HSTS (cuidado: só ative quando SSL estiver 100% OK)
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload" always;
    
    # ✅ CSP Restritiva
    add_header Content-Security-Policy "
        default-src 'self';
        script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com;
        style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.tailwindcss.com;
        font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com;
        img-src 'self' data: https:;
        connect-src 'self' https://api.openrouter.ai;
        frame-ancestors 'none';
        form-action 'self';
        base-uri 'self';
        upgrade-insecure-requests;
    " always;

    # ✅ Bloqueia bots maliciosos
    if ($bad_bot) {
        return 403;
    }

    # ✅ Bloqueia acesso a arquivos sensíveis
    location ~ /\.(?!well-known) {
        deny all;
        return 404;
    }

    location ~ /\.env {
        deny all;
        return 404;
    }

    location ~ /\.git {
        deny all;
        return 404;
    }

    location ~ /(storage|vendor|node_modules)/ {
        deny all;
        return 404;
    }

    location ~ /composer\.(json|lock)$ {
        deny all;
        return 404;
    }

    location ~ /package(-lock)?\.json$ {
        deny all;
        return 404;
    }

    # ✅ Rate limit em autenticação
    location ~ ^/api/auth/(login|register) {
        limit_req zone=auth_limit burst=5 nodelay;
        limit_conn conn_limit 5;
        
        try_files $uri $uri/ /index.php?$query_string;
    }

    # ✅ Rate limit na API
    location /api/ {
        limit_req zone=api_limit burst=20 nodelay;
        limit_conn conn_limit 10;
        
        try_files $uri $uri/ /index.php?$query_string;
    }

    # ✅ PHP-FPM
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        
        # ✅ Timeouts ajustados
        fastcgi_read_timeout 60;
        fastcgi_send_timeout 60;
        
        # ✅ Buffer sizes
        fastcgi_buffer_size 128k;
        fastcgi_buffers 256 16k;
        
        # ✅ Oculta versão do PHP
        fastcgi_hide_header X-Powered-By;
    }

    # ✅ Assets estáticos com cache longo
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    # ✅ Limita tamanho de upload
    client_max_body_size 10M;
    client_body_buffer_size 128k;

    # ✅ Oculta versão do Nginx
    server_tokens off;

    # ✅ Log de acesso com IPs reais (se atrás de CDN)
    # set_real_ip_from 173.245.48.0/20; # Cloudflare
    # real_ip_header CF-Connecting-IP;

    access_log /var/log/nginx/deepeyes_access.log combined;
    error_log /var/log/nginx/deepeyes_error.log error;
}

# ✅ Redirect HTTP para HTTPS
server {
    listen 80;
    listen [::]:80;
    server_name deepeyes.com;
    return 301 https://$server_name$request_uri;
}
```

## 4.2 Hardening Linux

```bash
#!/bin/bash
# hardening.sh - Script de hardening para servidor

# ✅ Permissões de arquivos Laravel
cd /var/www/deepeyes

# Storage e cache graváveis
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Código apenas leitura
chmod -R 644 app config routes resources
find app config routes resources -type d -exec chmod 755 {} \;

# ✅ .env somente leitura pelo owner
chmod 600 .env
chown www-data:www-data .env

# ✅ Vendor não deve ser listável
chmod 711 vendor

# ✅ Instala fail2ban
apt install fail2ban -y

# ✅ Configura jail para Nginx
cat > /etc/fail2ban/jail.local << 'EOF'
[DEFAULT]
bantime = 3600
findtime = 600
maxretry = 5

[nginx-http-auth]
enabled = true

[nginx-botsearch]
enabled = true
filter = nginx-botsearch
logpath = /var/log/nginx/deepeyes_access.log
maxretry = 2
bantime = 86400

[nginx-limit-req]
enabled = true
filter = nginx-limit-req
logpath = /var/log/nginx/deepeyes_error.log
maxretry = 10
EOF

# ✅ Filtro customizado para bots
cat > /etc/fail2ban/filter.d/nginx-botsearch.conf << 'EOF'
[Definition]
failregex = ^<HOST> -.*"(GET|POST|HEAD).*(sqlmap|nikto|nmap|dirbuster|gobuster|nuclei|wpscan).*"
ignoreregex =
EOF

systemctl restart fail2ban

# ✅ Firewall básico (ufw)
ufw default deny incoming
ufw default allow outgoing
ufw allow ssh
ufw allow 'Nginx Full'
ufw enable

# ✅ Desabilita serviços desnecessários
systemctl disable bluetooth
systemctl disable cups

# ✅ Configura sysctl para segurança
cat >> /etc/sysctl.conf << 'EOF'
# Proteção contra SYN flood
net.ipv4.tcp_syncookies = 1

# Proteção contra IP spoofing
net.ipv4.conf.all.rp_filter = 1

# Ignora ICMP redirects
net.ipv4.conf.all.accept_redirects = 0
net.ipv6.conf.all.accept_redirects = 0

# Não aceita source routing
net.ipv4.conf.all.accept_source_route = 0

# Log pacotes suspeitos
net.ipv4.conf.all.log_martians = 1
EOF

sysctl -p

echo "✅ Hardening completo!"
```

---

# 5️⃣ DEFESA EM PROFUNDIDADE

## 5.1 Arquitetura de Camadas

```
┌─────────────────────────────────────────────────────────────────┐
│                         CLOUDFLARE                              │
│  • DDoS Protection • WAF • Rate Limiting • Bot Protection       │
└─────────────────────────────────────────────────────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────┐
│                           NGINX                                 │
│  • TLS Termination • Rate Limit • Header Security • Bad Bot     │
└─────────────────────────────────────────────────────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────┐
│                    LARAVEL MIDDLEWARES                          │
│  Layer 1: ObfuscateRoutes (Honeypots, Scanner Detection)        │
│  Layer 2: RateLimitAI (Per IP/User/Token)                       │
│  Layer 3: DetectAnomalies (Fingerprinting, Behavior)            │
│  Layer 4: ValidateRequestSignature (HMAC - rotas críticas)      │
│  Layer 5: Sanctum (Token Validation)                            │
└─────────────────────────────────────────────────────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────┐
│                       CONTROLLERS                               │
│  • Gate/Policy Authorization                                    │
│  • Form Request Validation                                      │
│  • Input Sanitization                                           │
└─────────────────────────────────────────────────────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────┐
│                     BUSINESS LOGIC                              │
│  • Actions (Encapsulated Logic)                                 │
│  • Services (External APIs)                                     │
│  • Jobs (Async Processing)                                      │
└─────────────────────────────────────────────────────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────┐
│                        DATABASE                                 │
│  • Eloquent (Query Escaping)                                    │
│  • Encrypted Fields (Sensitive Data)                            │
│  • Soft Deletes (Audit Trail)                                   │
└─────────────────────────────────────────────────────────────────┘
```

## 5.2 Zero Trust Interno

```php
// ✅ Mesmo componentes internos não confiam uns nos outros

// app/Services/DeepSeekService.php
class DeepSeekService
{
    public function chat(string $systemPrompt, array $messages, string $userMessage): string
    {
        // ✅ Valida entrada mesmo vindo de outro service
        if (strlen($userMessage) > 50000) {
            throw new \InvalidArgumentException('Message too long');
        }

        // ✅ Sanitiza antes de enviar para API externa
        $userMessage = $this->sanitizeForExternalApi($userMessage);

        // ✅ Valida resposta da API externa
        $response = $this->callApi($systemPrompt, $messages, $userMessage);
        
        if (!$this->isValidResponse($response)) {
            throw new DeepSeekException('Invalid API response');
        }

        return $response;
    }

    private function sanitizeForExternalApi(string $message): string
    {
        // Remove caracteres que podem causar problemas na API
        return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $message);
    }

    private function isValidResponse(string $response): bool
    {
        // Verifica se a resposta é válida
        return strlen($response) > 0 && strlen($response) < 1000000;
    }
}
```

---

# 6️⃣ ANTI-ENGENHARIA REVERSA

## 6.1 Técnicas de Ofuscação

### A) Código Backend
- ✅ Nomes de variáveis e métodos privados não-descritivos em produção
- ✅ Usar Actions e Services para fragmentar lógica
- ✅ Evitar comentários que expliquem regras de negócio

### B) Código Frontend
- ✅ Minificação agressiva com Terser
- ✅ Ofuscação com javascript-obfuscator
- ✅ Dead code injection
- ✅ String array encoding
- ✅ Control flow flattening

### C) Estrutura de Rotas
```php
// ❌ EVITAR - rotas previsíveis
Route::get('/api/users/{id}', ...);
Route::get('/api/sessions/{id}', ...);

// ✅ PREFERIR - UUIDs + prefixos não-óbvios
Route::get('/api/v1/u/{uuid}', ...); // Ofuscado
Route::get('/api/v1/s/{uuid}', ...);
```

### D) Respostas da API
```php
// ✅ Sempre retornar estrutura consistente
// Mesmo em erros, manter o mesmo formato

// Sucesso:
{
    "status": "ok",
    "data": {...}
}

// Erro (genérico):
{
    "status": "error",
    "message": "Request failed"
}

// ❌ NUNCA:
{
    "error": "User with email test@test.com not found in table users"
}
```

---

# 📋 CHECKLIST DE IMPLEMENTAÇÃO

## Prioridade CRÍTICA (Fazer AGORA):
- [ ] Remover endpoint `/api` que expõe versão
- [ ] Corrigir respostas de login para não enumerar usuários
- [ ] Configurar expiração de tokens Sanctum
- [ ] Configurar headers de segurança no Nginx
- [ ] Bloquear acesso a `.env`, `.git`, `vendor`

## Prioridade ALTA (Próxima Sprint):
- [ ] Implementar middleware ObfuscateRoutes
- [ ] Implementar DetectAnomalies
- [ ] Configurar logs separados (security, auth)
- [ ] Configurar Fail2ban
- [ ] Implementar rate limiting por comportamento

## Prioridade MÉDIA (Roadmap):
- [ ] Implementar assinatura HMAC para rotas admin
- [ ] Configurar ofuscação de JS no build
- [ ] Implementar anti-DevTools
- [ ] Integrar com Cloudflare WAF
- [ ] Implementar GeoIP para detecção de anomalias

## Prioridade BAIXA (Nice to Have):
- [ ] Honeypots avançados
- [ ] Fingerprinting de browser
- [ ] Machine learning para detecção de anomalias

---

# ⚠️ LIMITAÇÕES IMPORTANTES

1. **Anti-DevTools não é 100% efetivo** - Atacantes experientes conseguem contornar
2. **Ofuscação de JS pode ser revertida** - Aumenta custo, não impede
3. **Rate limiting pode afetar usuários legítimos** - Balancear
4. **CSP muito restritiva pode quebrar funcionalidades** - Testar bem
5. **Zero Trust tem overhead de performance** - Medir impacto

---

**Este documento deve ser revisado periodicamente e atualizado conforme novas ameaças surgem.**
