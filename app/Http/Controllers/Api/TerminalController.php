<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

class TerminalController extends Controller
{
    /**
     * Comandos permitidos (whitelist) com suas configurações
     */
    private array $allowedCommands = [
        // Reconhecimento DNS/WHOIS
        'whois' => ['timeout' => 30, 'args_required' => true],
        'dig' => ['timeout' => 15, 'args_required' => true],
        'nslookup' => ['timeout' => 15, 'args_required' => true],
        'host' => ['timeout' => 15, 'args_required' => true],
        
        // Rede básica
        'ping' => ['timeout' => 10, 'args_required' => true, 'default_args' => '-c 4'],
        'traceroute' => ['timeout' => 30, 'args_required' => true],
        'tracert' => ['timeout' => 30, 'args_required' => true], // Windows
        
        // HTTP/Web
        'curl' => ['timeout' => 30, 'args_required' => true, 'blocked_args' => ['-o', '--output', '-O', '>', '>>', '|']],
        'wget' => ['timeout' => 30, 'args_required' => true, 'allowed_args' => ['-q', '-O', '-', '--spider', '-S', '--server-response']],
        
        // Scanner de portas (se instalado)
        'nmap' => ['timeout' => 120, 'args_required' => true, 'blocked_args' => ['-oN', '-oX', '-oG', '-oA', '>', '>>', '|']],
        
        // Ferramentas de pentest (se instaladas)
        'nikto' => ['timeout' => 300, 'args_required' => true],
        'gobuster' => ['timeout' => 300, 'args_required' => true],
        'dirb' => ['timeout' => 300, 'args_required' => true],
        'wpscan' => ['timeout' => 300, 'args_required' => true],
        'subfinder' => ['timeout' => 120, 'args_required' => true],
        'httpx' => ['timeout' => 60, 'args_required' => true],
        'nuclei' => ['timeout' => 300, 'args_required' => true],
        
        // Utilitários seguros
        'echo' => ['timeout' => 5, 'args_required' => false],
        'date' => ['timeout' => 5, 'args_required' => false],
        'whoami' => ['timeout' => 5, 'args_required' => false],
        'id' => ['timeout' => 5, 'args_required' => false],
        'uname' => ['timeout' => 5, 'args_required' => false],
        'pwd' => ['timeout' => 5, 'args_required' => false],
        'hostname' => ['timeout' => 5, 'args_required' => false],
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
     * Executa um comando do terminal
     */
    public function execute(Request $request): JsonResponse
    {
        $request->validate([
            'command' => 'required|string|max:500',
        ]);

        $fullCommand = trim($request->input('command'));
        
        // Parse do comando
        $parts = preg_split('/\s+/', $fullCommand, 2);
        $command = strtolower($parts[0]);
        $args = $parts[1] ?? '';

        // Verificar se comando está na whitelist
        if (!isset($this->allowedCommands[$command])) {
            return response()->json([
                'success' => false,
                'type' => 'error',
                'output' => "Comando não permitido: {$command}\n\nComandos disponíveis: " . implode(', ', array_keys($this->allowedCommands)),
            ]);
        }

        $config = $this->allowedCommands[$command];

        // Verificar se args são necessários
        if ($config['args_required'] && empty($args)) {
            return response()->json([
                'success' => false,
                'type' => 'error',
                'output' => "O comando '{$command}' requer argumentos.\nExemplo: {$command} target.com",
            ]);
        }

        // Verificar padrões bloqueados
        foreach ($this->blockedPatterns as $pattern) {
            if (preg_match($pattern, $fullCommand)) {
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

            return response()->json([
                'success' => $process->isSuccessful(),
                'type' => $process->isSuccessful() ? 'output' : 'error',
                'output' => $fullOutput,
                'exit_code' => $process->getExitCode(),
            ]);

        } catch (ProcessTimedOutException $e) {
            return response()->json([
                'success' => false,
                'type' => 'warning',
                'output' => "Comando excedeu o tempo limite de {$config['timeout']} segundos.",
            ]);
        } catch (\Exception $e) {
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
    public function commands(): JsonResponse
    {
        $commands = [];
        foreach ($this->allowedCommands as $cmd => $config) {
            $commands[$cmd] = [
                'timeout' => $config['timeout'],
                'requires_args' => $config['args_required'],
            ];
        }

        return response()->json([
            'success' => true,
            'commands' => $commands,
        ]);
    }
}
