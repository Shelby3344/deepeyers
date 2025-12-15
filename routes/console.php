<?php

declare(strict_types=1);

use App\Jobs\CleanupOldSessionsJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Cleanup old sessions daily at 3 AM
Schedule::job(new CleanupOldSessionsJob(30))->dailyAt('03:00');
