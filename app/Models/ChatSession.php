<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatSession extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'target_domain',
        'profile',
        'is_active',
        'total_tokens',
        'message_count',
        'metadata',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'total_tokens' => 'integer',
        'message_count' => 'integer',
        'metadata' => 'array',
    ];

    protected $hidden = [
        'metadata',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'session_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public function incrementTokens(int $tokens): void
    {
        $this->increment('total_tokens', $tokens);
    }

    public function incrementMessageCount(): void
    {
        $this->increment('message_count');
    }

    public function getContextMessages(int $limit = null): array
    {
        $limit = $limit ?? config('deepseek.memory.max_messages', 20);

        return $this->messages()
            ->whereIn('role', ['user', 'assistant'])
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get()
            ->reverse()
            ->map(fn($msg) => [
                'role' => $msg->role,
                'content' => $msg->content,
            ])
            ->values()
            ->toArray();
    }

    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }
}
