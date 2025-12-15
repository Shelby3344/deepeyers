<?php

declare(strict_types=1);

namespace App\Actions\DeepSeek;

use App\DTO\ChatMessageDTO;
use App\DTO\DeepSeekResponseDTO;
use App\Exceptions\DeepSeekException;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\User;
use App\Services\ContentModerationService;
use App\Services\DeepSeekService;
use Illuminate\Support\Facades\DB;

class SendMessageAction
{
    public function __construct(
        private readonly DeepSeekService $deepSeekService,
        private readonly ContentModerationService $moderationService,
    ) {}

    /**
     * Execute the action - send message to DeepSeek and store response
     */
    public function execute(ChatMessageDTO $dto): array
    {
        // Load session with user
        $session = ChatSession::with('user')->findOrFail($dto->sessionId);
        $user = $session->user;

        // Validate user can use this profile
        $this->validateProfileAccess($user, $session->profile);

        // Validate input content
        $this->validateContent($dto, $session);

        // Get system prompt
        $systemPrompt = $this->deepSeekService->getSystemPrompt($session->profile);

        // Get context messages
        $contextMessages = $session->getContextMessages();

        // Store user message
        $userMessage = $this->storeUserMessage($session, $dto->content);

        try {
            // Call DeepSeek API
            $response = $this->deepSeekService->chatWithContext(
                systemPrompt: $systemPrompt,
                contextMessages: $contextMessages,
                userMessage: $dto->content,
            );

            // Store assistant message
            $assistantMessage = $this->storeAssistantMessage($session, $response);

            // Update session stats
            $this->updateSessionStats($session, $response);

            // Increment user daily requests
            $user->incrementDailyRequests();

            return [
                'success' => true,
                'message' => $assistantMessage,
                'usage' => [
                    'prompt_tokens' => $response->promptTokens,
                    'completion_tokens' => $response->completionTokens,
                    'total_tokens' => $response->totalTokens,
                ],
            ];

        } catch (DeepSeekException $e) {
            // Mark user message as flagged if API fails
            $userMessage->update(['is_flagged' => true]);
            throw $e;
        }
    }

    /**
     * Validate user can access the profile
     */
    private function validateProfileAccess(User $user, string $profile): void
    {
        if (!$user->canUseProfile($profile)) {
            throw new DeepSeekException(
                "User does not have access to profile: {$profile}",
                403
            );
        }
    }

    /**
     * Validate content using moderation service
     */
    private function validateContent(ChatMessageDTO $dto, ChatSession $session): void
    {
        $result = $this->moderationService->validateInput($dto->content);

        if ($result->failed()) {
            $this->moderationService->logAbuse(
                type: $result->type,
                reason: $result->reason,
                userId: $dto->userId,
                sessionId: $dto->sessionId,
                content: $dto->content,
                ipAddress: $dto->ipAddress,
            );

            throw new DeepSeekException($result->reason, 400);
        }
    }

    /**
     * Store user message in database
     */
    private function storeUserMessage(ChatSession $session, string $content): ChatMessage
    {
        return $session->messages()->create([
            'role' => 'user',
            'content' => $content,
        ]);
    }

    /**
     * Store assistant message in database
     */
    private function storeAssistantMessage(ChatSession $session, DeepSeekResponseDTO $response): ChatMessage
    {
        return $session->messages()->create([
            'role' => 'assistant',
            'content' => $response->content,
            'tokens' => $response->totalTokens,
            'prompt_tokens' => $response->promptTokens,
            'completion_tokens' => $response->completionTokens,
            'model' => $response->model,
            'metadata' => [
                'finish_reason' => $response->finishReason,
                'api_id' => $response->id,
            ],
        ]);
    }

    /**
     * Update session statistics
     */
    private function updateSessionStats(ChatSession $session, DeepSeekResponseDTO $response): void
    {
        $session->incrementTokens($response->totalTokens);
        $session->increment('message_count', 2); // User + Assistant
    }
}
