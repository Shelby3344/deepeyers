<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Pentest',
                'slug' => 'free',
                'description' => 'Plano gratuito para iniciantes',
                'price' => 0,
                'billing_cycle' => 'monthly',
                'requests_per_day' => 10,
                'requests_per_month' => 300,
                'features' => [
                    'Perfil Pentest',
                    'Chat com IA',
                    '10 requisições por dia',
                    '1 sessão de chat',
                ],
                'allowed_profiles' => ['pentest'],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Red Team',
                'slug' => 'redteam',
                'description' => 'Para profissionais de segurança',
                'price' => 49.00,
                'billing_cycle' => 'monthly',
                'requests_per_day' => 200,
                'requests_per_month' => 6000,
                'features' => [
                    'Perfil Pentest + Red Team',
                    'Chat com IA',
                    '200 requisições por dia',
                    'Sessões ilimitadas',
                    'Terminal Integrado',
                    'Scanner de Vulnerabilidades',
                    'Checklist OWASP',
                ],
                'allowed_profiles' => ['pentest', 'redteam'],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Full Attack',
                'slug' => 'fullattack',
                'description' => 'Acesso completo sem restrições',
                'price' => 99.00,
                'billing_cycle' => 'monthly',
                'requests_per_day' => 500,
                'requests_per_month' => 15000,
                'features' => [
                    'Todos os Perfis',
                    'Chat com IA',
                    '500 requisições por dia',
                    'Sessões ilimitadas',
                    'Terminal Integrado',
                    'Scanner de Vulnerabilidades',
                    'Checklist OWASP',
                    'Geração de Relatórios',
                    'Suporte Prioritário',
                ],
                'allowed_profiles' => ['pentest', 'redteam', 'fullattack'],
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
