<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RateLimitRequests
{
    /**
     * Rate limit configuration
     */
    private int $maxRequests = 100; // requests per minute
    private int $decayMinutes = 1;
    private int $banThreshold = 500; // requests that trigger a ban
    private int $banDuration = 60; // minutes

    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $key = 'rate_limit:' . $ip;
        $banKey = 'banned:' . $ip;

        // Check if IP is banned
        if (Cache::has($banKey)) {
            Log::channel('security')->warning('Banned IP attempted access', [
                'ip' => $ip,
                'path' => $request->path(),
            ]);
            abort(429, 'Too Many Requests. You have been temporarily banned.');
        }

        // Get current request count
        $requests = Cache::get($key, 0);

        // Check if over limit
        if ($requests >= $this->maxRequests) {
            // Check if should ban
            if ($requests >= $this->banThreshold) {
                Cache::put($banKey, true, now()->addMinutes($this->banDuration));
                Log::channel('security')->alert('IP banned for excessive requests', [
                    'ip' => $ip,
                    'requests' => $requests,
                ]);
            }

            Log::channel('security')->warning('Rate limit exceeded', [
                'ip' => $ip,
                'requests' => $requests,
            ]);

            return response()->json([
                'error' => 'Too Many Requests',
                'retry_after' => $this->decayMinutes * 60,
            ], 429)->withHeaders([
                'Retry-After' => $this->decayMinutes * 60,
                'X-RateLimit-Limit' => $this->maxRequests,
                'X-RateLimit-Remaining' => 0,
            ]);
        }

        // Increment counter
        Cache::put($key, $requests + 1, now()->addMinutes($this->decayMinutes));

        $response = $next($request);

        // Add rate limit headers
        $response->headers->set('X-RateLimit-Limit', (string) $this->maxRequests);
        $response->headers->set('X-RateLimit-Remaining', (string) max(0, $this->maxRequests - $requests - 1));

        return $response;
    }
}
