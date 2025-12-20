<?php

declare(strict_types=1);

namespace App\Actions\DeepSeek;

use App\DTO\CreateSessionDTO;
use App\Models\ChatSession;
use App\Models\User;

class CreateSessionAction
{
    /**
     * Execute the action - create a new chat session
     */
    public function execute(CreateSessionDTO $dto): ChatSession
    {
        $user = User::findOrFail($dto->userId);

        // Validate profile access
        if (!$user->canUseProfile($dto->profile)) {
            $dto = new CreateSessionDTO(
                userId: $dto->userId,
                title: $dto->title,
                profile: config('deepseek.default_profile'),
                targetDomain: $dto->targetDomain,
                metadata: $dto->metadata,
            );
        }

        return ChatSession::create([
            'user_id' => $dto->userId,
            'title' => $dto->title,
            'target_domain' => $dto->targetDomain,
            'profile' => $dto->profile,
            'is_active' => true,
            'metadata' => $dto->metadata,
        ]);
    }
}
