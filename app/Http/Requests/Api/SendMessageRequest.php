<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Normalizer;

/**
 * Request defensivo para envio de mensagens
 * 
 * Implementa:
 * - Validação rigorosa de input
 * - Detecção de padrões suspeitos (SQLi, XSS, RCE)
 * - Normalização e sanitização
 */
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
                'max:100000', // 100KB limite
                function ($attribute, $value, $fail) {
                    if ($this->containsSuspiciousPatterns($value)) {
                        $fail('Invalid message content.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' => 'A mensagem é obrigatória.',
            'message.min' => 'A mensagem não pode estar vazia.',
            'message.max' => 'A mensagem é muito longa.',
        ];
    }

    /**
     * Prepara e normaliza os dados antes da validação
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('message')) {
            $this->merge([
                'message' => $this->normalizeInput($this->input('message')),
            ]);
        }
    }

    /**
     * Normaliza input removendo caracteres perigosos
     */
    private function normalizeInput(string $input): string
    {
        // Remove null bytes (poison null byte attack)
        $input = str_replace("\0", '', $input);
        
        // Normaliza unicode para forma canônica
        if (class_exists('Normalizer')) {
            $normalized = Normalizer::normalize($input, Normalizer::FORM_C);
            if ($normalized !== false) {
                $input = $normalized;
            }
        }
        
        // Remove caracteres de controle ASCII (exceto tab, newline, carriage return)
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input) ?? $input;
        
        return trim($input);
    }

    /**
     * Detecta padrões suspeitos de ataque
     * 
     * NOTA: Para aplicação de pentest, alguns padrões são legítimos
     * Então apenas logamos e não bloqueamos certos casos
     */
    private function containsSuspiciousPatterns(string $value): bool
    {
        // Padrões críticos que SEMPRE devem ser bloqueados
        $criticalPatterns = [
            // Server-Side Template Injection
            '/\{\{.*\}\}/s' => 'ssti_attempt',
            '/\{%.*%\}/s' => 'ssti_jinja',
            
            // PHP code injection
            '/<\?php/i' => 'php_injection',
            '/<\?=/i' => 'php_short_tag',
            
            // Null byte injection
            '/\x00/' => 'null_byte',
            
            // Unicode bypass attempts
            '/\xef\xbb\xbf/' => 'bom_injection', // UTF-8 BOM
        ];

        // Padrões que apenas logamos (podem ser código legítimo sendo analisado)
        $monitorPatterns = [
            // SQL patterns
            '/(\bunion\b.*\bselect\b)/i' => 'possible_sqli_union',
            '/(\bor\b\s+[\'\"]?1[\'\"]?\s*=\s*[\'\"]?1)/i' => 'possible_sqli_or',
            
            // XSS patterns  
            '/<script\b[^>]*>/i' => 'possible_xss_script',
            '/javascript:/i' => 'possible_xss_js_uri',
            '/on(error|load|click|mouse|focus|blur)\s*=/i' => 'possible_xss_event',
            
            // Command injection (em contexto de análise pode ser código legítimo)
            '/`[^`]+`/' => 'possible_cmd_backtick',
        ];

        // Verifica padrões críticos - BLOQUEIA
        foreach ($criticalPatterns as $pattern => $type) {
            if (preg_match($pattern, $value)) {
                Log::channel('security')->error('Critical pattern BLOCKED', [
                    'pattern_type' => $type,
                    'ip' => request()->ip(),
                    'user_id' => auth()->id(),
                    'snippet' => substr($value, 0, 200),
                ]);
                return true; // Bloqueia
            }
        }

        // Verifica padrões de monitoramento - apenas LOGA
        foreach ($monitorPatterns as $pattern => $type) {
            if (preg_match($pattern, $value)) {
                Log::channel('security')->info('Monitored pattern detected', [
                    'pattern_type' => $type,
                    'ip' => request()->ip(),
                    'user_id' => auth()->id(),
                ]);
                // Não bloqueia - pode ser código legítimo para análise
            }
        }

        return false;
    }
}
