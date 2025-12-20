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

        $session = new ChatSession();
        $session->user_id = $dto->userId;
        $session->title = $dto->title;
        $session->target_domain = $dto->targetDomain;
        $session->profile = $dto->profile;
        $session->is_active = true;
        $session->metadata = $dto->metadata;
        $session->save();

        return $session;
    }
}
