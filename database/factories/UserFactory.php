<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;
    protected static ?string $password = null;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'user',
            'is_banned' => false,
            'banned_at' => null,
            'ban_reason' => null,
            'daily_requests' => 0,
            'daily_requests_date' => null,
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'admin',
        ]);
    }

    public function redteam(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'redteam',
        ]);
    }

    public function analyst(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'analyst',
        ]);
    }

    public function banned(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_banned' => true,
            'banned_at' => now(),
            'ban_reason' => 'Violation of terms',
        ]);
    }
}
