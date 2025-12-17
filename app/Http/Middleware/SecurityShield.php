<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de proteção contra scanners e ataques automatizados
 * 
 * Implementa:
 * - Detecção de User-Agents maliciosos
 * - Honeypots para identificar atacantes
 * - Delay proposital para aumentar custo de ataques
 */
class SecurityShield
{
    /**
     * Honeypots - rotas falsas que atraem atacantes
     */
    private array $honeypots = [
        'admin.php',
        'wp-admin',
        'wp-login.php',
        'phpmyadmin',
        'pma',
        '.env',
        '.git/config',
        'config.php',
        'backup.sql',
        'db.sql',
        'database.sql',
        'wp-config.php',
        'xmlrpc.php',
        'shell.php',
        'c99.php',
        'r57.php',
        'webshell.php',
        'upload.php',
        'manager/html',
        'solr/admin',
        'actuator',
        'api/swagger',
        'swagger.json',
    ];

    /**
     * User-Agents de scanners conhecidos
     */
    private array $blockedAgents = [
        'sqlmap',
        'nikto',
        'nmap',
        'masscan',
        'wpscan',
        'dirbuster',
        'gobuster',
        'nuclei',
        'burp',
        'owasp',
        'acunetix',
        'nessus',
        'openvas',
        'w3af',
        'skipfish',
        'arachni',
        'vega',
        'zaproxy',
        'havij',
        'pangolin',
    ];

    /**
     * Padrões suspeitos em paths
     */
    private array $suspiciousPatterns = [
        '/\.\.\//i',                    // Path traversal
        '/\.(env|git|svn|htaccess)/i',  // Arquivos sensíveis
        '/\.(sql|bak|backup|old)/i',    // Backups
        '/(phpmyadmin|adminer|pma)/i',  // DB tools
        '/(shell|cmd|exec|system)/i',   // RCE attempts
        '/\%00/i',                       // Null byte
        '/\<script\>/i',                 // XSS
        '/(union|select|insert|delete|drop|update).*\s+(from|into|table)/i', // SQLi
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $path = ltrim($request->path(), '/');
        $userAgent = strtolower($request->userAgent() ?? '');
        $ip = $request->ip();

        // ✅ Verifica honeypots
        if ($this->isHoneypot($path)) {
            $this->logSuspiciousActivity($request, 'honeypot_triggered', [
                'path' => $path,
            ]);
            
            // Delay proposital para aumentar custo do atacante
            usleep(random_int(1000000, 3000000)); // 1-3 segundos
            
            return response()->json(['error' => 'Not Found'], 404);
        }

        // ✅ Bloqueia User-Agents de scanners
        foreach ($this->blockedAgents as $agent) {
            if (str_contains($userAgent, $agent)) {
                $this->logSuspiciousActivity($request, 'scanner_detected', [
                    'agent' => $userAgent,
                ]);
                
                // Pode retornar 403 ou fingir que é uma página normal
                usleep(random_int(500000, 2000000));
                abort(403);
            }
        }

        // ✅ Verifica padrões suspeitos no path
        foreach ($this->suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $request->fullUrl())) {
                $this->logSuspiciousActivity($request, 'suspicious_pattern', [
                    'pattern' => $pattern,
                    'url' => $request->fullUrl(),
                ]);
                
                // Não bloqueia imediatamente, mas monitora
                // Atacantes persistentes serão banidos pelo rate limit
            }
        }

        // ✅ Verifica requests com headers suspeitos
        if ($this->hasSuspiciousHeaders($request)) {
            $this->logSuspiciousActivity($request, 'suspicious_headers', [
                'headers' => $request->headers->all(),
            ]);
        }

        return $next($request);
    }

    private function isHoneypot(string $path): bool
    {
        foreach ($this->honeypots as $honeypot) {
            if (str_contains($path, $honeypot)) {
                return true;
            }
        }
        return false;
    }

    private function hasSuspiciousHeaders(Request $request): bool
    {
        // Headers que indicam ferramentas automatizadas
        $suspiciousHeaders = [
            'x-scanner',
            'x-originating-ip',
            'x-forwarded-for' => function($value) {
                // Múltiplos IPs podem indicar proxy chain
                return substr_count($value, ',') > 3;
            },
        ];

        foreach ($suspiciousHeaders as $key => $check) {
            if (is_callable($check)) {
                if ($request->hasHeader($key) && $check($request->header($key))) {
                    return true;
                }
            } else {
                if ($request->hasHeader($check)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function logSuspiciousActivity(Request $request, string $type, array $extra = []): void
    {
        Log::channel('security')->warning("Security: {$type}", array_merge([
            'ip' => $request->ip(),
            'path' => $request->path(),
            'method' => $request->method(),
            'user_agent' => $request->userAgent(),
            'user_id' => auth()->id(),
            'timestamp' => now()->toIso8601String(),
        ], $extra));
    }
}
