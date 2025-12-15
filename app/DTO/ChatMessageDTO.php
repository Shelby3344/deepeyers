<?php

declare(strict_types=1);

namespace App\DTO;

use Illuminate\Http\Request;

final readonly class ChatMessageDTO
{
    public function __construct(
        public string $content,
        public string $sessionId,
        public string $profile,
        public int $userId,
        public ?string $ipAddress = null,
    ) {}

    public static function fromRequest(Request $request, string $sessionId): self
    {
        return new self(
            content: $request->validated('message'),
            sessionId: $sessionId,
            profile: $request->validated('profile', config('deepseek.default_profile')),
            userId: $request->user()->id,
            ipAddress: $request->ip(),
        );
    }

    public static function make(
        string $content,
        string $sessionId,
        string $profile,
        int $userId,
        ?string $ipAddress = null
    ): self {
        return new self(
            content: $content,
            sessionId: $sessionId,
            profile: $profile,
            userId: $userId,
            ipAddress: $ipAddress,
        );
    }

    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'session_id' => $this->sessionId,
            'profile' => $this->profile,
            'user_id' => $this->userId,
            'ip_address' => $this->ipAddress,
        ];
    }
}
