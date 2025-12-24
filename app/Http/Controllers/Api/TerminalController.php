<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

class TerminalController extends Controller
{
    /**
     * Rate limiting config
     */
    private int $maxCommandsPerMinute = 10;
    private int $maxCommandsPerHour = 60;

    /**
     * Comandos permitidos (whitelist) com suas configurações
     */
    private array $allowedCommands = [
        // Reconhecimento DNS/WHOIS (seguros)
        'whois' => ['timeout' => 30, 'args_required' => true, 'description' => 'Consulta informações WHOIS de domínios'],
        'dig' => ['timeout' => 15, 'args_required' => true, 'description' => 'Consulta DNS detalhada'],
        'nslookup' => ['timeout' => 15, 'args_required' => true, 'description' => 'Consulta DNS simples'],
        'host' => ['timeout' => 15, 'args_required' => true, 'description' => 'Resolução de DNS'],
        
        // Rede básica
        'ping' => ['timeout' => 10, 'args_required' => true, 'default_args' => '-c 4', 'description' => 'Teste de conectividade'],
        'traceroute' => ['timeout' => 30, 'args_required' => true, 'description' => 'Rastreamento de rota'],
        
        // HTTP/Web (com restrições)
        'curl' => ['timeout' => 30, 'args_required' => true, 'blocked_args' => ['-o', '--output', '-O', '>', '>>', '|', '-d', '--data', '-X POST', '-X PUT', '-X DELETE'], 'description' => 'Requisições HTTP (GET apenas)'],
        
        // Scanner de portas (se instalado)
        'nmap' => ['timeout' => 120, 'args_required' => true, 'blocked_args' => ['-oN', '-oX', '-oG', '-oA', '>', '>>', '|', '--script'], 'description' => 'Scanner de portas'],
        
        // Ferramentas de pentest (se instaladas)
        'nikto' => ['timeout' => 300, 'args_required' => true, 'description' => 'Scanner de vulnerabilidades web'],
        'gobuster' => ['timeout' => 300, 'args_required' => true, 'description' => 'Fuzzing de diretórios'],
        'wpscan' => ['timeout' => 300, 'args_required' => true, 'description' => 'Scanner WordPress'],
        'subfinder' => ['timeout' => 120, 'args_required' => true, 'description' => 'Descoberta de subdomínios'],
        'httpx' => ['timeout' => 60, 'args_required' => true, 'description' => 'Probe HTTP'],
        
        // Utilitários seguros
        'echo' => ['timeout' => 5, 'args_required' => false, 'description' => 'Exibe texto'],
        'date' => ['timeout' => 5, 'args_required' => false, 'description' => 'Data/hora atual'],
        'whoami' => ['timeout' => 5, 'args_required' => false, 'description' => 'Usuário atual'],
        'uname' => ['timeout' => 5, 'args_required' => false, 'description' => 'Informações do sistema'],
        'hostname' => ['timeout' => 5, 'args_required' => false, 'description' => 'Nome do servidor'],
    ];

    /**
     * Padrões bloqueados em qualquer comando (segurança)
     */
    private array $blockedPatterns = [
        '/[;&|`$()]/',           // Operadores de shell
        '/\.\.\//i',             // Path traversal
        '/\/etc\//i',            // Arquivos de sistema
        '/\/var\//i',            // Arquivos de sistema
        '/\/root/i',             // Home do root
        '/\/home\//i',           // Home de usuários
        '/rm\s/i',               // Comando rm
        '/sudo/i',               // Sudo
        '/su\s/i',               // Su
        '/chmod/i',              // Chmod
        '/chown/i',              // Chown
        '/mkfs/i',               // Formatação
        '/dd\s/i',               // DD
        '/>\s*\//',              // Redirect para raiz
        '/base64/i',             // Encoding (pode ser usado para bypass)
        '/eval/i',               // Eval
        '/exec/i',               // Exec
        '/system/i',             // System
        '/passthru/i',           // Passthru
        '/shell_exec/i',         // Shell exec
        '/proc\//i',             // Proc filesystem
        '/sys\//i',              // Sys filesystem
    ];

    /**
     * Verifica rate limiting
     */
    private function checkRateLimit(int $userId): ?JsonResponse
    {
        $minuteKey = "terminal_rate:{$userId}:minute";
        $hourKey = "terminal_rate:{$userId}:hour";

        $minuteCount = Cache::get($minuteKey, 0);
        $hourCount = Cache::get($hourKey, 0);

        if ($minuteCount >= $this->maxCommandsPerMinute) {
            return response()->json([
                'success' => false,
                'type' => 'error',
                'output' => "Rate limit: máximo {$this->maxCommandsPerMinute} comandos por minuto. Aguarde um momento.",
            ], 429);
        }

        if ($hourCount >= $this->maxCommandsPerHour) {
            return response()->json([
                'success' => false,
                'type' => 'error',
                'output' => "Rate limit: máximo {$this->maxCommandsPerHour} comandos por hora. Tente novamente mais tarde.",
            ], 429);
        }

        // Incrementa contadores
        Cache::put($minuteKey, $minuteCount + 1, 60);
        Cache::put($hourKey, $hourCount + 1, 3600);

        return null;
    }

