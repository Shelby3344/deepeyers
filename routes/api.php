<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\TerminalController;
use App\Http\Controllers\Api\ChecklistController;
use App\Http\Controllers\ProfileController;
use App\Models\Plan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| DeepEyes - Pentest AI System
|
*/

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('api.auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('api.auth.login');
});

// Public checklist view (no auth required)
Route::get('/checklists/public/{token}', [ChecklistController::class, 'showPublic'])->name('api.checklists.public');

// Protected routes
Route::middleware(['auth:sanctum', 'ensure.not.banned'])->group(function () {

    // Auth
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
        Route::get('/me', [AuthController::class, 'me'])->name('api.auth.me');
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('api.profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('api.profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('api.profile.avatar.upload');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('api.profile.avatar.delete');

    // Terminal - comandos de pentest
    Route::prefix('terminal')->group(function () {
        Route::post('/execute', [TerminalController::class, 'execute'])->name('api.terminal.execute');
        Route::get('/commands', [TerminalController::class, 'commands'])->name('api.terminal.commands');
    });

    // Checklists
    Route::prefix('checklists')->group(function () {
        Route::get('/', [ChecklistController::class, 'index'])->name('api.checklists.index');
        Route::post('/', [ChecklistController::class, 'store'])->name('api.checklists.store');
        Route::get('/{id}', [ChecklistController::class, 'show'])->name('api.checklists.show');
        Route::put('/{id}', [ChecklistController::class, 'update'])->name('api.checklists.update');
        Route::delete('/{id}', [ChecklistController::class, 'destroy'])->name('api.checklists.destroy');
        Route::post('/{id}/share', [ChecklistController::class, 'share'])->name('api.checklists.share');
        Route::delete('/{id}/share', [ChecklistController::class, 'unshare'])->name('api.checklists.unshare');
    });

    // Plans
    Route::get('/plans', function () {
        return response()->json([
            'data' => Plan::orderBy('price')->get()->map(function ($plan) {
                return [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'slug' => $plan->slug,
                    'price' => $plan->price,
                    'formatted_price' => $plan->formatted_price,
                    'billing_cycle' => $plan->billing_cycle,
                    'requests_per_day' => $plan->requests_per_day,
                    'requests_per_month' => $plan->requests_per_month,
                    'features' => $plan->features,
                    'allowed_profiles' => $plan->allowed_profiles,
                ];
            })
        ]);
    })->name('api.plans.index');

    // Chat - with AI rate limiting
    Route::prefix('chat')->middleware('rate.limit.ai')->group(function () {
        // Sessions CRUD
        Route::get('/sessions', [ChatController::class, 'index'])->name('api.chat.sessions.index');
        Route::post('/sessions', [ChatController::class, 'store'])->name('api.chat.sessions.store');
        Route::get('/sessions/{sessionId}', [ChatController::class, 'show'])->name('api.chat.sessions.show');
        Route::put('/sessions/{sessionId}', [ChatController::class, 'update'])->name('api.chat.sessions.update');
        Route::delete('/sessions/{sessionId}', [ChatController::class, 'destroy'])->name('api.chat.sessions.destroy');

        // Messaging
        Route::post('/sessions/{sessionId}/messages', [ChatController::class, 'sendMessage'])->name('api.chat.message');
        Route::post('/sessions/{sessionId}/messages/stream', [ChatController::class, 'sendMessageStream'])->name('api.chat.message.stream');
        Route::post('/sessions/{sessionId}/messages/async', [ChatController::class, 'sendMessageAsync'])->name('api.chat.message.async');
        Route::get('/sessions/{sessionId}/status', [ChatController::class, 'checkStatus'])->name('api.chat.status');

        // Profiles
        Route::get('/profiles', [ChatController::class, 'profiles'])->name('api.chat.profiles');
    });

    // Admin routes (admin only)
    Route::prefix('admin')->middleware('admin')->group(function () {
        // Dashboard
        Route::get('/stats', [AdminController::class, 'stats'])->name('api.admin.stats');

        // Users
        Route::get('/users', [AdminController::class, 'users'])->name('api.admin.users');
        Route::put('/users/{userId}', [AdminController::class, 'updateUser'])->name('api.admin.users.update');
        Route::delete('/users/{userId}', [AdminController::class, 'deleteUser'])->name('api.admin.users.delete');
        Route::post('/users/delete-bulk', [AdminController::class, 'deleteUsers'])->name('api.admin.users.delete.bulk');
        Route::post('/users/{userId}/ban', [AdminController::class, 'banUser'])->name('api.admin.users.ban');
        Route::post('/users/{userId}/unban', [AdminController::class, 'unbanUser'])->name('api.admin.users.unban');

        // Sessions
        Route::get('/sessions', [AdminController::class, 'sessions'])->name('api.admin.sessions');
        Route::get('/sessions/{sessionId}/view', [AdminController::class, 'viewSession'])->name('api.admin.sessions.view');
        Route::delete('/sessions/{sessionId}', [AdminController::class, 'deleteSession'])->name('api.admin.sessions.delete');
        Route::post('/sessions/delete-bulk', [AdminController::class, 'deleteSessionsBulk'])->name('api.admin.sessions.delete.bulk');
        Route::delete('/sessions/clear', [AdminController::class, 'clearSessions'])->name('api.admin.sessions.clear');

        // Plans
        Route::get('/plans', [AdminController::class, 'plans'])->name('api.admin.plans');
    });
});

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toIso8601String(),
        'version' => '1.0.0',
    ]);
})->name('api.health');
