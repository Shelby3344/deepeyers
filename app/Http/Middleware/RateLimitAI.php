<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\AbuseLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitAI
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!config('deepseek.rate_limit.enabled', true)) {
            return $next($request);
        }

        $user = $request->user();

        if (!$user) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Authentication required',
            ], 401);
        }

        // Check if user is banned
        if ($user->is_banned) {
            return response()->json([
                'error' => 'Forbidden',
                'message' => 'Your account has been suspended',
            ], 403);
        }

        // Check rate limits
        $limits = [
            'minute' => [
                'key' => "ai_minute:{$user->id}",
                'max' => (int) config('deepseek.rate_limit.max_requests_per_minute', 20),
                'decay' => 60,
            ],
            'hour' => [
                'key' => "ai_hour:{$user->id}",
                'max' => (int) config('deepseek.rate_limit.max_requests_per_hour', 100),
                'decay' => 3600,
            ],
        ];

        foreach ($limits as $name => $limit) {
            $executed = RateLimiter::attempt(
                $limit['key'],
                $limit['max'],
                fn() => true,
                $limit['decay']
            );

            if (!$executed) {
                $this->logRateLimitHit($user->id, $name, $request);

                $retryAfter = RateLimiter::availableIn($limit['key']);

                return response()->json([
                    'error' => 'Too Many Requests',
                    'message' => "Rate limit exceeded ({$name}). Try again in {$retryAfter} seconds.",
                    'retry_after' => $retryAfter,
                ], 429)->header('Retry-After', (string) $retryAfter);
            }
        }

        // Check daily limit (persisted)
        if ($user->hasReachedDailyLimit()) {
            $this->logRateLimitHit($user->id, 'daily', $request);

            return response()->json([
                'error' => 'Daily Limit Exceeded',
                'message' => 'You have reached your daily request limit. Try again tomorrow.',
                'remaining' => 0,
            ], 429);
        }

        // Add rate limit headers
        $response = $next($request);

        return $this->addRateLimitHeaders($response, $user);
    }

    /**
     * Add rate limit headers to response
     */
    private function addRateLimitHeaders(Response $response, $user): Response
    {
        $perMinute = config('deepseek.rate_limit.max_requests_per_minute', 20);
        $remaining = RateLimiter::remaining("ai_minute:{$user->id}", $perMinute);

        $response->headers->set('X-RateLimit-Limit', (string) $perMinute);
        $response->headers->set('X-RateLimit-Remaining', (string) max(0, $remaining));
        $response->headers->set('X-RateLimit-Daily-Remaining', (string) $user->getDailyRequestsRemaining());

        return $response;
    }

    /**
     * Log rate limit hit
     */
    private function logRateLimitHit(int $userId, string $limitType, Request $request): void
    {
        AbuseLog::log(
            type: 'rate_limit',
            reason: "Rate limit exceeded: {$limitType}",
            userId: $userId,
            ipAddress: $request->ip(),
            metadata: [
                'limit_type' => $limitType,
                'path' => $request->path(),
            ],
        );
    }
}
