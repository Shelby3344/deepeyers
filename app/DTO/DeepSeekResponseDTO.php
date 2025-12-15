<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class DeepSeekResponseDTO
{
    public function __construct(
        public string $content,
        public int $promptTokens,
        public int $completionTokens,
        public int $totalTokens,
        public string $model,
        public string $finishReason,
        public ?string $id = null,
    ) {}

    public static function fromApiResponse(array $response): self
    {
        $choice = $response['choices'][0] ?? [];
        $usage = $response['usage'] ?? [];

        return new self(
            content: $choice['message']['content'] ?? '',
            promptTokens: $usage['prompt_tokens'] ?? 0,
            completionTokens: $usage['completion_tokens'] ?? 0,
            totalTokens: $usage['total_tokens'] ?? 0,
            model: $response['model'] ?? config('deepseek.model'),
            finishReason: $choice['finish_reason'] ?? 'unknown',
            id: $response['id'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'prompt_tokens' => $this->promptTokens,
            'completion_tokens' => $this->completionTokens,
            'total_tokens' => $this->totalTokens,
            'model' => $this->model,
            'finish_reason' => $this->finishReason,
            'id' => $this->id,
        ];
    }
}
