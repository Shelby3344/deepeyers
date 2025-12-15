<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ChatMessage>
 */
class ChatMessageFactory extends Factory
{
    protected $model = ChatMessage::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'session_id' => ChatSession::factory(),
            'role' => fake()->randomElement(['user', 'assistant']),
            'content' => fake()->paragraph(),
            'tokens' => fake()->numberBetween(10, 500),
            'prompt_tokens' => null,
            'completion_tokens' => null,
            'model' => 'deepseek-chat',
            'metadata' => null,
            'is_flagged' => false,
        ];
    }

    public function user(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'user',
        ]);
    }

    public function assistant(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'assistant',
        ]);
    }

    public function flagged(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_flagged' => true,
        ]);
    }
}
