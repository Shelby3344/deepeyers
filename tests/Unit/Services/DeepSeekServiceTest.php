<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\DTO\DeepSeekResponseDTO;
use App\Exceptions\DeepSeekException;
use App\Services\DeepSeekService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DeepSeekServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('deepseek.api_key', 'test-api-key');
        Config::set('deepseek.endpoint', 'https://api.deepseek.com/chat/completions');
        Config::set('deepseek.model', 'deepseek-chat');
        Config::set('deepseek.timeout', 30);
        Config::set('deepseek.connect_timeout', 5);
        Config::set('deepseek.retry_times', 1);
        Config::set('deepseek.temperature', 0.2);
        Config::set('deepseek.max_tokens', 4096);
        Config::set('deepseek.system_prompts', [
            'pentest' => 'You are SentinelAI',
            'redteam' => 'You are BlackSentinel',
        ]);
    }

    public function test_chat_returns_valid_response(): void
    {
        Http::fake([
            'api.deepseek.com/*' => Http::response([
                'id' => 'chatcmpl-123',
                'model' => 'deepseek-chat',
                'choices' => [
                    [
                        'message' => [
                            'role' => 'assistant',
                            'content' => 'This is a test response about SQL Injection.',
                        ],
                        'finish_reason' => 'stop',
                    ],
                ],
                'usage' => [
                    'prompt_tokens' => 50,
                    'completion_tokens' => 100,
                    'total_tokens' => 150,
                ],
            ], 200),
        ]);

        $service = new DeepSeekService();

        $response = $service->chat([
            ['role' => 'system', 'content' => 'You are SentinelAI'],
            ['role' => 'user', 'content' => 'Explain SQL Injection'],
        ]);

        $this->assertInstanceOf(DeepSeekResponseDTO::class, $response);
        $this->assertStringContainsString('SQL Injection', $response->content);
        $this->assertEquals(150, $response->totalTokens);
        $this->assertEquals('stop', $response->finishReason);
    }

    public function test_chat_handles_api_error(): void
    {
        Http::fake([
            'api.deepseek.com/*' => Http::response([
                'error' => [
                    'message' => 'Rate limit exceeded',
                    'type' => 'rate_limit_error',
                ],
            ], 429),
        ]);

        $service = new DeepSeekService();

        $this->expectException(DeepSeekException::class);
        $this->expectExceptionCode(429);

        $service->chat([
            ['role' => 'user', 'content' => 'Test'],
        ]);
    }

    public function test_chat_handles_unauthorized(): void
    {
        Http::fake([
            'api.deepseek.com/*' => Http::response([
                'error' => [
                    'message' => 'Invalid API key',
                ],
            ], 401),
        ]);

        $service = new DeepSeekService();

        $this->expectException(DeepSeekException::class);
        $this->expectExceptionCode(401);

        $service->chat([
            ['role' => 'user', 'content' => 'Test'],
        ]);
    }

    public function test_get_system_prompt_returns_correct_prompt(): void
    {
        $service = new DeepSeekService();

        $prompt = $service->getSystemPrompt('pentest');
        $this->assertEquals('You are SentinelAI', $prompt);

        $prompt = $service->getSystemPrompt('redteam');
        $this->assertEquals('You are BlackSentinel', $prompt);
    }

    public function test_get_system_prompt_throws_for_unknown_profile(): void
    {
        $service = new DeepSeekService();

        $this->expectException(DeepSeekException::class);

        $service->getSystemPrompt('unknown');
    }

    public function test_validate_profile_access(): void
    {
        Config::set('deepseek.allowed_profiles', [
            'user' => ['pentest'],
            'admin' => ['pentest', 'redteam'],
        ]);

        $service = new DeepSeekService();

        $this->assertTrue($service->validateProfileAccess('user', 'pentest'));
        $this->assertFalse($service->validateProfileAccess('user', 'redteam'));
        $this->assertTrue($service->validateProfileAccess('admin', 'redteam'));
    }

    public function test_chat_with_context(): void
    {
        Http::fake([
            'api.deepseek.com/*' => Http::response([
                'id' => 'chatcmpl-456',
                'model' => 'deepseek-chat',
                'choices' => [
                    [
                        'message' => [
                            'role' => 'assistant',
                            'content' => 'Based on our previous discussion...',
                        ],
                        'finish_reason' => 'stop',
                    ],
                ],
                'usage' => [
                    'prompt_tokens' => 200,
                    'completion_tokens' => 50,
                    'total_tokens' => 250,
                ],
            ], 200),
        ]);

        $service = new DeepSeekService();

        $contextMessages = [
            ['role' => 'user', 'content' => 'Previous question'],
            ['role' => 'assistant', 'content' => 'Previous answer'],
        ];

        $response = $service->chatWithContext(
            'System prompt',
            $contextMessages,
            'New question'
        );

        $this->assertInstanceOf(DeepSeekResponseDTO::class, $response);
        $this->assertEquals(250, $response->totalTokens);

        // Verify the request was made with correct structure
        Http::assertSent(function ($request) {
            $body = $request->data();
            return count($body['messages']) === 4 // system + 2 context + user
                && $body['messages'][0]['role'] === 'system'
                && $body['messages'][3]['role'] === 'user'
                && $body['messages'][3]['content'] === 'New question';
        });
    }
}
