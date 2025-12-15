<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'session_id',
        'role',
        'content',
        'tokens',
        'prompt_tokens',
        'completion_tokens',
        'model',
        'metadata',
        'is_flagged',
    ];

    protected $casts = [
        'tokens' => 'integer',
        'prompt_tokens' => 'integer',
        'completion_tokens' => 'integer',
        'metadata' => 'array',
        'is_flagged' => 'boolean',
    ];

    protected $hidden = [
        'metadata',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function session(): BelongsTo
    {
        return $this->belongsTo(ChatSession::class, 'session_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    public function scopeUserMessages($query)
    {
        return $query->where('role', 'user');
    }

    public function scopeAssistantMessages($query)
    {
        return $query->where('role', 'assistant');
    }

    public function scopeFlagged($query)
    {
        return $query->where('is_flagged', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getTotalTokensAttribute(): int
    {
        return ($this->prompt_tokens ?? 0) + ($this->completion_tokens ?? 0);
    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public function flag(string $reason = null): void
    {
        $this->update([
            'is_flagged' => true,
            'metadata' => array_merge($this->metadata ?? [], [
                'flagged_at' => now()->toIso8601String(),
                'flag_reason' => $reason,
            ]),
        ]);
    }
}
