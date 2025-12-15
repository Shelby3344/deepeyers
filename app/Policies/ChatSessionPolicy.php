<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ChatSession;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChatSessionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any sessions.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the session.
     */
    public function view(User $user, ChatSession $session): bool
    {
        return $user->id === $session->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can create sessions.
     */
    public function create(User $user): bool
    {
        return !$user->is_banned;
    }

    /**
     * Determine whether the user can update the session.
     */
    public function update(User $user, ChatSession $session): bool
    {
        return $user->id === $session->user_id;
    }

    /**
     * Determine whether the user can delete the session.
     */
    public function delete(User $user, ChatSession $session): bool
    {
        return $user->id === $session->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can send messages to the session.
     */
    public function sendMessage(User $user, ChatSession $session): bool
    {
        if ($user->is_banned) {
            return false;
        }

        if ($user->id !== $session->user_id) {
            return false;
        }

        if (!$session->is_active) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can use the requested profile.
     */
    public function useProfile(User $user, string $profile): bool
    {
        return $user->canUseProfile($profile);
    }
}
