<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Mail\PlanExpiringEmail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifyExpiringPlans extends Command
{
    protected $signature = 'plans:notify-expiring {--days=7 : Dias antes da expiração para notificar}';

    protected $description = 'Envia notificações para usuários com planos prestes a expirar';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        
        $this->info("Verificando planos que expiram em {$days} dias...");

        // Busca usuários com subscription ativa que expira nos próximos X dias
        $users = User::whereHas('subscription', function ($query) use ($days) {
            $query->where('status', 'active')
                  ->whereBetween('ends_at', [now(), now()->addDays($days)]);
        })->with(['plan', 'subscription'])->get();

        $count = 0;

        foreach ($users as $user) {
            $daysRemaining = $user->subscription->daysRemaining();
            
            // Notifica em 7, 3 e 1 dia antes
            if (in_array($daysRemaining, [7, 3, 1])) {
                try {
                    Mail::to($user->email)->queue(new PlanExpiringEmail($user, $daysRemaining));
                    $count++;
                    $this->line("✓ Notificação enviada para {$user->email} ({$daysRemaining} dias restantes)");
                } catch (\Exception $e) {
                    Log::error('Failed to send plan expiring email', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                    ]);
                    $this->error("✗ Falha ao enviar para {$user->email}: {$e->getMessage()}");
                }
            }
        }

        $this->info("Total de notificações enviadas: {$count}");

        return Command::SUCCESS;
    }
}
