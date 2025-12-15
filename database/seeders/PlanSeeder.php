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
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Plano gratuito para testes',
                'price' => 0,
                'billing_cycle' => 'monthly',
                'requests_per_day' => 10,
                'requests_per_month' => 100,
                'features' => [
                    'IA 100% destravada para pentest',
                    'Acesso ao OlhoDeDeus I.A básico',
                    '10 requisições por dia',
                    'Histórico de 7 dias',
                ],
                'allowed_profiles' => ['pentest'],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Hacker',
                'slug' => 'hacker',
                'description' => 'Para pentesters iniciantes',
                'price' => 49.90,
                'billing_cycle' => 'monthly',
                'requests_per_day' => 100,
                'requests_per_month' => 3000,
                'features' => [
                    'IA 100% destravada - sem censura',
                    'Acesso ao OlhoDeDeus I.A completo',
                    '100 requisições por dia',
                    'Histórico de 30 dias',
                    'Suporte por email',
                ],
                'allowed_profiles' => ['pentest'],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Red Team',
                'slug' => 'redteam',
                'description' => 'Para profissionais de Red Team',
                'price' => 149.90,
                'billing_cycle' => 'monthly',
                'requests_per_day' => 500,
                'requests_per_month' => 15000,
                'features' => [
                    'IA totalmente destravada para ataques',
                    'OlhoDeDeus I.A + BlackSentinel',
                    '500 requisições por dia',
                    'Histórico ilimitado',
                    'Perfis avançados de ataque',
                    'Suporte prioritário',
                ],
                'allowed_profiles' => ['pentest', 'redteam'],
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Ghost Operator',
                'slug' => 'ghost',
                'description' => 'Acesso total sem restrições',
                'price' => 299.90,
                'billing_cycle' => 'monthly',
                'requests_per_day' => 9999,
                'requests_per_month' => 99999,
                'features' => [
                    '🔓 IA 100% DESTRAVADA - SEM LIMITES',
                    'Zero censura, zero restrições',
                    'OlhoDeDeus I.A + BlackSentinel + GhostOps',
                    'Requisições ilimitadas',
                    'Todos os perfis de ataque',
                    'Suporte VIP 24/7',
                ],
                'allowed_profiles' => ['pentest', 'redteam', 'offensive'],
                'is_active' => true,
                'sort_order' => 4,
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
