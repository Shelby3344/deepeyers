<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\Checklist;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Exibe a página do dashboard
     */
    public function index(Request $request): View
    {
        return view('dashboard');
    }

    /**
     * Retorna estatísticas do dashboard via API
     */
    public function stats(Request $request): JsonResponse
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Estatísticas do usuário
        $totalSessions = ChatSession::where('user_id', $user->id)->count();
        $totalMessages = DB::table('chat_messages')
            ->whereIn('session_id', ChatSession::where('user_id', $user->id)->pluck('id'))
            ->count();
        $totalChecklists = Checklist::where('user_id', $user->id)->count();
        
        // Sessões recentes
        $recentSessions = ChatSession::where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get(['id', 'title', 'target_domain', 'profile', 'updated_at']);

        // Checklists recentes
        $recentChecklists = Checklist::where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get(['id', 'title', 'type', 'states', 'updated_at']);

        // Uso diário
        $dailyUsage = [
            'used' => $user->daily_requests ?? 0,
            'limit' => $user->getDailyLimit(),
            'remaining' => $user->getDailyRequestsRemaining(),
            'percentage' => $user->getDailyLimit() > 0 
                ? round(($user->daily_requests ?? 0) / $user->getDailyLimit() * 100) 
                : 0,
        ];

        // Atividade dos últimos 7 dias
        $weeklyActivity = DB::table('chat_messages')
            ->whereIn('session_id', ChatSession::where('user_id', $user->id)->pluck('id'))
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Preenche dias sem atividade
        $activityData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $activityData[] = [
                'date' => now()->subDays($i)->format('d/m'),
                'count' => $weeklyActivity[$date] ?? 0,
            ];
        }

        return response()->json([
            'stats' => [
                'total_sessions' => $totalSessions,
                'total_messages' => $totalMessages,
                'total_checklists' => $totalChecklists,
            ],
            'daily_usage' => $dailyUsage,
            'weekly_activity' => $activityData,
            'recent_sessions' => $recentSessions,
            'recent_checklists' => $recentChecklists->map(function ($checklist) {
                $states = $checklist->states ?? [];
                $total = count($states);
                $completed = collect($states)->filter(fn($s) => $s === true)->count();
                return [
                    'id' => $checklist->id,
                    'title' => $checklist->title,
                    'type' => $checklist->type,
                    'progress' => $total > 0 ? round($completed / $total * 100) : 0,
                    'updated_at' => $checklist->updated_at->diffForHumans(),
                ];
            }),
            'plan' => $user->plan ? [
                'name' => $user->plan->name,
                'slug' => $user->plan->slug,
            ] : ['name' => 'Free', 'slug' => 'free'],
        ]);
    }
}
