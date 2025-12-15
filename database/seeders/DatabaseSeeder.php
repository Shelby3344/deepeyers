<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@olhodedeus.local',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create redteam user
        User::create([
            'name' => 'Red Team',
            'email' => 'redteam@olhodedeus.local',
            'password' => Hash::make('password'),
            'role' => 'redteam',
            'email_verified_at' => now(),
        ]);

        // Create analyst user
        User::create([
            'name' => 'Analyst',
            'email' => 'analyst@olhodedeus.local',
            'password' => Hash::make('password'),
            'role' => 'analyst',
            'email_verified_at' => now(),
        ]);

        // Create regular user
        User::create([
            'name' => 'User',
            'email' => 'user@olhodedeus.local',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);
    }
}
