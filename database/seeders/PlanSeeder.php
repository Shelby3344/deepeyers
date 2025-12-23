<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
                'features' => json_encode([
                    'Perfil Pentest',
                    'Chat com IA',
                    '10 requisições por dia',
                    '1 sessão de chat',
                ]),
                'allowed_profiles' => json_encode(['pentest']),
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
                'features' => json_encode([
                    'Perfil Pentest + Red Team',
                    'Chat com IA',
                    '200 requisições por dia',
                    'Sessões ilimitadas',
                    'Terminal Integrado',
                    'Scanner de Vulnerabilidades',
                    'Checklist OWASP',
                ]),
                'allowed_profiles' => json_encode(['pentest', 'redteam']),
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
                'features' => json_encode([
                    'Todos os Perfis',
                    'Chat com IA',
                    '500 requisições por dia',
                    'Sessões ilimitadas',
                    'Terminal Integrado',
                    'Scanner de Vulnerabilidades',
                    'Checklist OWASP',
                    'Geração de Relatórios',
                    'Suporte Prioritário',
                ]),
                'allowed_profiles' => json_encode(['pentest', 'redteam', 'fullattack']),
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            // Usar query raw para PostgreSQL com cast explícito de boolean
            DB::statement("
                INSERT INTO plans (name, slug, description, price, billing_cycle, requests_per_day, requests_per_month, features, allowed_profiles, is_active, sort_order, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, true, ?, NOW(), NOW())
                ON CONFLICT (slug) DO UPDATE SET
                    name = EXCLUDED.name,
                    description = EXCLUDED.description,
                    price = EXCLUDED.price,
                    billing_cycle = EXCLUDED.billing_cycle,
                    requests_per_day = EXCLUDED.requests_per_day,
                    requests_per_month = EXCLUDED.requests_per_month,
                    features = EXCLUDED.features,
                    allowed_profiles = EXCLUDED.allowed_profiles,
                    is_active = true,
                    sort_order = EXCLUDED.sort_order,
                    updated_at = NOW()
            ", [
                $plan['name'],
                $plan['slug'],
                $plan['description'],
                $plan['price'],
                $plan['billing_cycle'],
                $plan['requests_per_day'],
                $plan['requests_per_month'],
                $plan['features'],
                $plan['allowed_profiles'],
                $plan['sort_order'],
            ]);
        }
    }
}
