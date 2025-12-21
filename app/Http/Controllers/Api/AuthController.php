<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Busca o plano Ghost para novos usuários
        $ghostPlan = \App\Models\Plan::where('slug', 'ghost')->first();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'plan_id' => $ghostPlan?->id,
        ]);

        // ✅ Token com abilities limitadas
        $token = $user->createToken('api-token', [
            'chat:read',
            'chat:write',
            'profile:read',
        ])->plainTextToken;

        // Log de registro
        Log::channel('security')->info('User registered', [
            'user_id' => $user->id,
            'ip' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'data' => [
                'user' => $this->sanitizeUser($user),
                'token' => $token,
                'expires_at' => now()->addDay()->toIso8601String(),
            ],
        ], 201);
    }

    /**
     * Login user
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        // ✅ Resposta genérica - não indica se email existe ou senha está errada
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            // Delay anti-bruteforce para dificultar ataques
            usleep(random_int(100000, 300000));
            
            // Log de tentativa falha
            Log::channel('security')->warning('Login failed', [
                'email' => $validated['email'],
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            throw ValidationException::withMessages([
                'credentials' => ['Authentication failed.'],
            ]);
        }

        if ($user->is_banned) {
            // Mesma mensagem genérica para não revelar status do usuário
            usleep(random_int(100000, 300000));
            
            Log::channel('security')->warning('Banned user login attempt', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
            ]);
            
            throw ValidationException::withMessages([
                'credentials' => ['Authentication failed.'],
            ]);
        }

        // ✅ Revoga TODOS os tokens anteriores (single session por segurança)
        $user->tokens()->delete();
        
        // ✅ Regenera sessão para prevenir session fixation
        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        // ✅ Token com abilities limitadas
        $token = $user->createToken('api-token', [
            'chat:read',
            'chat:write',
            'profile:read',
            'profile:write',
        ])->plainTextToken;

        // ✅ Atualiza informações de login
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        // Log de login bem-sucedido
        Log::channel('security')->info('User login', [
            'user_id' => $user->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'message' => 'Login successful',
            'data' => [
                'user' => $this->sanitizeUser($user),
                'token' => $token,
                'expires_at' => now()->addDay()->toIso8601String(),
            ],
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request): JsonResponse
    {
        // ✅ Remove TODOS os tokens do usuário
        $request->user()->tokens()->delete();

        Log::channel('security')->info('User logout', [
            'user_id' => $request->user()->id,
            'ip' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Get current user
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated',
            ], 401);
        }

        // Carrega o plano
        $user->loadMissing('plan');

        return response()->json([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'avatar' => $user->avatar_url ?? null,
                'daily_requests_remaining' => $user->getDailyRequestsRemaining(),
                'plan' => $user->plan ? [
                    'id' => $user->plan->id,
                    'name' => $user->plan->name,
                    'allowed_profiles' => $user->plan->allowed_profiles ?? ['pentest'],
                ] : null,
            ],
        ]);
    }

    /**
     * Sanitiza dados do usuário para não expor informações sensíveis
     */
    private function sanitizeUser(User $user): array
    {
        // Carrega o plano se não estiver carregado
        $user->loadMissing('plan');
        
        return [
            'id' => $user->id,
            'name' => $user->name,
            'role' => $user->role,
            'avatar' => $user->avatar_url ?? null,
            'plan' => $user->plan ? [
                'id' => $user->plan->id,
                'name' => $user->plan->name,
                'allowed_profiles' => $user->plan->allowed_profiles ?? ['pentest'],
            ] : null,
        ];
    }
}
