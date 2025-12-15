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
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Lista todos os usuários
     */
    public function users(Request $request): JsonResponse
    {
        $users = User::with('plan')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'avatar' => $user->avatar_url, // Usa accessor para URL completa
                    'is_banned' => (bool) $user->is_banned,
                    'plan' => $user->plan ? [
                        'id' => $user->plan->id,
                        'name' => $user->plan->name,
                    ] : null,
                    'created_at' => $user->created_at,
                ];
            });

        return response()->json(['data' => $users]);
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

        $user->update($validated);

        return response()->json([
            'message' => 'Usuário atualizado com sucesso',
            'user' => $user->load('plan'),
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
     * Lista todas as sessões de chat
     */
    public function sessions(Request $request): JsonResponse
    {
        $sessions = ChatSession::with('user')
            ->withCount('messages')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($session) {
                return [
                    'id' => $session->id,
                    'title' => $session->title,
                    'profile' => $session->profile,
                    'user' => $session->user ? [
                        'id' => $session->user->id,
                        'name' => $session->user->name,
                    ] : null,
                    'message_count' => $session->messages_count,
                    'created_at' => $session->created_at,
                ];
            });

        return response()->json(['data' => $sessions]);
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
     * Lista todos os planos
     */
    public function plans(): JsonResponse
    {
        $plans = Plan::withCount('users')
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

        return response()->json(['data' => $plans]);
    }

    /**
     * Estatísticas do dashboard
     */
    public function stats(): JsonResponse
    {
        $totalUsers = User::count();
        $totalSessions = ChatSession::count();
        $totalMessages = ChatMessage::count();
        $todayMessages = ChatMessage::whereDate('created_at', today())->count();

        return response()->json([
            'users' => $totalUsers,
            'sessions' => $totalSessions,
            'messages' => $totalMessages,
            'today_messages' => $todayMessages,
        ]);
    }
}
