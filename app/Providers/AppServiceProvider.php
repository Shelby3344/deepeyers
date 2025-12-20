<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\ChatSession;
use App\Policies\ChatSessionPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register policies
        Gate::policy(ChatSession::class, ChatSessionPolicy::class);
    }
}
