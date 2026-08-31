<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Schedule;
use App\Services\MqttService;
use Illuminate\Support\Carbon;

#[Signature('ac:schedule-worker')]
#[Description('Automatic AC schedule evaluator that sends MQTT commands according to active schedule rules')]
class RunAcScheduler extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(MqttService $mqttService)
    {
        $this->info("Starting AC Scheduler Automation Worker...");
        $this->info("Checking active schedule rules every 10 seconds...");

        while (true) {
            try {
                $now = Carbon::now('Asia/Jakarta');
                $nowTime = $now->format('H:i');
                $activeSchedules = Schedule::where('is_active', true)->get();

                $ac1DesiredOn = false;
                $ac2DesiredOn = false;

                foreach ($activeSchedules as $schedule) {
                    $start = Carbon::parse($schedule->start_time)->format('H:i');
                    $end = Carbon::parse($schedule->end_time)->format('H:i');

                    // Evaluate if current time falls in the active ON window
                    $isInsideWindow = false;
                    if ($start <= $end) {
                        // Normal window (e.g. 10:57 - 10:58)
                        $isInsideWindow = ($nowTime >= $start && $nowTime < $end);
                    } else {
                        // Overnight window (e.g. 18:00 - 06:00)
                        $isInsideWindow = ($nowTime >= $start || $nowTime < $end);
                    }

                    $targetAc = $schedule->target_ac ?? 'all';
                    if ($isInsideWindow) {
                        if ($targetAc === '1' || $targetAc === 'all') $ac1DesiredOn = true;
                        if ($targetAc === '2' || $targetAc === 'all') $ac2DesiredOn = true;
                    }

                    $this->line("[{$nowTime} WIB] Aturan '{$schedule->label}' (Target: {$targetAc}) ({$start} - {$end}): " . ($isInsideWindow ? 'AKTIF ON' : 'STANDBY OFF'));
                }

                // Dispatch state to Raspberry Pi via MQTT
                $mqttService->publish('pindad/ac/schedule', json_encode(['relay' => 1, 'command' => $ac1DesiredOn ? 'ON' : 'OFF']));
                $mqttService->publish('pindad/ac/schedule', json_encode(['relay' => 2, 'command' => $ac2DesiredOn ? 'ON' : 'OFF']));
            } catch (\Exception $e) {
                $this->error("Scheduler evaluation error: " . $e->getMessage());
            }

            sleep(10);
        }
    }
}
