<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\NewLoginAlertEmail;
use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Domínios de email permitidos (principais provedores)
     */
    private array $allowedEmailDomains = [
        // Google
        'gmail.com', 'googlemail.com',
        // Microsoft
        'outlook.com', 'hotmail.com', 'live.com', 'msn.com', 'outlook.com.br', 'hotmail.com.br',
        // Yahoo
        'yahoo.com', 'yahoo.com.br', 'ymail.com',
        // Apple
        'icloud.com', 'me.com', 'mac.com',
        // ProtonMail
        'protonmail.com', 'proton.me', 'pm.me',
        // Outros populares
        'zoho.com', 'aol.com', 'mail.com', 'gmx.com', 'gmx.net',
        // Brasil
        'uol.com.br', 'bol.com.br', 'terra.com.br', 'ig.com.br', 'globo.com', 'r7.com',
    ];

    /**
     * Register a new user
     */
    public function register(Request $request): JsonResponse
    {
        // Validação forte de senha e email
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'email' => [
                'required', 
                'string', 
                'email:rfc,dns',
                'max:255', 
                'unique:users',
                function ($attribute, $value, $fail) {
                    $domain = strtolower(substr(strrchr($value, "@"), 1));
                    if (!in_array($domain, $this->allowedEmailDomains)) {
                        $fail('Use um email de provedor válido (Gmail, Outlook, Yahoo, etc).');
                    }
                },
            ],
            'password' => [
                'required', 
                'string', 
                'min:8',
                'max:128',
                'confirmed',
                'regex:/[a-z]/',      // Pelo menos uma letra minúscula
                'regex:/[A-Z]/',      // Pelo menos uma letra maiúscula  
                'regex:/[0-9]/',      // Pelo menos um número
                'regex:/[@$!%*#?&.]/', // Pelo menos um caractere especial
            ],
        ], [
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
            'password.regex' => 'A senha deve conter: maiúscula, minúscula, número e caractere especial (@$!%*#?&.)',
            'password.confirmed' => 'As senhas não conferem.',
            'email.email' => 'Email inválido ou domínio não existe.',
            'email.unique' => 'Este email já está cadastrado.',
        ]);

        // Busca o plano Free (básico) para novos usuários
        $freePlan = \App\Models\Plan::where('slug', 'free')->first();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'plan_id' => $freePlan?->id,
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

        // Envia email de boas-vindas
        try {
            Mail::to($user->email)->queue(new WelcomeEmail($user));
        } catch (\Exception $e) {
            Log::error('Failed to send welcome email', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }

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
        ], [
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'Email inválido.',
            'password.required' => 'A senha é obrigatória.',
        ]);

        $user = User::where('email', $validated['email'])->first();

        // Verifica se usuário existe
        if (!$user) {
            usleep(random_int(100000, 300000));
            
            Log::channel('security')->warning('Login failed - user not found', [
                'email' => $validated['email'],
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            throw ValidationException::withMessages([
                'email' => ['Usuário não encontrado.'],
            ]);
        }

        // Verifica senha
        if (!Hash::check($validated['password'], $user->password)) {
            usleep(random_int(100000, 300000));
            
            Log::channel('security')->warning('Login failed - wrong password', [
                'email' => $validated['email'],
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            throw ValidationException::withMessages([
                'password' => ['Senha incorreta.'],
            ]);
        }

        if ($user->is_banned) {
            usleep(random_int(100000, 300000));
            
            Log::channel('security')->warning('Banned user login attempt', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
            ]);
            
            throw ValidationException::withMessages([
                'email' => ['Sua conta foi suspensa. Entre em contato com o suporte.'],
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
        $previousIp = $user->last_login_ip;
        $currentIp = $request->ip();
        
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $currentIp,
        ]);

        // Envia alerta se for um novo IP
        if ($previousIp && $previousIp !== $currentIp) {
            try {
                $location = $this->getLocationFromIp($currentIp);
                Mail::to($user->email)->queue(new NewLoginAlertEmail(
                    $user,
                    $currentIp,
                    $request->userAgent() ?? 'Unknown',
                    $location
                ));
            } catch (\Exception $e) {
                Log::error('Failed to send new login alert email', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

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

    /**
     * Obtém localização aproximada do IP
     */
    private function getLocationFromIp(string $ip): string
    {
        try {
            // IPs locais/privados
            if (in_array($ip, ['127.0.0.1', '::1']) || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
                return 'Rede Local';
            }

            // Usa API gratuita para geolocalização
            $response = @file_get_contents("http://ip-api.com/json/{$ip}?fields=city,regionName,country");
            if ($response) {
                $data = json_decode($response, true);
                if ($data && isset($data['city'])) {
                    return "{$data['city']}, {$data['regionName']}, {$data['country']}";
                }
            }
        } catch (\Exception $e) {
            Log::warning('Failed to get IP location', ['ip' => $ip, 'error' => $e->getMessage()]);
        }

        return 'Localização desconhecida';
    }
}
