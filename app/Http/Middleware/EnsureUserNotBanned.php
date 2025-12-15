<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserNotBanned
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->is_banned) {
            return response()->json([
                'error' => 'Forbidden',
                'message' => 'Your account has been suspended.',
                'banned_at' => $user->banned_at?->toIso8601String(),
            ], 403);
        }

        return $next($request);
    }
}
