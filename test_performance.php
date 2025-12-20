<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Teste de Performance do Supabase ===\n\n";

// Teste 1: Query simples
$start = microtime(true);
$user = DB::table('users')->first();
$time1 = (microtime(true) - $start) * 1000;
echo "1. Query users->first(): " . round($time1) . "ms\n";

// Teste 2: Segunda query (conexão já aberta)
$start = microtime(true);
$plans = DB::table('plans')->get();
$time2 = (microtime(true) - $start) * 1000;
echo "2. Query plans->get(): " . round($time2) . "ms\n";

// Teste 3: Query com join
$start = microtime(true);
$userWithPlan = DB::table('users')
    ->leftJoin('plans', 'users.plan_id', '=', 'plans.id')
    ->first();
$time3 = (microtime(true) - $start) * 1000;
echo "3. Query com JOIN: " . round($time3) . "ms\n";

// Teste 4: Ping simples
$start = microtime(true);
DB::select('SELECT 1');
$time4 = (microtime(true) - $start) * 1000;
echo "4. Ping (SELECT 1): " . round($time4) . "ms\n";

echo "\n=== Resultados ===\n";
$total = $time1 + $time2 + $time3 + $time4;
echo "Tempo total: " . round($total) . "ms\n";
echo "Média por query: " . round($total / 4) . "ms\n";

if ($time1 > 200) {
    echo "\n⚠️ AVISO: A conexão está lenta (>200ms por query)\n";
    echo "Isso é normal para Supabase em localhost devido à latência de rede.\n";
    echo "Em produção com servidor próximo, será mais rápido.\n";
} else {
    echo "\n✅ Velocidade aceitável!\n";
}
