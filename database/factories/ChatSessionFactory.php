<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ChatSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ChatSession>
 */
class ChatSessionFactory extends Factory
{
    protected $model = ChatSession::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'user_id' => User::factory(),
            'title' => fake()->sentence(3),
            'profile' => 'pentest',
            'is_active' => true,
            'total_tokens' => 0,
            'message_count' => 0,
            'metadata' => null,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function redteam(): static
    {
        return $this->state(fn(array $attributes) => [
            'profile' => 'redteam',
        ]);
    }
}
