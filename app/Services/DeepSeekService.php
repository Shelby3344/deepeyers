<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\DeepSeekResponseDTO;
use App\Exceptions\DeepSeekException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeepSeekService
{
    private string $apiKey;
    private string $endpoint;
    private string $model;
    private int $timeout;
    private int $connectTimeout;
    private int $retryTimes;
    private int $retrySleep;

    public function __construct()
    {
        $this->apiKey = (string) config('deepseek.api_key', '');
        $this->endpoint = (string) config('deepseek.endpoint', 'https://api.deepseek.com/chat/completions');
        $this->model = (string) config('deepseek.model', 'deepseek-chat');
        $this->timeout = (int) config('deepseek.timeout', 120);
        $this->connectTimeout = (int) config('deepseek.connect_timeout', 10);
        $this->retryTimes = (int) config('deepseek.retry_times', 3);
        $this->retrySleep = (int) config('deepseek.retry_sleep', 500);

        if (empty($this->apiKey)) {
            throw new DeepSeekException('DeepSeek API key not configured');
        }
    }

    /**
     * Send a chat completion request to DeepSeek API
     */
    public function chat(array $messages, array $options = []): DeepSeekResponseDTO
    {
        $payload = $this->buildPayload($messages, $options);

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout($this->timeout)
                ->connectTimeout($this->connectTimeout)
                ->retry($this->retryTimes, $this->retrySleep, function ($exception) {
                    return $this->shouldRetry($exception);
                })
                ->post($this->endpoint, $payload);

            if ($response->failed()) {
                $this->handleErrorResponse($response);
            }

            return DeepSeekResponseDTO::fromApiResponse($response->json());

        } catch (ConnectionException $e) {
            Log::error('DeepSeek API connection failed', [
                'error' => $e->getMessage(),
            ]);
            throw new DeepSeekException('Failed to connect to DeepSeek API', 503, $e);

        } catch (RequestException $e) {
            Log::error('DeepSeek API request failed', [
                'status' => $e->response?->status(),
                'body' => $e->response?->body(),
            ]);
            throw new DeepSeekException(
                'DeepSeek API request failed: ' . $e->getMessage(),
                $e->response?->status() ?? 500,
                $e
            );
        }
    }

    /**
     * Build chat with context (system prompt + history + new message)
     */
    public function chatWithContext(
        string $systemPrompt,
        array $contextMessages,
        string $userMessage,
        array $options = []
    ): DeepSeekResponseDTO {
        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        foreach ($contextMessages as $msg) {
            $messages[] = [
                'role' => $msg['role'],
                'content' => $msg['content'],
            ];
        }

        $messages[] = ['role' => 'user', 'content' => $userMessage];

        return $this->chat($messages, $options);
    }

    /**
     * Get system prompt by profile
     */
    public function getSystemPrompt(string $profile): string
    {
        $prompts = config('deepseek.system_prompts', []);

        if (!isset($prompts[$profile])) {
            throw new DeepSeekException("Unknown profile: {$profile}");
        }

        return $prompts[$profile];
    }

    /**
     * Validate profile access for user
     */
    public function validateProfileAccess(string $userRole, string $profile): bool
    {
        $allowedProfiles = config('deepseek.allowed_profiles.' . $userRole, []);
        return in_array($profile, $allowedProfiles);
    }

    /**
     * Build request payload
     */
    private function buildPayload(array $messages, array $options = []): array
    {
        return [
            'model' => $options['model'] ?? $this->model,
            'messages' => $messages,
            'temperature' => (float) ($options['temperature'] ?? config('deepseek.temperature', 0.2)),
            'max_tokens' => (int) ($options['max_tokens'] ?? config('deepseek.max_tokens', 4096)),
            'top_p' => (float) ($options['top_p'] ?? config('deepseek.top_p', 0.95)),
            'stream' => false,
        ];
    }

    /**
     * Get request headers - API key is NEVER exposed
     * Supports both DeepSeek direct and OpenRouter
     */
    private function getHeaders(): array
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Add OpenRouter specific headers if using OpenRouter
        if (str_contains($this->endpoint, 'openrouter.ai')) {
            $headers['HTTP-Referer'] = config('app.url', 'http://localhost');
            $headers['X-Title'] = 'SentinelAI Pentest Assistant';
        }

        return $headers;
    }

    /**
     * Determine if request should be retried
     */
    private function shouldRetry(\Throwable $exception): bool
    {
        if ($exception instanceof ConnectionException) {
            return true;
        }

        if ($exception instanceof RequestException) {
            $status = $exception->response?->status();
            return in_array($status, [408, 429, 500, 502, 503, 504]);
        }

        return false;
    }

    /**
     * Handle error responses from API
     */
    private function handleErrorResponse($response): void
    {
        $status = $response->status();
        $body = $response->json();

        $message = $body['error']['message'] ?? 'Unknown API error';

        Log::error('DeepSeek API error', [
            'status' => $status,
            'message' => $message,
            'body' => $body,
        ]);

        match ($status) {
            401 => throw new DeepSeekException('Invalid API key', 401),
            429 => throw new DeepSeekException('Rate limit exceeded', 429),
            400 => throw new DeepSeekException("Bad request: {$message}", 400),
            default => throw new DeepSeekException("API error: {$message}", $status),
        };
    }
    
    /**
     * Send a streaming chat completion request to DeepSeek API
     * OTIMIZADO para respostas ultra-rápidas com streaming real
     */
    public function chatStream(array $messages, array $options = []): \Generator
    {
        $payload = $this->buildPayload($messages, $options);
        $payload['stream'] = true;

        $ch = curl_init();
        
        // Buffer para processar chunks parciais
        $buffer = '';
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->endpoint,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json',
                'Accept: text/event-stream',
            ],
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_CONNECTTIMEOUT => 5, // Conexão rápida
            CURLOPT_TCP_NODELAY => true, // Desabilita Nagle para baixa latência
            CURLOPT_BUFFERSIZE => 128, // Buffer pequeno para respostas rápidas
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        ]);
        
        // Array para coletar chunks via callback
        $chunks = [];
        $done = false;
        
        curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $rawData) use (&$buffer, &$chunks, &$done) {
            $buffer .= $rawData;
            
            // Processa linhas completas
            while (($pos = strpos($buffer, "\n")) !== false) {
                $line = trim(substr($buffer, 0, $pos));
                $buffer = substr($buffer, $pos + 1);
                
                if (empty($line)) continue;
                
                if ($line === 'data: [DONE]') {
                    $done = true;
                    continue;
                }
                
                if (str_starts_with($line, 'data: ')) {
                    $json = substr($line, 6);
                    $data = json_decode($json, true);
                    
                    if (isset($data['choices'][0]['delta']['content'])) {
                        $chunks[] = $data['choices'][0]['delta']['content'];
                    }
                }
            }
            
            return strlen($rawData);
        });
        
        // Executa em modo não bloqueante com multi_exec para máxima velocidade
        $mh = curl_multi_init();
        curl_multi_add_handle($mh, $ch);
        
        $active = null;
        $lastYieldedIndex = 0;
        
        do {
            $status = curl_multi_exec($mh, $active);
            
            // Yield chunks assim que chegam (streaming real!)
            while ($lastYieldedIndex < count($chunks)) {
                yield $chunks[$lastYieldedIndex];
                $lastYieldedIndex++;
            }
            
            if ($active) {
                // Aguarda brevemente por mais dados (1ms para máxima responsividade)
                curl_multi_select($mh, 0.001);
            }
        } while ($active && $status === CURLM_OK);
        
        // Yield chunks restantes
        while ($lastYieldedIndex < count($chunks)) {
            yield $chunks[$lastYieldedIndex];
            $lastYieldedIndex++;
        }
        
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_multi_remove_handle($mh, $ch);
        curl_multi_close($mh);
        curl_close($ch);
        
        if ($httpCode !== 200 && $httpCode !== 0) {
            throw new DeepSeekException('API error: HTTP ' . $httpCode, $httpCode);
        }
    }
    
    /**
     * Stream chat with context (system prompt + history + new message)
     */
    public function chatStreamWithContext(
        string $systemPrompt,
        array $contextMessages,
        string $userMessage,
        array $options = []
    ): \Generator {
        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        foreach ($contextMessages as $msg) {
            $messages[] = [
                'role' => $msg['role'],
                'content' => $msg['content'],
            ];
        }

        $messages[] = ['role' => 'user', 'content' => $userMessage];

        yield from $this->chatStream($messages, $options);
    }
}
