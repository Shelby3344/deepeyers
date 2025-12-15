<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class RateLimitTest extends TestCase
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

        config([
            'deepseek.rate_limit.enabled' => true,
            'deepseek.rate_limit.max_requests_per_minute' => 3,
            'deepseek.rate_limit.max_requests_per_hour' => 10,
            'deepseek.rate_limit.max_requests_per_day' => 20,
        ]);
    }

    protected function tearDown(): void
    {
        RateLimiter::clear("ai_minute:{$this->user->id}");
        RateLimiter::clear("ai_hour:{$this->user->id}");

        parent::tearDown();
    }

    public function test_rate_limit_headers_are_present(): void
    {
        $response = $this->withToken($this->token)
            ->getJson('/api/chat/sessions');

        $response->assertHeader('X-RateLimit-Limit');
        $response->assertHeader('X-RateLimit-Remaining');
        $response->assertHeader('X-RateLimit-Daily-Remaining');
    }

    public function test_rate_limit_blocks_after_max_requests(): void
    {
        // Make requests up to the limit
        for ($i = 0; $i < 3; $i++) {
            $response = $this->withToken($this->token)
                ->getJson('/api/chat/sessions');

            $response->assertStatus(200);
        }

        // Next request should be blocked
        $response = $this->withToken($this->token)
            ->getJson('/api/chat/sessions');

        $response->assertStatus(429)
            ->assertJsonStructure([
                'error',
                'message',
                'retry_after',
            ]);
    }

    public function test_banned_user_cannot_make_requests(): void
    {
        $this->user->update(['is_banned' => true]);

        $response = $this->withToken($this->token)
            ->getJson('/api/chat/sessions');

        $response->assertStatus(403)
            ->assertJson([
                'error' => 'Forbidden',
            ]);
    }
}
