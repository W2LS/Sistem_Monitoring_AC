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
                $nowTime = Carbon::now()->format('H:i:s');
                $activeSchedules = Schedule::where('is_active', true)->get();

                foreach ($activeSchedules as $schedule) {
                    $start = $schedule->start_time;
                    $end = $schedule->end_time;

                    // Evaluate if current time falls in the active ON window
                    $isInsideWindow = false;
                    if ($start <= $end) {
                        // Normal window (e.g. 07:00 - 15:00)
                        $isInsideWindow = ($nowTime >= $start && $nowTime <= $end);
                    } else {
                        // Overnight window (e.g. 23:00 - 07:00)
                        $isInsideWindow = ($nowTime >= $start || $nowTime <= $end);
                    }

                    $command = $isInsideWindow ? 'ON' : 'OFF';
                    $targetAc = $schedule->target_ac ?? 'all';

                    $this->line("[{$nowTime}] Rule '{$schedule->label}' (Target: {$targetAc}) ({$start} - {$end}): " . ($isInsideWindow ? 'ACTIVE_WINDOW (ON)' : 'OUTSIDE_WINDOW (OFF)'));

                    // Send MQTT execution
                    if ($targetAc === '1' || $targetAc === 'all') {
                        $mqttService->publish('pindad/ac/schedule', json_encode(['relay' => 1, 'command' => $command]));
                    }
                    if ($targetAc === '2' || $targetAc === 'all') {
                        $mqttService->publish('pindad/ac/schedule', json_encode(['relay' => 2, 'command' => $command]));
                    }
                }
            } catch (\Exception $e) {
                $this->error("Scheduler evaluation error: " . $e->getMessage());
            }

            sleep(10);
        }
    }
}
