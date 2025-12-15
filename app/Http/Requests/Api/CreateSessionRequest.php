<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $allowedProfiles = array_keys(config('deepseek.system_prompts', []));

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
            'profile.in' => 'Perfil inválido selecionado.',
        ];
    }
}
