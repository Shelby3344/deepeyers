<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\ChatSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ChatControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => 'user',
            'is_banned' => false,
        ]);

        $this->token = $this->user->createToken('test')->plainTextToken;

        // Configure DeepSeek
        config([
            'deepseek.api_key' => 'test-key',
            'deepseek.system_prompts' => [
                'pentest' => 'You are SentinelAI',
            ],
            'deepseek.allowed_profiles' => [
                'user' => ['pentest'],
                'admin' => ['pentest', 'redteam'],
            ],
            'deepseek.rate_limit.enabled' => false,
        ]);
    }

    public function test_can_create_session(): void
    {
        $response = $this->withToken($this->token)
            ->postJson('/api/chat/sessions', [
                'title' => 'Security Audit',
                'profile' => 'pentest',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => ['id', 'title', 'profile', 'is_active', 'created_at'],
            ]);

        $this->assertDatabaseHas('chat_sessions', [
            'user_id' => $this->user->id,
            'title' => 'Security Audit',
            'profile' => 'pentest',
        ]);
    }

    public function test_can_list_sessions(): void
    {
        ChatSession::factory()->count(3)->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->withToken($this->token)
            ->getJson('/api/chat/sessions');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_view_session(): void
    {
        $session = ChatSession::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->withToken($this->token)
            ->getJson("/api/chat/sessions/{$session->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'session',
                    'messages',
                ],
            ]);
    }

    public function test_cannot_view_other_users_session(): void
    {
        $otherUser = User::factory()->create();
        $session = ChatSession::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->withToken($this->token)
            ->getJson("/api/chat/sessions/{$session->id}");

        $response->assertStatus(403);
    }

    public function test_can_send_message(): void
    {
        Http::fake([
            '*' => Http::response([
                'id' => 'chatcmpl-123',
                'model' => 'deepseek-chat',
                'choices' => [
                    [
                        'message' => [
                            'role' => 'assistant',
                            'content' => 'SQL Injection analysis...',
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

        $session = ChatSession::factory()->create([
            'user_id' => $this->user->id,
            'profile' => 'pentest',
        ]);

        $response = $this->withToken($this->token)
            ->postJson("/api/chat/sessions/{$session->id}/messages", [
                'message' => 'Explain SQL Injection',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'message' => ['id', 'role', 'content', 'created_at'],
                    'usage' => ['prompt_tokens', 'completion_tokens', 'total_tokens'],
                ],
            ]);

        $this->assertDatabaseHas('chat_messages', [
            'session_id' => $session->id,
            'role' => 'user',
            'content' => 'Explain SQL Injection',
        ]);

        $this->assertDatabaseHas('chat_messages', [
            'session_id' => $session->id,
            'role' => 'assistant',
        ]);
    }

    public function test_can_delete_session(): void
    {
        $session = ChatSession::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->withToken($this->token)
            ->deleteJson("/api/chat/sessions/{$session->id}");

        $response->assertStatus(200);

        $this->assertSoftDeleted('chat_sessions', [
            'id' => $session->id,
        ]);
    }

    public function test_banned_user_cannot_access(): void
    {
        $this->user->update(['is_banned' => true]);

        $response = $this->withToken($this->token)
            ->getJson('/api/chat/sessions');

        $response->assertStatus(403);
    }

    public function test_unauthenticated_request_returns_401(): void
    {
        $response = $this->getJson('/api/chat/sessions');

        $response->assertStatus(401);
    }
}
