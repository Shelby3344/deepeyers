<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use App\Models\Plan;
use Illuminate\Foundation\Http\FormRequest;

class CreateSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Pegar perfis permitidos do plano do usuário
        $user = $this->user();
        $plan = $user?->plan;
        
        // Fallback seguro para allowed_profiles
        $allowedProfiles = ['pentest']; // default
        
        if ($plan && is_array($plan->allowed_profiles) && !empty($plan->allowed_profiles)) {
            $allowedProfiles = $plan->allowed_profiles;
        }

        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'target_domain' => ['required', 'string', 'max:255'],
            'profile' => ['sometimes', 'string', 'in:' . implode(',', $allowedProfiles)],
        ];
    }

    public function messages(): array
    {
        return [
            'title.max' => 'O título não pode exceder 255 caracteres.',
            'target_domain.required' => 'O domínio do alvo é obrigatório.',
            'target_domain.max' => 'O domínio do alvo não pode exceder 255 caracteres.',
            'profile.in' => 'Perfil inválido selecionado. Faça upgrade do seu plano para acessar mais perfis.',
        ];
    }
}
