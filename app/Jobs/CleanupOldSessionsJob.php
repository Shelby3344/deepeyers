<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\AbuseLog;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CleanupOldSessionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300;

    public function __construct(
        private readonly int $daysOld = 30,
    ) {}

    public function handle(): void
    {
        $cutoff = now()->subDays($this->daysOld);

        // Delete old sessions (cascades to messages)
        $deletedSessions = ChatSession::where('updated_at', '<', $cutoff)
            ->where('is_active', false)
            ->delete();

        // Clean old abuse logs
        $deletedLogs = AbuseLog::where('created_at', '<', $cutoff)->delete();

        Log::info('Cleanup completed', [
            'deleted_sessions' => $deletedSessions,
            'deleted_abuse_logs' => $deletedLogs,
        ]);
    }
}
