<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\DeepSeek\SendMessageAction;
use App\DTO\ChatMessageDTO;
use App\Exceptions\DeepSeekException;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProcessDeepSeekMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 180;
    public int $backoff = 10;
    public int $maxExceptions = 2;

    private string $cacheKey;

    public function __construct(
        private readonly ChatMessageDTO $dto,
    ) {
        $this->cacheKey = "chat_response:{$dto->sessionId}:{$dto->userId}";
    }

    public function handle(SendMessageAction $action): void
    {
        try {
            // Set processing state
            Cache::put($this->cacheKey . ':status', 'processing', 300);

            // Execute the action
            $result = $action->execute($this->dto);

            // Store result in cache for polling
            Cache::put($this->cacheKey . ':result', $result, 300);
            Cache::put($this->cacheKey . ':status', 'completed', 300);

            Log::info('DeepSeek message processed', [
                'session_id' => $this->dto->sessionId,
                'user_id' => $this->dto->userId,
                'tokens' => $result['usage']['total_tokens'] ?? 0,
            ]);

        } catch (DeepSeekException $e) {
            $this->handleFailure($e);
            throw $e;
        }
    }

    private function handleFailure(\Throwable $e): void
    {
        Cache::put($this->cacheKey . ':status', 'failed', 300);
        Cache::put($this->cacheKey . ':error', [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
        ], 300);

        Log::error('DeepSeek message processing failed', [
            'session_id' => $this->dto->sessionId,
            'user_id' => $this->dto->userId,
            'error' => $e->getMessage(),
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        $this->handleFailure($exception);
    }

    /**
     * Determine if job should be retried
     */
    public function shouldRetry(\Throwable $e): bool
    {
        if ($e instanceof DeepSeekException) {
            return $e->isRetryable();
        }
        return true;
    }

    /**
     * Get cache key for this job
     */
    public static function getCacheKey(string $sessionId, int $userId): string
    {
        return "chat_response:{$sessionId}:{$userId}";
    }
}
