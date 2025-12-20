<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Exibe a página de perfil do usuário
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $user->load(['plan', 'subscription']);
        
        $plans = Plan::orderBy('price')->get();
        
        return view('profile', [
            'user' => $user,
            'plans' => $plans,
        ]);
    }

    /**
     * Retorna dados do perfil via API
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated',
            ], 401);
        }
        
        $user->load(['plan', 'subscription']);
        
        $currentPlan = $user->plan ?? Plan::where('slug', 'free')->first();
        $subscription = $user->subscription;
        
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                'created_at' => $user->created_at->format('d/m/Y'),
                'member_since' => $user->created_at->diffForHumans(),
            ],
            'plan' => [
                'name' => $currentPlan->name,
                'slug' => $currentPlan->slug,
                'price' => $currentPlan->formatted_price,
                'billing_cycle' => $currentPlan->billing_cycle,
                'requests_per_day' => $currentPlan->requests_per_day,
                'requests_per_month' => $currentPlan->requests_per_month,
                'features' => $currentPlan->features,
                'allowed_profiles' => $currentPlan->allowed_profiles,
            ],
            'subscription' => $subscription ? [
                'status' => $subscription->status,
                'is_active' => $subscription->isActive(),
                'days_remaining' => $subscription->daysRemaining(),
                'ends_at' => $subscription->ends_at?->format('d/m/Y'),
            ] : null,
            'usage' => [
                'daily_requests' => $user->daily_requests ?? 0,
                'daily_limit' => $user->getDailyLimit(),
                'remaining' => $user->getDailyRequestsRemaining(),
            ],
        ]);
    }

    /**
     * Atualiza informações do perfil
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $request->user()->id,
            'current_password' => 'required_with:new_password',
            'new_password' => 'sometimes|min:8|confirmed',
        ]);

        $user = $request->user();

        if (isset($validated['name'])) {
            $user->name = $validated['name'];
        }

        if (isset($validated['email'])) {
            $user->email = $validated['email'];
        }

        if (isset($validated['new_password'])) {
            if (!password_verify($validated['current_password'], $user->password)) {
                return response()->json([
                    'message' => 'Senha atual incorreta.',
                ], 422);
            }
            $user->password = bcrypt($validated['new_password']);
        }

        $user->save();

        return response()->json([
            'message' => 'Perfil atualizado com sucesso.',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    /**
     * Upload de avatar do usuário
     */
    public function uploadAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user = $request->user();

        // Remove avatar antigo se existir
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Salva novo avatar
        $path = $request->file('avatar')->store('avatars', 'public');
        
        $user->avatar = $path;
        $user->save();

        return response()->json([
            'message' => 'Avatar atualizado com sucesso.',
            'avatar' => asset('storage/' . $path),
        ]);
    }

    /**
     * Remove avatar do usuário
     */
    public function deleteAvatar(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->avatar = null;
        $user->save();

        return response()->json([
            'message' => 'Avatar removido com sucesso.',
        ]);
    }
}
