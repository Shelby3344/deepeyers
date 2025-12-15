<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'is_banned',
        'banned_at',
        'ban_reason',
        'daily_requests',
        'daily_requests_date',
        'plan_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_banned' => 'boolean',
            'banned_at' => 'datetime',
            'daily_requests' => 'integer',
            'daily_requests_date' => 'date',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Retorna a URL completa do avatar
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (!$this->avatar) {
            return null;
        }
        
        // Se já for uma URL completa, retorna direto
        if (str_starts_with($this->avatar, 'http')) {
            return $this->avatar;
        }
        
        return asset('storage/' . $this->avatar);
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function chatSessions(): HasMany
    {
        return $this->hasMany(ChatSession::class);
    }

    public function abuseLogs(): HasMany
    {
        return $this->hasMany(AbuseLog::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Role Checks
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isRedTeam(): bool
    {
        return in_array($this->role, ['redteam', 'admin']);
    }

    public function isAnalyst(): bool
    {
        return in_array($this->role, ['analyst', 'redteam', 'admin']);
    }

    public function canUseProfile(string $profile): bool
    {
        $allowedProfiles = config('deepseek.allowed_profiles.' . $this->role, []);
        return in_array($profile, $allowedProfiles);
    }

    /*
    |--------------------------------------------------------------------------
    | Ban Methods
    |--------------------------------------------------------------------------
    */

    public function ban(string $reason): void
    {
        $this->update([
            'is_banned' => true,
            'banned_at' => now(),
            'ban_reason' => $reason,
        ]);

        $this->tokens()->delete();
    }

    public function unban(): void
    {
        $this->update([
            'is_banned' => false,
            'banned_at' => null,
            'ban_reason' => null,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */

    public function incrementDailyRequests(): void
    {
        $today = now()->toDateString();

        if ($this->daily_requests_date?->toDateString() !== $today) {
            $this->update([
                'daily_requests' => 1,
                'daily_requests_date' => $today,
            ]);
        } else {
            $this->increment('daily_requests');
        }
    }

    public function getDailyRequestsRemaining(): int
    {
        // Usa o limite do plano do usuário
        $plan = $this->plan;
        $maxPerDay = $plan ? (int) $plan->requests_per_day : 5; // Free default: 5
        
        // Se for admin, ilimitado
        if ($this->role === 'admin') {
            return 9999;
        }
        
        $today = now()->toDateString();

        if ($this->daily_requests_date?->toDateString() !== $today) {
            return $maxPerDay;
        }

        return max(0, $maxPerDay - (int) $this->daily_requests);
    }

    public function hasReachedDailyLimit(): bool
    {
        // Admin nunca atinge limite
        if ($this->role === 'admin') {
            return false;
        }
        return $this->getDailyRequestsRemaining() <= 0;
    }

    /**
     * Retorna o limite diário do plano do usuário
     */
    public function getDailyLimit(): int
    {
        if ($this->role === 'admin') {
            return -1; // Ilimitado
        }
        $plan = $this->plan;
        return $plan ? (int) $plan->requests_per_day : 5;
    }
}
