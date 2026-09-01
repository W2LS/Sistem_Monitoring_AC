<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Schedule;
use App\Models\Device;
use App\Services\MqttService;
use Illuminate\Support\Carbon;

#[Signature('ac:schedule-worker')]
#[Description('Automatic AC schedule evaluator that sends MQTT commands according to active schedule rules per device')]
class RunAcScheduler extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(MqttService $mqttService)
    {
        $this->info("Starting AC Scheduler Automation Worker (Per-Device Multi-Tenancy)...");
        $this->info("Checking active schedule rules every 5 seconds...");

        $lastStates = []; // Key: device_id . '_' . relay -> bool

        while (true) {
            try {
                $now = Carbon::now('Asia/Jakarta');
                $nowTime = $now->format('H:i');

                // Get all active schedules
                $activeSchedules = Schedule::where('is_active', true)->get();

                // Distinct devices to evaluate
                $devices = Device::all();
                $deviceIds = $devices->pluck('device_id')->filter()->unique()->values()->toArray();
                if (!in_array('RPI3B_PINDAD_ROOM_1', $deviceIds)) {
                    $deviceIds[] = 'RPI3B_PINDAD_ROOM_1';
                }

                foreach ($deviceIds as $devId) {
                    $devSchedules = $activeSchedules->filter(function($s) use ($devId) {
                        if ($devId === 'RPI3B_PINDAD_ROOM_1') {
                            return empty($s->device_id) || $s->device_id === 'RPI3B_PINDAD_ROOM_1';
                        }
                        return $s->device_id === $devId;
                    });

                    $ac1DesiredOn = false;
                    $ac2DesiredOn = false;

                    foreach ($devSchedules as $schedule) {
                        $start = Carbon::parse($schedule->start_time)->format('H:i');
                        $end = Carbon::parse($schedule->end_time)->format('H:i');

                        $isInsideWindow = false;
                        if ($start <= $end) {
                            $isInsideWindow = ($nowTime >= $start && $nowTime < $end);
                        } else {
                            $isInsideWindow = ($nowTime >= $start || $nowTime < $end);
                        }

                        $targetAc = $schedule->target_ac ?? 'all';
                        if ($isInsideWindow) {
                            if ($targetAc === '1' || $targetAc === 'all') $ac1DesiredOn = true;
                            if ($targetAc === '2' || $targetAc === 'all') $ac2DesiredOn = true;
                        }
                    }

                    // AC 1 evaluation for this device
                    $key1 = "{$devId}_1";
                    if (!isset($lastStates[$key1]) || $lastStates[$key1] !== $ac1DesiredOn) {
                        $lastStates[$key1] = $ac1DesiredOn;
                        $cmd = $ac1DesiredOn ? 'ON' : 'OFF';
                        if ($devId === 'RPI3B_PINDAD_ROOM_1') {
                            $mqttService->publish('pindad/ac/schedule', json_encode(['relay' => 1, 'command' => $cmd, 'source' => 'schedule']));
                        }
                        $mqttService->publish("pindad/devices/{$devId}/schedule", json_encode(['relay' => 1, 'command' => $cmd, 'source' => 'schedule']));
                        $this->info("[{$nowTime} WIB] [{$devId}] Sinyal Transisi Terkirim -> AC 1: {$cmd}");
                    }

                    // AC 2 evaluation for this device
                    $key2 = "{$devId}_2";
                    if (!isset($lastStates[$key2]) || $lastStates[$key2] !== $ac2DesiredOn) {
                        $lastStates[$key2] = $ac2DesiredOn;
                        $cmd = $ac2DesiredOn ? 'ON' : 'OFF';
                        if ($devId === 'RPI3B_PINDAD_ROOM_1') {
                            $mqttService->publish('pindad/ac/schedule', json_encode(['relay' => 2, 'command' => $cmd, 'source' => 'schedule']));
                        }
                        $mqttService->publish("pindad/devices/{$devId}/schedule", json_encode(['relay' => 2, 'command' => $cmd, 'source' => 'schedule']));
                        $this->info("[{$nowTime} WIB] [{$devId}] Sinyal Transisi Terkirim -> AC 2: {$cmd}");
                    }
                }
            } catch (\Exception $e) {
                $this->error("Scheduler evaluation error: " . $e->getMessage());
            }

            sleep(5);
        }
    }
}