    /**
     * Registra log do comando
     */
    private function logCommand(int $userId, string $command, string $ip, bool $success, ?string $output = null): void
    {
        Log::channel('terminal')->info('Terminal command executed', [
            'user_id' => $userId,
            'ip' => $ip,
            'command' => $command,
            'success' => $success,
            'output_preview' => $output ? substr($output, 0, 500) : null,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Verifica se ferramenta está instalada
     */
    private function isToolInstalled(string $command): bool
    {
        $process = Process::fromShellCommandline("which {$command} 2>/dev/null || command -v {$command} 2>/dev/null");
        $process->setTimeout(5);
        $process->run();
        return $process->isSuccessful() && !empty(trim($process->getOutput()));
    }

    /**
     * Executa um comando do terminal
     */
    public function execute(Request $request): JsonResponse
    {
        $request->validate([
            'command' => 'required|string|max:500',
        ]);

        $user = $request->user();
        $userId = $user->id;
        $ip = $request->ip();
        $fullCommand = trim($request->input('command'));

        // Verificar se usuário tem acesso ao terminal (apenas Full Attack ou admin)
        if ($user->role !== 'admin') {
            $plan = $user->plan;
            $planSlug = $plan ? $plan->slug : 'free';
            
            if ($planSlug !== 'fullattack') {
                $this->logCommand($userId, $fullCommand, $ip, false, 'Plan not allowed');
                return response()->json([
                    'success' => false,
                    'type' => 'error',
                    'output' => "⚠️ Terminal disponível apenas no plano Full Attack.\n\nSeu plano atual: " . ($plan ? $plan->name : 'Pentest') . "\n\nFaça upgrade para ter acesso ao terminal integrado.",
                ], 403);
            }
        }

        // Rate limiting (admin não tem limite)
        if ($user->role !== 'admin') {
            $rateLimitResponse = $this->checkRateLimit($userId);
            if ($rateLimitResponse) {
                $this->logCommand($userId, $fullCommand, $ip, false, 'Rate limited');
                return $rateLimitResponse;
            }
        }
        
        // Verificar tamanho do comando (proteção contra comandos muito grandes)
        if (strlen($fullCommand) > 500) {
            $this->logCommand($userId, $fullCommand, $ip, false, 'Command too long');
            return response()->json([
                'success' => false,
                'type' => 'error',
                'output' => "⚠️ Comando muito longo.\n\nMáximo permitido: 500 caracteres\nSeu comando: " . strlen($fullCommand) . " caracteres",
            ]);
        }
        
        // Parse do comando
        $parts = preg_split('/\s+/', $fullCommand, 2);
        $command = strtolower($parts[0]);
        $args = $parts[1] ?? '';
        
        // Verificar número de argumentos (proteção contra comandos complexos)
        $argCount = substr_count($args, ' ') + (empty($args) ? 0 : 1);
        if ($argCount > 20) {
            $this->logCommand($userId, $fullCommand, $ip, false, 'Too many arguments');
            return response()->json([
                'success' => false,
                'type' => 'error',
                'output' => "⚠️ Muitos argumentos no comando.\n\nMáximo permitido: 20 argumentos\nSeu comando: {$argCount} argumentos",
            ]);
        }
        
        // Verificar se há múltiplos alvos (proteção contra scans massivos)
        $targetCount = preg_match_all('/\b(?:\d{1,3}\.){3}\d{1,3}\b|\b[a-zA-Z0-9][-a-zA-Z0-9]*\.[a-zA-Z]{2,}\b/', $args);
        if ($targetCount > 5) {
            $this->logCommand($userId, $fullCommand, $ip, false, 'Too many targets');
            return response()->json([
                'success' => false,
                'type' => 'error',
                'output' => "⚠️ Muitos alvos no comando.\n\nMáximo permitido: 5 alvos por comando\nSeu comando: {$targetCount} alvos\n\nExecute comandos separados para cada alvo.",
            ]);
        }
        
        // Verificar ranges de IP (proteção contra scans de rede inteira)
        if (preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\/\d{1,2}/', $args, $matches)) {
            // Extrai o CIDR
            preg_match('/\/(\d{1,2})$/', $matches[0], $cidr);
            $cidrValue = (int) ($cidr[1] ?? 32);
            
            // Bloqueia ranges maiores que /24 (mais de 256 hosts)
            if ($cidrValue < 24) {
                $this->logCommand($userId, $fullCommand, $ip, false, 'IP range too large');
                return response()->json([
                    'success' => false,
                    'type' => 'error',
                    'output' => "⚠️ Range de IP muito grande.\n\nMáximo permitido: /24 (256 hosts)\nSeu range: /{$cidrValue}\n\nUse ranges menores para não sobrecarregar o servidor.",
                ]);
            }
        }
        
        // Verificar port ranges muito grandes no nmap
        if ($command === 'nmap' && preg_match('/-p\s*(\d+)-(\d+)/', $args, $portMatch)) {
            $startPort = (int) $portMatch[1];
            $endPort = (int) $portMatch[2];
            $portRange = $endPort - $startPort;
            
            if ($portRange > 1000) {
                $this->logCommand($userId, $fullCommand, $ip, false, 'Port range too large');
                return response()->json([
                    'success' => false,
                    'type' => 'error',
                    'output' => "⚠️ Range de portas muito grande.\n\nMáximo permitido: 1000 portas\nSeu range: {$portRange} portas ({$startPort}-{$endPort})\n\nUse -p 1-1000 ou portas específicas como -p 22,80,443,8080",
                ]);
            }
        }
        
        // Bloquear scan de todas as portas (-p-)
        if ($command === 'nmap' && preg_match('/-p-/', $args)) {
            $this->logCommand($userId, $fullCommand, $ip, false, 'Full port scan blocked');
            return response()->json([
                'success' => false,
                'type' => 'error',
                'output' => "⚠️ Scan de todas as portas não permitido.\n\nO parâmetro -p- escaneia 65535 portas e sobrecarrega o servidor.\n\nUse ranges específicos como -p 1-1000 ou --top-ports 100",
            ]);
        }
        
        // Bloquear nmap com muitas threads
        if ($command === 'nmap' && preg_match('/--min-rate\s*(\d+)/', $args, $rateMatch)) {
            $rate = (int) $rateMatch[1];
            if ($rate > 500) {
                $this->logCommand($userId, $fullCommand, $ip, false, 'Nmap rate too high');
                return response()->json([
                    'success' => false,
                    'type' => 'error',
                    'output' => "⚠️ Taxa de scan muito alta.\n\nMáximo permitido: --min-rate 500\nSua taxa: {$rate}\n\nTaxas altas podem sobrecarregar o servidor e o alvo.",
                ]);
            }
        }
        
        // Limitar threads do gobuster
        if ($command === 'gobuster' && preg_match('/-t\s*(\d+)/', $args, $threadMatch)) {
            $threads = (int) $threadMatch[1];
            if ($threads > 20) {
                $this->logCommand($userId, $fullCommand, $ip, false, 'Gobuster threads too high');
                return response()->json([
                    'success' => false,
                    'type' => 'error',
                    'output' => "⚠️ Muitas threads no gobuster.\n\nMáximo permitido: 20 threads (-t 20)\nSuas threads: {$threads}\n\nUse menos threads para não sobrecarregar.",
                ]);
            }
        }
        
        // Bloquear wordlists muito grandes no gobuster
        if ($command === 'gobuster' && preg_match('/-w\s*(\S+)/', $args, $wordlistMatch)) {
            $wordlist = $wordlistMatch[1];
            // Bloquear wordlists conhecidas por serem muito grandes
            $blockedWordlists = ['rockyou', 'directory-list-2.3-big', 'directory-list-2.3-medium', 'all.txt', 'big.txt'];
            foreach ($blockedWordlists as $blocked) {
                if (stripos($wordlist, $blocked) !== false) {
                    $this->logCommand($userId, $fullCommand, $ip, false, 'Large wordlist blocked');
                    return response()->json([
                        'success' => false,
                        'type' => 'error',
                        'output' => "⚠️ Wordlist muito grande bloqueada.\n\nWordlists grandes podem demorar horas e sobrecarregar o servidor.\n\nUse wordlists menores como:\n- /usr/share/wordlists/dirb/common.txt\n- /usr/share/wordlists/dirb/small.txt",
                    ]);
                }
            }
        }
        
        // Limitar nikto (sempre pesado)
        if ($command === 'nikto') {
            // Verificar se já tem um nikto rodando para este usuário
            $niktoKey = "terminal_nikto:{$userId}";
            if (Cache::has($niktoKey)) {
                return response()->json([
                    'success' => false,
                    'type' => 'warning',
                    'output' => "⚠️ Você já tem um scan nikto em andamento.\n\nAguarde o scan anterior terminar antes de iniciar outro.\nNikto pode demorar vários minutos.",
                ]);
            }
            // Marca que nikto está rodando (expira em 5 minutos)
            Cache::put($niktoKey, true, 300);
        }

        // Verificar se comando está na whitelist
        if (!isset($this->allowedCommands[$command])) {
            $this->logCommand($userId, $fullCommand, $ip, false, 'Command not allowed');
            return response()->json([
                'success' => false,
                'type' => 'error',
                'output' => "Comando não permitido: {$command}\n\nUse 'help' para ver comandos disponíveis.",
            ]);
        }

        $config = $this->allowedCommands[$command];

        // Verificar se ferramenta está instalada
        if (!$this->isToolInstalled($command)) {
            $this->logCommand($userId, $fullCommand, $ip, false, 'Tool not installed');
            return response()->json([
                'success' => false,
                'type' => 'warning',
                'output' => "Ferramenta '{$command}' não está instalada no servidor.\n\nPara instalar: apt install {$command}",
            ]);
        }

        // Verificar se args são necessários
        if ($config['args_required'] && empty($args)) {
            $this->logCommand($userId, $fullCommand, $ip, false, 'Args required');
            return response()->json([
                'success' => false,
                'type' => 'error',
                'output' => "O comando '{$command}' requer argumentos.\nExemplo: {$command} target.com",
            ]);
        }

        // Verificar padrões bloqueados
        foreach ($this->blockedPatterns as $pattern) {
            if (preg_match($pattern, $fullCommand)) {
                $this->logCommand($userId, $fullCommand, $ip, false, 'Blocked pattern detected');
                return response()->json([
                    'success' => false,
                    'type' => 'error',
                    'output' => "Comando bloqueado por razões de segurança.",
                ]);
            }
        }

        // Verificar args bloqueados específicos do comando
        if (isset($config['blocked_args'])) {
            foreach ($config['blocked_args'] as $blocked) {
                if (stripos($args, $blocked) !== false) {
                    $this->logCommand($userId, $fullCommand, $ip, false, "Blocked arg: {$blocked}");
                    return response()->json([
                        'success' => false,
                        'type' => 'error',
                        'output' => "Argumento não permitido: {$blocked}",
                    ]);
                }
            }
        }

        // Adicionar args padrão se configurado
        if (isset($config['default_args']) && !empty($args)) {
            // Para ping, adicionar -c 4 se não tiver -c
            if ($command === 'ping' && strpos($args, '-c') === false && strpos($args, '-n') === false) {
                $args = $config['default_args'] . ' ' . $args;
            }
        }

        // Montar comando final
        $finalCommand = $command . ($args ? ' ' . $args : '');

        try {
            // Executar comando
            $process = Process::fromShellCommandline($finalCommand);
            $process->setTimeout($config['timeout']);
            $process->run();

            $output = $process->getOutput();
            $errorOutput = $process->getErrorOutput();
            
            // Combinar output
            $fullOutput = trim($output . ($errorOutput ? "\n" . $errorOutput : ''));
            
            if (empty($fullOutput)) {
                $fullOutput = "(comando executado sem output)";
            }

            $this->logCommand($userId, $fullCommand, $ip, $process->isSuccessful(), $fullOutput);

            return response()->json([
                'success' => $process->isSuccessful(),
                'type' => $process->isSuccessful() ? 'output' : 'error',
                'output' => $fullOutput,
                'exit_code' => $process->getExitCode(),
            ]);

        } catch (ProcessTimedOutException $e) {
            $this->logCommand($userId, $fullCommand, $ip, false, 'Timeout');
            return response()->json([
                'success' => false,
                'type' => 'warning',
                'output' => "Comando excedeu o tempo limite de {$config['timeout']} segundos.",
            ]);
        } catch (\Exception $e) {
            $this->logCommand($userId, $fullCommand, $ip, false, $e->getMessage());
            return response()->json([
                'success' => false,
                'type' => 'error',
                'output' => "Erro ao executar comando: " . $e->getMessage(),
            ]);
        }
    }

    /**
     * Lista comandos disponíveis
     */
    public function commands(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Verificar se usuário tem acesso ao terminal (apenas Full Attack ou admin)
        if ($user->role !== 'admin') {
            $plan = $user->plan;
            $planSlug = $plan ? $plan->slug : 'free';
            
            if ($planSlug !== 'fullattack') {
                return response()->json([
                    'success' => false,
                    'error' => 'Terminal disponível apenas no plano Full Attack',
                    'plan_required' => 'fullattack',
                ], 403);
            }
        }
        
        $commands = [];
        foreach ($this->allowedCommands as $cmd => $config) {
            $installed = $this->isToolInstalled($cmd);
            $commands[$cmd] = [
                'description' => $config['description'] ?? '',
                'timeout' => $config['timeout'],
                'requires_args' => $config['args_required'],
                'installed' => $installed,
            ];
        }

        return response()->json([
            'success' => true,
            'commands' => $commands,
            'rate_limit' => [
                'per_minute' => $this->maxCommandsPerMinute,
                'per_hour' => $this->maxCommandsPerHour,
            ],
        ]);
    }
}
