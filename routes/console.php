<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\AcLog;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Auto-Pruning Scheduler: Pembersihan harian log telemetri AC pada jam 02:00 WIB
Schedule::command('sikomat:daily-log-pruning')->dailyAt('02:00');

Artisan::command('sikomat:daily-log-pruning', function () {
    $cutoff = now()->subDays(30);
    $deleted = AcLog::where('created_at', '<', $cutoff)
        ->orWhere('recorded_at', '<', $cutoff->toIso8601String())
        ->delete();
    $this->info("✅ [SIKOMAT AUTO-PRUNING] Berhasil membersihkan {$deleted} log telemetri lawas (retensi 30 hari).");
})->purpose('Pruning harian log telemetri mentah AC untuk optimasi storage');
