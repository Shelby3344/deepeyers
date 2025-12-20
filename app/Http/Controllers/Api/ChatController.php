<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\DeepSeek\CreateSessionAction;
use App\Actions\DeepSeek\GetSessionHistoryAction;
use App\Actions\DeepSeek\SendMessageAction;
use App\DTO\ChatMessageDTO;
use App\DTO\CreateSessionDTO;
use App\Exceptions\DeepSeekException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateSessionRequest;
use App\Http\Requests\Api\SendMessageRequest;
use App\Http\Requests\Api\UpdateSessionRequest;
use App\Jobs\ProcessDeepSeekMessageJob;
use App\Models\ChatSession;
use App\Models\ChatMessage;
use App\Services\DeepSeekService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatController extends Controller
{
    public function __construct(
        private readonly CreateSessionAction $createSessionAction,
        private readonly SendMessageAction $sendMessageAction,
        private readonly GetSessionHistoryAction $getSessionHistoryAction,
        private readonly DeepSeekService $deepSeekService,
    ) {}

    /**
     * Verify user can access session
     */
    private function authorizeView(Request $request, ChatSession $session): void
    {
        $user = $request->user();
        if ($user->id !== $session->user_id && !$user->isAdmin()) {
            abort(403, 'You do not have access to this session');
        }
    }

    /**
     * Verify user can send messages to session
     */
    private function authorizeSendMessage(Request $request, ChatSession $session): void
    {
        $user = $request->user();
        if ($user->is_banned) {
            abort(403, 'Your account has been suspended');
        }
        if ($user->id !== $session->user_id) {
            abort(403, 'You do not own this session');
        }
        if (!$session->is_active) {
            abort(403, 'This session is not active');
        }
    }

    /**
     * Verifica se o usuário pode acessar um perfil específico
     */
    private function userCanAccessProfile($user, string $profile): bool
    {
        // Pentest é sempre permitido
        if ($profile === 'pentest') {
            return true;
        }
        
        // Admin sempre tem acesso total
        if ($user->isAdmin()) {
            return true;
        }
        
        // Carrega o plano do usuário
        $user->loadMissing('plan');
        
        // Se não tem plano, só pode usar pentest
        if (!$user->plan) {
            return false;
        }
        
        // Verifica se o plano permite o perfil
        return $user->plan->allowsProfile($profile);
    }

    /**
     * List user sessions
     */
    public function index(Request $request): JsonResponse
    {
        $sessions = ChatSession::forUser($request->user()->id)
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return response()->json([
            'data' => $sessions->map(fn($session) => [
                'id' => $session->id,
                'title' => $session->title,
                'target_domain' => $session->target_domain,
                'profile' => $session->profile,
                'is_active' => $session->is_active,
                'message_count' => $session->message_count,
                'total_tokens' => $session->total_tokens,
                'created_at' => $session->created_at->toIso8601String(),
                'updated_at' => $session->updated_at->toIso8601String(),
            ]),
            'meta' => [
                'current_page' => $sessions->currentPage(),
                'last_page' => $sessions->lastPage(),
                'per_page' => $sessions->perPage(),
                'total' => $sessions->total(),
            ],
        ]);
    }

    /**
     * Create a new chat session
     */
    public function store(CreateSessionRequest $request): JsonResponse
    {
        // Verificar se usuário pode criar sessão
        $user = $request->user();
        if ($user->is_banned) {
            return response()->json([
                'error' => 'Forbidden',
                'message' => 'Your account has been suspended',
            ], 403);
        }

        // Verificar se usuário tem permissão para o perfil selecionado
        $profile = $request->validated('profile') ?? 'pentest';
        if (!$this->userCanAccessProfile($user, $profile)) {
            return response()->json([
                'error' => 'Forbidden',
                'message' => 'Para acessar os modos Red Team e Full Attack, é necessário ativar um plano.',
            ], 403);
        }

        $dto = CreateSessionDTO::fromRequest(
            $request->validated(),
            $request->user()->id
        );

        $session = $this->createSessionAction->execute($dto);

        return response()->json([
            'message' => 'Session created successfully',
            'data' => [
                'id' => $session->id,
                'title' => $session->title,
                'target_domain' => $session->target_domain,
                'profile' => $session->profile,
                'is_active' => $session->is_active,
                'created_at' => $session->created_at->toIso8601String(),
            ],
        ], 201);
    }

    /**
     * Get session with messages
     */
    public function show(Request $request, string $sessionId): JsonResponse
    {
        $session = ChatSession::findOrFail($sessionId);
        $this->authorizeView($request, $session);

        $history = $this->getSessionHistoryAction->execute($sessionId);

        return response()->json([
            'data' => $history,
        ]);
    }

    /**
     * Update session
     */
    public function update(UpdateSessionRequest $request, string $sessionId): JsonResponse
    {
        $session = ChatSession::findOrFail($sessionId);
        $user = $request->user();
        if ($user->id !== $session->user_id) {
            abort(403, 'You do not own this session');
        }

        $session->update($request->validated());

        return response()->json([
            'message' => 'Session updated successfully',
            'data' => [
                'id' => $session->id,
                'title' => $session->title,
                'is_active' => $session->is_active,
            ],
        ]);
    }

    /**
     * Delete session
     */
    public function destroy(Request $request, string $sessionId): JsonResponse
    {
        $session = ChatSession::findOrFail($sessionId);
        $user = $request->user();
        if ($user->id !== $session->user_id && !$user->isAdmin()) {
            abort(403, 'You do not have permission to delete this session');
        }

        $session->delete();

        return response()->json([
            'message' => 'Session deleted successfully',
        ]);
    }

    /**
     * Send message to AI (synchronous)
     */
    public function sendMessage(SendMessageRequest $request, string $sessionId): JsonResponse
    {
        $session = ChatSession::findOrFail($sessionId);
        $this->authorizeSendMessage($request, $session);

        $dto = ChatMessageDTO::make(
            content: $request->validated('message'),
            sessionId: $sessionId,
            profile: $session->profile,
            userId: $request->user()->id,
            ipAddress: $request->ip(),
        );

        try {
            $result = $this->sendMessageAction->execute($dto);

            return response()->json([
                'data' => [
                    'message' => [
                        'id' => $result['message']->id,
                        'role' => $result['message']->role,
                        'content' => $result['message']->content,
                        'created_at' => $result['message']->created_at->toIso8601String(),
                    ],
                    'usage' => $result['usage'],
                ],
            ]);

        } catch (DeepSeekException $e) {
            return response()->json([
                'error' => 'AI Processing Error',
                'message' => $e->getMessage(),
            ], $e->getCode() >= 400 ? $e->getCode() : 500);
        }
    }
    
    /**
     * Send message to AI with streaming response
     */
    public function sendMessageStream(SendMessageRequest $request, string $sessionId): StreamedResponse
    {
        $session = ChatSession::findOrFail($sessionId);
        $user = $request->user();
        
        // Verificar permissões
        if ($user->is_banned) {
            abort(403, 'Your account has been suspended');
        }
        if ($user->id !== $session->user_id) {
            abort(403, 'You do not own this session');
        }
        if (!$session->is_active) {
            abort(403, 'This session is not active');
        }
        
        // Verificar limite diário
        if ($user->hasReachedDailyLimit()) {
            abort(429, 'Você atingiu o limite diário de requisições. Tente novamente amanhã ou faça upgrade do seu plano.');
        }
        
        // Incrementar contador de requisições
        $user->incrementDailyRequests();
        
        $message = $request->validated('message');
        
        // Get conversation history BEFORE saving new message (to avoid duplicate in context)
        $history = $this->getSessionHistoryAction->execute($sessionId);
        
        // Save user message
        $userMessage = ChatMessage::create([
            'session_id' => $sessionId,
            'role' => 'user',
            'content' => $message,
        ]);
        $contextMessages = collect($history['messages'])->map(fn($msg) => [
            'role' => $msg['role'],
            'content' => $msg['content'],
        ])->toArray();
        
        // Get system prompt
        $systemPrompt = $this->deepSeekService->getSystemPrompt($session->profile);
        
        return response()->stream(function () use ($systemPrompt, $contextMessages, $message, $sessionId) {
            $fullContent = '';
            
            try {
                foreach ($this->deepSeekService->chatStreamWithContext(
                    $systemPrompt,
                    $contextMessages,
                    $message
                ) as $chunk) {
                    $fullContent .= $chunk;
                    echo "data: " . json_encode(['content' => $chunk]) . "\n\n";
                    if (ob_get_level() > 0) ob_flush();
                    flush();
                }
                
                // Save assistant message after streaming completes
                $assistantMessage = ChatMessage::create([
                    'session_id' => $sessionId,
                    'role' => 'assistant',
                    'content' => $fullContent,
                ]);
                
                echo "data: " . json_encode(['done' => true, 'message_id' => $assistantMessage->id]) . "\n\n";
                if (ob_get_level() > 0) ob_flush();
                flush();
                
            } catch (\Exception $e) {
                \Log::error('Stream error: ' . $e->getMessage());
                echo "data: " . json_encode(['error' => $e->getMessage()]) . "\n\n";
                if (ob_get_level() > 0) ob_flush();
                flush();
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    /**
     * Send message to AI (async via queue)
     */
    public function sendMessageAsync(SendMessageRequest $request, string $sessionId): JsonResponse
    {
        $session = ChatSession::findOrFail($sessionId);
        $this->authorizeSendMessage($request, $session);

        $user = $request->user();

        $dto = ChatMessageDTO::make(
            content: $request->validated('message'),
            sessionId: $sessionId,
            profile: $session->profile,
            userId: $user->id,
            ipAddress: $request->ip(),
        );

        // Dispatch job
        ProcessDeepSeekMessageJob::dispatch($dto);

        // Return job tracking key
        $cacheKey = ProcessDeepSeekMessageJob::getCacheKey($sessionId, $user->id);

        return response()->json([
            'message' => 'Message queued for processing',
            'tracking_key' => $cacheKey,
            'poll_url' => route('api.chat.status', ['sessionId' => $sessionId]),
        ], 202);
    }

    /**
     * Check async message status
     */
    public function checkStatus(Request $request, string $sessionId): JsonResponse
    {
        $session = ChatSession::findOrFail($sessionId);
        $this->authorizeView($request, $session);

        $cacheKey = ProcessDeepSeekMessageJob::getCacheKey($sessionId, $request->user()->id);

        $status = Cache::get($cacheKey . ':status', 'pending');
        $result = Cache::get($cacheKey . ':result');
        $error = Cache::get($cacheKey . ':error');

        $response = [
            'status' => $status,
        ];

        if ($status === 'completed' && $result) {
            $response['data'] = [
                'message' => [
                    'id' => $result['message']->id,
                    'role' => $result['message']->role,
                    'content' => $result['message']->content,
                    'created_at' => $result['message']->created_at->toIso8601String(),
                ],
                'usage' => $result['usage'],
            ];

            // Clear cache after retrieval
            Cache::forget($cacheKey . ':status');
            Cache::forget($cacheKey . ':result');
        }

        if ($status === 'failed' && $error) {
            $response['error'] = $error;
            Cache::forget($cacheKey . ':status');
            Cache::forget($cacheKey . ':error');
        }

        return response()->json($response);
    }

    /**
     * Get available profiles for current user
     */
    public function profiles(Request $request): JsonResponse
    {
        $user = $request->user();
        $allProfiles = array_keys(config('deepseek.system_prompts', []));
        $allowedProfiles = config('deepseek.allowed_profiles.' . $user->role, []);

        $profiles = [];
        foreach ($allProfiles as $profile) {
            $profiles[] = [
                'name' => $profile,
                'available' => in_array($profile, $allowedProfiles),
            ];
        }

        return response()->json([
            'data' => $profiles,
            'user_role' => $user->role,
        ]);
    }
}
