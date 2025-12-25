<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ScannerController extends Controller
{
    /**
     * Executa scan completo no alvo
     */
    public function scan(Request $request): JsonResponse
    {
        $request->validate([
            'target' => 'required|string|max:255',
            'options' => 'array',
        ]);

        $user = $request->user();
        $target = $this->sanitizeTarget($request->input('target'));
        $options = $request->input('options', ['headers', 'ssl', 'dns', 'tech']);

        // Rate limiting
        $cacheKey = "scanner:{$user->id}:count";
        $scanCount = Cache::get($cacheKey, 0);
        
        if ($scanCount >= 10 && $user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'error' => 'Limite de 10 scans por hora atingido',
            ], 429);
        }
        
        Cache::put($cacheKey, $scanCount + 1, 3600);

        $results = [
            'target' => $target,
            'timestamp' => now()->toIso8601String(),
            'findings' => [],
            'summary' => [
                'critical' => 0,
                'high' => 0,
                'medium' => 0,
                'low' => 0,
                'info' => 0,
            ],
        ];

        try {
            // Scanner de Headers
            if (in_array('headers', $options)) {
                $headerFindings = $this->scanHeaders($target);
                $results['findings'] = array_merge($results['findings'], $headerFindings);
            }

            // Scanner de SSL
            if (in_array('ssl', $options)) {
                $sslFindings = $this->scanSSL($target);
                $results['findings'] = array_merge($results['findings'], $sslFindings);
            }

            // Scanner de DNS
            if (in_array('dns', $options)) {
                $dnsFindings = $this->scanDNS($target);
                $results['findings'] = array_merge($results['findings'], $dnsFindings);
            }

            // Detecção de Tecnologias
            if (in_array('tech', $options)) {
                $techFindings = $this->detectTechnologies($target);
                $results['findings'] = array_merge($results['findings'], $techFindings);
            }

            // Calcula summary
            foreach ($results['findings'] as $finding) {
                $severity = strtolower($finding['severity'] ?? 'info');
                if (isset($results['summary'][$severity])) {
                    $results['summary'][$severity]++;
                }
            }

            return response()->json([
                'success' => true,
                'data' => $results,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao escanear: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sanitiza e valida o target
     */
    private function sanitizeTarget(string $target): string
    {
        // Remove protocolo se existir
        $target = preg_replace('#^https?://#', '', $target);
        // Remove path
        $target = explode('/', $target)[0];
        // Remove porta
        $target = explode(':', $target)[0];
        
        return trim($target);
    }

    /**
     * Scanner de Headers HTTP de Segurança
     */
    private function scanHeaders(string $target): array
    {
        $findings = [];
        
        try {
            $response = Http::timeout(10)
                ->withOptions(['verify' => false])
                ->get("https://{$target}");
            
            $headers = $response->headers();
            
            // Headers de segurança importantes
            $securityHeaders = [
                'Strict-Transport-Security' => [
                    'severity' => 'high',
                    'title' => 'HSTS não configurado',
                    'description' => 'O header Strict-Transport-Security não está presente. Isso permite ataques de downgrade para HTTP.',
                    'recommendation' => 'Adicione: Strict-Transport-Security: max-age=31536000; includeSubDomains; preload',
                ],
                'X-Content-Type-Options' => [
                    'severity' => 'medium',
                    'title' => 'X-Content-Type-Options ausente',
                    'description' => 'Sem este header, o navegador pode interpretar arquivos de forma incorreta (MIME sniffing).',
                    'recommendation' => 'Adicione: X-Content-Type-Options: nosniff',
                ],
                'X-Frame-Options' => [
                    'severity' => 'medium',
                    'title' => 'X-Frame-Options ausente',
                    'description' => 'O site pode ser incorporado em iframes, permitindo ataques de clickjacking.',
                    'recommendation' => 'Adicione: X-Frame-Options: DENY ou SAMEORIGIN',
                ],
                'Content-Security-Policy' => [
                    'severity' => 'medium',
                    'title' => 'CSP não configurado',
                    'description' => 'Content Security Policy não está definido. Isso aumenta o risco de ataques XSS.',
                    'recommendation' => 'Configure uma política CSP adequada para seu site',
                ],
                'X-XSS-Protection' => [
                    'severity' => 'low',
                    'title' => 'X-XSS-Protection ausente',
                    'description' => 'Header de proteção XSS do navegador não está ativo.',
                    'recommendation' => 'Adicione: X-XSS-Protection: 1; mode=block',
                ],
                'Referrer-Policy' => [
                    'severity' => 'low',
                    'title' => 'Referrer-Policy ausente',
                    'description' => 'Sem política de referrer, informações sensíveis podem vazar via header Referer.',
                    'recommendation' => 'Adicione: Referrer-Policy: strict-origin-when-cross-origin',
                ],
                'Permissions-Policy' => [
                    'severity' => 'low',
                    'title' => 'Permissions-Policy ausente',
                    'description' => 'Sem este header, o site não controla quais APIs do navegador podem ser usadas.',
                    'recommendation' => 'Adicione: Permissions-Policy: geolocation=(), microphone=(), camera=()',
                ],
            ];

            foreach ($securityHeaders as $header => $config) {
                $headerLower = strtolower($header);
                $found = false;
                
                foreach ($headers as $key => $value) {
                    if (strtolower($key) === $headerLower) {
                        $found = true;
                        $findings[] = [
                            'type' => 'header',
                            'severity' => 'info',
                            'title' => "{$header} configurado",
                            'description' => "Valor: " . implode(', ', $value),
                            'recommendation' => 'Header configurado corretamente',
                        ];
                        break;
                    }
                }
                
                if (!$found) {
                    $findings[] = [
                        'type' => 'header',
                        'severity' => $config['severity'],
                        'title' => $config['title'],
                        'description' => $config['description'],
                        'recommendation' => $config['recommendation'],
                    ];
                }
            }

            // Verifica headers que expõem informações
            $dangerousHeaders = ['Server', 'X-Powered-By', 'X-AspNet-Version', 'X-AspNetMvc-Version'];
            foreach ($dangerousHeaders as $header) {
                foreach ($headers as $key => $value) {
                    if (strtolower($key) === strtolower($header)) {
                        $findings[] = [
                            'type' => 'header',
                            'severity' => 'low',
                            'title' => "Header {$header} expõe informações",
                            'description' => "O header {$header} revela: " . implode(', ', $value),
                            'recommendation' => "Remova ou ofusque o header {$header} para não expor informações do servidor",
                        ];
                    }
                }
            }

        } catch (\Exception $e) {
            $findings[] = [
                'type' => 'header',
                'severity' => 'info',
                'title' => 'Não foi possível verificar headers',
                'description' => 'Erro: ' . $e->getMessage(),
                'recommendation' => 'Verifique se o site está acessível',
            ];
        }

        return $findings;
    }

    /**
     * Scanner de SSL/TLS
     */
    private function scanSSL(string $target): array
    {
        $findings = [];
        
        try {
            $context = stream_context_create([
                'ssl' => [
                    'capture_peer_cert' => true,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);

            $socket = @stream_socket_client(
                "ssl://{$target}:443",
                $errno,
                $errstr,
                10,
                STREAM_CLIENT_CONNECT,
                $context
            );

            if ($socket) {
                $params = stream_context_get_params($socket);
                $cert = openssl_x509_parse($params['options']['ssl']['peer_certificate']);
                
                if ($cert) {
                    // Verifica validade
                    $validFrom = $cert['validFrom_time_t'];
                    $validTo = $cert['validTo_time_t'];
                    $now = time();
                    $daysToExpire = floor(($validTo - $now) / 86400);

                    if ($daysToExpire < 0) {
                        $findings[] = [
                            'type' => 'ssl',
                            'severity' => 'critical',
                            'title' => 'Certificado SSL expirado',
                            'description' => "O certificado expirou há " . abs($daysToExpire) . " dias",
                            'recommendation' => 'Renove o certificado SSL imediatamente',
                        ];
                    } elseif ($daysToExpire < 30) {
                        $findings[] = [
                            'type' => 'ssl',
                            'severity' => 'high',
                            'title' => 'Certificado SSL expirando em breve',
                            'description' => "O certificado expira em {$daysToExpire} dias",
                            'recommendation' => 'Renove o certificado SSL antes que expire',
                        ];
                    } else {
                        $findings[] = [
                            'type' => 'ssl',
                            'severity' => 'info',
                            'title' => 'Certificado SSL válido',
                            'description' => "Expira em {$daysToExpire} dias (" . date('d/m/Y', $validTo) . ")",
                            'recommendation' => 'Certificado OK',
                        ];
                    }

                    // Verifica emissor
                    $issuer = $cert['issuer']['O'] ?? $cert['issuer']['CN'] ?? 'Desconhecido';
                    $findings[] = [
                        'type' => 'ssl',
                        'severity' => 'info',
                        'title' => 'Emissor do certificado',
                        'description' => "Emitido por: {$issuer}",
                        'recommendation' => '',
                    ];

                    // Verifica Subject Alternative Names
                    if (isset($cert['extensions']['subjectAltName'])) {
                        $sans = $cert['extensions']['subjectAltName'];
                        $findings[] = [
                            'type' => 'ssl',
                            'severity' => 'info',
                            'title' => 'Domínios cobertos (SAN)',
                            'description' => $sans,
                            'recommendation' => '',
                        ];
                    }
                }

                fclose($socket);
            } else {
                $findings[] = [
                    'type' => 'ssl',
                    'severity' => 'critical',
                    'title' => 'SSL/TLS não disponível',
                    'description' => "Não foi possível estabelecer conexão SSL: {$errstr}",
                    'recommendation' => 'Configure SSL/TLS no servidor',
                ];
            }

        } catch (\Exception $e) {
            $findings[] = [
                'type' => 'ssl',
                'severity' => 'info',
                'title' => 'Erro ao verificar SSL',
                'description' => $e->getMessage(),
                'recommendation' => '',
            ];
        }

        return $findings;
    }

    /**
     * Scanner de DNS
     */
    private function scanDNS(string $target): array
    {
        $findings = [];

        try {
            // Registros A
            $aRecords = @dns_get_record($target, DNS_A);
            if ($aRecords) {
                $ips = array_column($aRecords, 'ip');
                $findings[] = [
                    'type' => 'dns',
                    'severity' => 'info',
                    'title' => 'Registros A (IPv4)',
                    'description' => implode(', ', $ips),
                    'recommendation' => '',
                ];
            }

            // Registros AAAA
            $aaaaRecords = @dns_get_record($target, DNS_AAAA);
            if ($aaaaRecords) {
                $ips = array_column($aaaaRecords, 'ipv6');
                $findings[] = [
                    'type' => 'dns',
                    'severity' => 'info',
                    'title' => 'Registros AAAA (IPv6)',
                    'description' => implode(', ', $ips),
                    'recommendation' => '',
                ];
            }

            // Registros MX
            $mxRecords = @dns_get_record($target, DNS_MX);
            if ($mxRecords) {
                $mxList = array_map(fn($r) => $r['target'] . ' (pri: ' . $r['pri'] . ')', $mxRecords);
                $findings[] = [
                    'type' => 'dns',
                    'severity' => 'info',
                    'title' => 'Registros MX (Email)',
                    'description' => implode(', ', $mxList),
                    'recommendation' => '',
                ];
            }

            // Registros TXT (SPF, DKIM, DMARC)
            $txtRecords = @dns_get_record($target, DNS_TXT);
            $hasSPF = false;
            $hasDMARC = false;
            
            if ($txtRecords) {
                foreach ($txtRecords as $txt) {
                    $value = $txt['txt'] ?? '';
                    if (stripos($value, 'v=spf1') !== false) {
                        $hasSPF = true;
                        $findings[] = [
                            'type' => 'dns',
                            'severity' => 'info',
                            'title' => 'SPF configurado',
                            'description' => substr($value, 0, 100) . (strlen($value) > 100 ? '...' : ''),
                            'recommendation' => '',
                        ];
                    }
                }
            }

            // Verifica DMARC
            $dmarcRecords = @dns_get_record("_dmarc.{$target}", DNS_TXT);
            if ($dmarcRecords) {
                $hasDMARC = true;
                $findings[] = [
                    'type' => 'dns',
                    'severity' => 'info',
                    'title' => 'DMARC configurado',
                    'description' => substr($dmarcRecords[0]['txt'] ?? '', 0, 100),
                    'recommendation' => '',
                ];
            }

            if (!$hasSPF) {
                $findings[] = [
                    'type' => 'dns',
                    'severity' => 'medium',
                    'title' => 'SPF não configurado',
                    'description' => 'Sem SPF, emails podem ser falsificados usando seu domínio',
                    'recommendation' => 'Configure um registro SPF TXT',
                ];
            }

            if (!$hasDMARC) {
                $findings[] = [
                    'type' => 'dns',
                    'severity' => 'medium',
                    'title' => 'DMARC não configurado',
                    'description' => 'Sem DMARC, você não tem controle sobre emails falsificados',
                    'recommendation' => 'Configure um registro DMARC em _dmarc.' . $target,
                ];
            }

            // Registros NS
            $nsRecords = @dns_get_record($target, DNS_NS);
            if ($nsRecords) {
                $nsList = array_column($nsRecords, 'target');
                $findings[] = [
                    'type' => 'dns',
                    'severity' => 'info',
                    'title' => 'Servidores DNS (NS)',
                    'description' => implode(', ', $nsList),
                    'recommendation' => '',
                ];
            }

        } catch (\Exception $e) {
            $findings[] = [
                'type' => 'dns',
                'severity' => 'info',
                'title' => 'Erro ao verificar DNS',
                'description' => $e->getMessage(),
                'recommendation' => '',
            ];
        }

        return $findings;
    }

    /**
     * Detecção de Tecnologias
     */
    private function detectTechnologies(string $target): array
    {
        $findings = [];

        try {
            $response = Http::timeout(10)
                ->withOptions(['verify' => false])
                ->get("https://{$target}");

            $headers = $response->headers();
            $body = $response->body();

            // Detecta servidor web
            foreach ($headers as $key => $value) {
                if (strtolower($key) === 'server') {
                    $findings[] = [
                        'type' => 'tech',
                        'severity' => 'info',
                        'title' => 'Servidor Web',
                        'description' => implode(', ', $value),
                        'recommendation' => '',
                    ];
                }
                if (strtolower($key) === 'x-powered-by') {
                    $findings[] = [
                        'type' => 'tech',
                        'severity' => 'info',
                        'title' => 'Tecnologia Backend',
                        'description' => implode(', ', $value),
                        'recommendation' => '',
                    ];
                }
            }

            // Detecta frameworks/CMS no HTML
            $techPatterns = [
                'WordPress' => ['wp-content', 'wp-includes', 'wordpress'],
                'Laravel' => ['laravel_session', 'XSRF-TOKEN'],
                'React' => ['react', '_reactRoot', 'data-reactroot'],
                'Vue.js' => ['vue', 'v-cloak', '__vue__'],
                'Angular' => ['ng-version', 'ng-app', 'angular'],
                'jQuery' => ['jquery', 'jQuery'],
                'Bootstrap' => ['bootstrap', 'btn-primary'],
                'Tailwind CSS' => ['tailwind', 'tw-'],
                'Cloudflare' => ['cf-ray', 'cloudflare'],
                'Google Analytics' => ['google-analytics', 'gtag', 'ga.js'],
                'Google Tag Manager' => ['googletagmanager'],
            ];

            $detected = [];
            $bodyLower = strtolower($body);
            $headersStr = strtolower(json_encode($headers));

            foreach ($techPatterns as $tech => $patterns) {
                foreach ($patterns as $pattern) {
                    if (strpos($bodyLower, strtolower($pattern)) !== false || 
                        strpos($headersStr, strtolower($pattern)) !== false) {
                        if (!in_array($tech, $detected)) {
                            $detected[] = $tech;
                            $findings[] = [
                                'type' => 'tech',
                                'severity' => 'info',
                                'title' => "Tecnologia detectada: {$tech}",
                                'description' => "Identificado pelo padrão: {$pattern}",
                                'recommendation' => '',
                            ];
                        }
                        break;
                    }
                }
            }

            // Detecta meta generator
            if (preg_match('/<meta[^>]+name=["\']generator["\'][^>]+content=["\']([^"\']+)["\']/', $body, $matches)) {
                $findings[] = [
                    'type' => 'tech',
                    'severity' => 'info',
                    'title' => 'Generator Meta Tag',
                    'description' => $matches[1],
                    'recommendation' => 'Considere remover esta tag para não expor informações',
                ];
            }

        } catch (\Exception $e) {
            $findings[] = [
                'type' => 'tech',
                'severity' => 'info',
                'title' => 'Erro ao detectar tecnologias',
                'description' => $e->getMessage(),
                'recommendation' => '',
            ];
        }

        return $findings;
    }
}
