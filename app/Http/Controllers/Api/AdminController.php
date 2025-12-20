<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ChatSession;
use App\Models\ChatMessage;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Lista todos os usuários (com cache de 60 segundos)
     */
    public function users(Request $request): JsonResponse
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 50);
        $cacheKey = "admin_users_page_{$page}_per_{$perPage}";
        
        $users = Cache::remember($cacheKey, 60, function () use ($perPage) {
            return User::select(['id', 'name', 'email', 'role', 'avatar', 'is_banned', 'plan_id', 'created_at'])
                ->with(['plan:id,name'])
                ->orderByDesc('created_at')
                ->paginate($perPage);
        });

        return response()->json([
            'data' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'avatar' => $user->avatar_url,
                    'is_banned' => (bool) $user->is_banned,
                    'plan' => $user->plan ? [
                        'id' => $user->plan->id,
                        'name' => $user->plan->name,
                    ] : null,
                    'created_at' => $user->created_at,
                ];
            }),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ]
        ]);
    }

    /**
     * Atualiza um usuário
     */
    public function updateUser(Request $request, string $userId): JsonResponse
    {
        $user = User::findOrFail($userId);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'role' => 'sometimes|in:user,analyst,redteam,admin',
            'password' => 'sometimes|string|min:6',
            'plan_id' => 'sometimes|exists:plans,id',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        Log::info('Atualizando usuário', ['user_id' => $userId, 'data' => $validated]);
        
        $user->update($validated);
        
        // Limpa cache de usuários
        Cache::forget('admin_users_page_1_per_50');
        Cache::forget('admin_stats');

        return response()->json([
            'message' => 'Usuário atualizado com sucesso',
            'user' => $user->fresh()->load('plan'),
        ]);
    }

    /**
     * Exclui um usuário
     */
    public function deleteUser(string $userId): JsonResponse
    {
        $user = User::findOrFail($userId);

        // Não permite excluir admin
        if ($user->role === 'admin') {
            return response()->json([
                'message' => 'Não é possível excluir um administrador',
            ], 403);
        }

        // Exclui sessões e mensagens do usuário
        $sessions = ChatSession::where('user_id', $user->id)->get();
        foreach ($sessions as $session) {
            ChatMessage::where('session_id', $session->id)->delete();
            $session->delete();
        }

        $user->delete();

        return response()->json([
            'message' => 'Usuário excluído com sucesso',
        ]);
    }

    /**
     * Bane um usuário
     */
    public function banUser(string $userId): JsonResponse
    {
        $user = User::findOrFail($userId);

        if ($user->role === 'admin') {
            return response()->json([
                'message' => 'Não é possível banir um administrador',
            ], 403);
        }

        $user->update(['is_banned' => true]);

        return response()->json([
            'message' => 'Usuário banido com sucesso',
        ]);
    }

    /**
     * Desbane um usuário
     */
    public function unbanUser(string $userId): JsonResponse
    {
        $user = User::findOrFail($userId);
        $user->update(['is_banned' => false]);

        return response()->json([
            'message' => 'Usuário desbanido com sucesso',
        ]);
    }

    /**
     * Lista todas as sessões de chat (com cache e paginação)
     */
    public function sessions(Request $request): JsonResponse
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 50);
        $userId = $request->input('user_id');
        $cacheKey = "admin_sessions_page_{$page}_per_{$perPage}_user_{$userId}";
        
        $sessions = Cache::remember($cacheKey, 60, function () use ($perPage, $userId) {
            $query = ChatSession::select(['id', 'user_id', 'title', 'profile', 'message_count', 'created_at'])
                ->with(['user:id,name'])
                ->orderByDesc('created_at');
            
            if ($userId) {
                $query->where('user_id', $userId);
            }
            
            return $query->paginate($perPage);
        });

        return response()->json([
            'data' => $sessions->map(function ($session) {
                return [
                    'id' => $session->id,
                    'title' => $session->title,
                    'profile' => $session->profile,
                    'user' => $session->user ? [
                        'id' => $session->user->id,
                        'name' => $session->user->name,
                    ] : null,
                    'message_count' => $session->message_count,
                    'created_at' => $session->created_at,
                ];
            }),
            'meta' => [
                'current_page' => $sessions->currentPage(),
                'last_page' => $sessions->lastPage(),
                'per_page' => $sessions->perPage(),
                'total' => $sessions->total(),
            ]
        ]);
    }

    /**
     * Exclui uma sessão
     */
    public function deleteSession(string $sessionId): JsonResponse
    {
        $session = ChatSession::findOrFail($sessionId);
        
        ChatMessage::where('session_id', $session->id)->delete();
        $session->delete();

        return response()->json([
            'message' => 'Sessão excluída com sucesso',
        ]);
    }

    /**
     * Visualiza uma sessão com todas as mensagens
     */
    public function viewSession(string $sessionId): JsonResponse
    {
        $session = ChatSession::with(['user', 'messages' => function($query) {
            $query->orderBy('created_at', 'asc');
        }])->findOrFail($sessionId);

        return response()->json([
            'data' => [
                'session' => [
                    'id' => $session->id,
                    'title' => $session->title,
                    'profile' => $session->profile,
                    'target_domain' => $session->target_domain,
                    'created_at' => $session->created_at,
                    'user' => $session->user ? [
                        'id' => $session->user->id,
                        'name' => $session->user->name,
                        'email' => $session->user->email,
                    ] : null,
                ],
                'messages' => $session->messages->map(function($msg) {
                    return [
                        'id' => $msg->id,
                        'role' => $msg->role,
                        'content' => $msg->content,
                        'created_at' => $msg->created_at,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Limpa todas as sessões
     */
    public function clearSessions(): JsonResponse
    {
        ChatMessage::truncate();
        ChatSession::truncate();

        return response()->json([
            'message' => 'Todas as sessões foram excluídas',
        ]);
    }

    /**
     * Lista todos os planos (cache de 5 minutos)
     */
    public function plans(): JsonResponse
    {
        $plans = Cache::remember('admin_plans', 300, function () {
            return Plan::select(['id', 'name', 'slug', 'price', 'requests_per_day', 'features'])
                ->withCount('users')
                ->orderBy('price')
                ->get()
                ->map(function ($plan) {
                    return [
                        'id' => $plan->id,
                        'name' => $plan->name,
                        'slug' => $plan->slug,
                        'price' => $plan->price,
                        'daily_limit' => $plan->requests_per_day,
                        'users_count' => $plan->users_count,
                        'features' => $plan->features,
                    ];
                });
        });

        return response()->json(['data' => $plans]);
    }

    /**
     * Estatísticas do dashboard (cache de 30 segundos)
     */
    public function stats(): JsonResponse
    {
        $stats = Cache::remember('admin_stats', 30, function () {
            return [
                'users' => User::count(),
                'sessions' => ChatSession::count(),
                'messages' => ChatMessage::count(),
                'today_messages' => ChatMessage::whereDate('created_at', today())->count(),
            ];
        });

        return response()->json($stats);
    }
}
