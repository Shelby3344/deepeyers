<?php

declare(strict_types=1);

namespace App\Actions\DeepSeek;

use App\Models\ChatSession;

class GetSessionHistoryAction
{
    /**
     * Execute the action - get session with messages
     * OTIMIZADO: Limita mensagens para contexto mais rápido
     */
    public function execute(string $sessionId, int $limit = null): array
    {
        // Usa limite da config se não especificado (padrão: 10 para velocidade)
        $maxMessages = $limit ?? (int) config('deepseek.memory.max_messages', 10);
        
        $session = ChatSession::findOrFail($sessionId);
        
        // Pega apenas as últimas N mensagens para contexto rápido
        $messages = $session->messages()
            ->orderBy('created_at', 'desc')
            ->take($maxMessages)
            ->get()
            ->reverse() // Reordena para ordem cronológica
            ->values();

        return [
            'session' => [
                'id' => $session->id,
                'title' => $session->title,
                'profile' => $session->profile,
                'is_active' => $session->is_active,
                'total_tokens' => $session->total_tokens,
                'message_count' => $session->message_count,
                'created_at' => $session->created_at->toIso8601String(),
            ],
            'messages' => $messages->map(fn($msg) => [
                'id' => $msg->id,
                'role' => $msg->role,
                'content' => $msg->content,
                'tokens' => $msg->tokens,
                'created_at' => $msg->created_at->toIso8601String(),
            ])->toArray(),
        ];
    }
}
