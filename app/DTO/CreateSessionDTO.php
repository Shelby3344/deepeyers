<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class CreateSessionDTO
{
    public function __construct(
        public int $userId,
        public string $title,
        public string $profile,
        public ?string $targetDomain = null,
        public ?array $metadata = null,
    ) {}

    public static function fromRequest(array $data, int $userId): self
    {
        return new self(
            userId: $userId,
            title: $data['title'] ?? 'Nova Sessão',
            profile: $data['profile'] ?? config('deepseek.default_profile'),
            targetDomain: $data['target_domain'] ?? null,
            metadata: $data['metadata'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'title' => $this->title,
            'target_domain' => $this->targetDomain,
            'profile' => $this->profile,
            'metadata' => $this->metadata,
        ];
    }
}
