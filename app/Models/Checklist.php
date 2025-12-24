<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Checklist extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'session_id',
        'title',
        'target_domain',
        'type',
        'states',
        'notes',
        'share_token',
        'is_public',
    ];

    protected function casts(): array
    {
        return [
            'states' => 'array',
            'notes' => 'array',
            'is_public' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(ChatSession::class, 'session_id');
    }

    public function generateShareToken(): string
    {
        $this->share_token = Str::random(64);
        $this->save();
        return $this->share_token;
    }

    public function getProgress(): array
    {
        $states = $this->states ?? [];
        $tested = count(array_filter($states, fn($s) => $s === 'tested'));
        $vuln = count(array_filter($states, fn($s) => $s === 'vuln'));
        $ok = count(array_filter($states, fn($s) => $s === 'ok'));
        
        return [
            'tested' => $tested,
            'vulnerable' => $vuln,
            'ok' => $ok,
            'total' => $tested + $vuln + $ok,
        ];
    }
}
