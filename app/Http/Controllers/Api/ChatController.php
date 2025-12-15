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
        Gate::authorize('create', ChatSession::class);

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
    public function show(string $sessionId): JsonResponse
    {
        $session = ChatSession::findOrFail($sessionId);
        Gate::authorize('view', $session);

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
        Gate::authorize('update', $session);

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
    public function destroy(string $sessionId): JsonResponse
    {
        $session = ChatSession::findOrFail($sessionId);
        Gate::authorize('delete', $session);

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
        Gate::authorize('sendMessage', $session);

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
        Gate::authorize('sendMessage', $session);
        
        $user = $request->user();
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
        Gate::authorize('sendMessage', $session);

        $user = $request->user();;

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
        Gate::authorize('view', $session);

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
