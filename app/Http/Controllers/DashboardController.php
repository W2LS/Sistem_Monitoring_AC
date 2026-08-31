<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcLog;
use App\Models\Schedule;
use App\Models\Device;
use App\Services\MqttService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    protected MqttService $mqttService;

    public function __construct(MqttService $mqttService)
    {
        $this->mqttService = $mqttService;
    }

    /**
     * Get active shift description for a specific AC unit based on current time.
     */
    private function getActiveShiftText(int $acNum): string
    {
        $nowTime = Carbon::now('Asia/Jakarta')->format('H:i');
        
        $schedules = Schedule::where('is_active', true)
            ->where(function($q) use ($acNum) {
                $q->where('target_ac', (string) $acNum)
                  ->orWhere('target_ac', 'all')
                  ->orWhereNull('target_ac');
            })
            ->get();

        foreach ($schedules as $s) {
            $start = Carbon::parse($s->start_time)->format('H:i');
            $end = Carbon::parse($s->end_time)->format('H:i');

            $isInside = false;
            if ($start <= $end) {
                $isInside = ($nowTime >= $start && $nowTime < $end);
            } else {
                $isInside = ($nowTime >= $start || $nowTime < $end);
            }

            if ($isInside) {
                return "{$s->label} ({$start} - {$end} WIB)";
            }
        }

        $upcoming = $schedules->first();
        if ($upcoming) {
            $start = Carbon::parse($upcoming->start_time)->format('H:i');
            $end = Carbon::parse($upcoming->end_time)->format('H:i');
            return "Standby: {$upcoming->label} ({$start} - {$end} WIB)";
        }

        return $acNum === 1 ? "Shift Siang (06:00 - 18:00 WIB)" : "Shift Malam (18:00 - 06:00 WIB)";
    }

    /**
     * Display the dashboard page with Multi-Device Fleet support.
     */
    public function index(Request $request)
    {
        // Ensure default devices exist
        $devices = Device::all();
        if ($devices->isEmpty()) {
            Device::create([
                'device_id' => 'RPI3B_PINDAD_ROOM_1',
                'name' => 'Ruang Server Utama (Lt. 1)',
                'location' => 'Gedung Divisi Mutu & TI',
                'ip_address' => '192.168.197.64',
                'hardware_type' => 'Raspberry Pi 3B+',
                'status' => 'online',
                'auth_token' => '2zT3Crp6HA5DZQaxI26aftTrFUAuwo3F',
                'num_ac' => 2,
                'description' => 'Sistem pemantauan 2 unit pendingin Panasonic server utama.'
            ]);
            $devices = Device::all();
        }

        $selectedDeviceId = $request->query('device_id', $devices->first()->device_id ?? 'RPI3B_PINDAD_ROOM_1');
        $currentDevice = $devices->firstWhere('device_id', $selectedDeviceId) ?? $devices->first();

        // Get the latest log for AC 1 and AC 2 filtered by selected device
        $latestAc1 = AcLog::where('device_id', $selectedDeviceId)->where('active_ac', 'like', 'AC_1%')->latest('recorded_at')->first();
        if (!$latestAc1) {
            $latestAc1 = AcLog::where('active_ac', 'like', 'AC_1%')->latest('recorded_at')->first();
        }
        $latestAc2 = AcLog::where('device_id', $selectedDeviceId)->where('active_ac', 'like', 'AC_2%')->latest('recorded_at')->first();
        if (!$latestAc2) {
            $latestAc2 = AcLog::where('active_ac', 'like', 'AC_2%')->latest('recorded_at')->first();
        }

        // Get recent logs separated for AC1 and AC2
        $recentLogsAll = AcLog::where('device_id', $selectedDeviceId)->latest('recorded_at')->take(50)->get();
        if ($recentLogsAll->isEmpty()) {
            $recentLogsAll = AcLog::latest('recorded_at')->take(50)->get();
        }
        $recentLogsAc1 = AcLog::where('device_id', $selectedDeviceId)->where('active_ac', 'like', 'AC_1%')->latest('recorded_at')->take(50)->get();
        if ($recentLogsAc1->isEmpty()) {
            $recentLogsAc1 = AcLog::where('active_ac', 'like', 'AC_1%')->latest('recorded_at')->take(50)->get();
        }
        $recentLogsAc2 = AcLog::where('device_id', $selectedDeviceId)->where('active_ac', 'like', 'AC_2%')->latest('recorded_at')->take(50)->get();
        if ($recentLogsAc2->isEmpty()) {
            $recentLogsAc2 = AcLog::where('active_ac', 'like', 'AC_2%')->latest('recorded_at')->take(50)->get();
        }

        // Calculate dynamic real-time stats for each device in the fleet
        $fleetStats = [];
        foreach ($devices as $dev) {
            $devLast = AcLog::where('device_id', $dev->device_id)->latest('recorded_at')->first();
            $isDevOnline = false;
            if ($devLast && $devLast->recorded_at) {
                $isDevOnline = Carbon::parse($devLast->recorded_at)->diffInSeconds(now()) <= 60;
            } elseif ($dev->device_id === 'RPI3B_PINDAD_ROOM_1') {
                $anyLast = AcLog::latest('recorded_at')->first();
                $isDevOnline = $anyLast && Carbon::parse($anyLast->recorded_at)->diffInSeconds(now()) <= 60;
            }

            $devAc1 = AcLog::where('device_id', $dev->device_id)->where('active_ac', 'like', 'AC_1%')->latest('recorded_at')->first();
            $devAc2 = AcLog::where('device_id', $dev->device_id)->where('active_ac', 'like', 'AC_2%')->latest('recorded_at')->first();
            $c1 = $devAc1 ? (float)$devAc1->current_ampere : 0.0;
            $c2 = $devAc2 ? (float)$devAc2->current_ampere : 0.0;
            $w = round(($c1 + $c2) * 220);

            $fleetStats[$dev->device_id] = [
                'is_online' => $isDevOnline,
                'total_watt' => $w,
                'total_current' => round($c1 + $c2, 2),
                'last_seen' => $devLast ? Carbon::parse($devLast->recorded_at)->diffForHumans() : 'Standby',
            ];
        }

        // Get all schedules
        $schedules = Schedule::orderBy('start_time')->get();
        $shiftAc1 = $this->getActiveShiftText(1);
        $shiftAc2 = $this->getActiveShiftText(2);

        return view('dashboard', compact(
            'latestAc1', 'latestAc2', 'recentLogsAll', 'recentLogsAc1', 'recentLogsAc2', 
            'schedules', 'shiftAc1', 'shiftAc2', 'devices', 'selectedDeviceId', 'currentDevice', 'fleetStats'
        ));
    }

    /**
     * Get real-time telemetry data for AJAX polling (JSON format) with Multi-Device support.
     */
    public function apiLogs(Request $request)
    {
        $selectedDeviceId = $request->query('device_id', 'RPI3B_PINDAD_ROOM_1');

        $latestAc1 = AcLog::where('device_id', $selectedDeviceId)->where('active_ac', 'like', 'AC_1%')->latest('recorded_at')->first();
        if (!$latestAc1) {
            $latestAc1 = AcLog::where('active_ac', 'like', 'AC_1%')->latest('recorded_at')->first();
        }
        $latestAc2 = AcLog::where('device_id', $selectedDeviceId)->where('active_ac', 'like', 'AC_2%')->latest('recorded_at')->first();
        if (!$latestAc2) {
            $latestAc2 = AcLog::where('active_ac', 'like', 'AC_2%')->latest('recorded_at')->first();
        }

        $currentAc1 = $latestAc1 ? (float) $latestAc1->current_ampere : 0.0000;
        $currentAc2 = $latestAc2 ? (float) $latestAc2->current_ampere : 0.0000;
        $totalCurrent = round($currentAc1 + $currentAc2, 4);
        $estimatedWatt = round($totalCurrent * 220);

        // Check if device is actively sending telemetry (within last 60 seconds)
        $latestAny = AcLog::where('device_id', $selectedDeviceId)->latest('recorded_at')->first() ?? AcLog::latest('recorded_at')->first();
        $isDeviceOnline = false;
        if ($latestAny && $latestAny->recorded_at) {
            $secondsAgo = Carbon::parse($latestAny->recorded_at)->diffInSeconds(now());
            $isDeviceOnline = $secondsAgo <= 60;
        }

        // Take the latest 15 distinct chronological timestamps for Chart.js
        $logs = AcLog::where('device_id', $selectedDeviceId)->latest('recorded_at')->take(30)->get()->reverse()->values();
        if ($logs->isEmpty()) {
            $logs = AcLog::latest('recorded_at')->take(30)->get()->reverse()->values();
        }

        $chartLabels = [];
        $ac1Points = [];
        $ac2Points = [];

        foreach ($logs as $log) {
            $timeLabel = Carbon::parse($log->recorded_at)->format('H:i:s');
            if (!in_array($timeLabel, $chartLabels)) {
                $chartLabels[] = $timeLabel;
            }
        }
        $chartLabels = array_slice($chartLabels, -12);

        foreach ($chartLabels as $label) {
            $match1 = $logs->first(function ($l) use ($label) {
                return Carbon::parse($l->recorded_at)->format('H:i:s') === $label && str_contains($l->active_ac, 'AC_1');
            });
            $match2 = $logs->first(function ($l) use ($label) {
                return Carbon::parse($l->recorded_at)->format('H:i:s') === $label && str_contains($l->active_ac, 'AC_2');
            });

            $ac1Points[] = $match1 ? (float) $match1->current_ampere : $currentAc1;
            $ac2Points[] = $match2 ? (float) $match2->current_ampere : $currentAc2;
        }

        return response()->json([
            'status' => 'success',
            'device_id' => $selectedDeviceId,
            'latest_ac1' => $latestAc1 ? [
                'active_ac' => $latestAc1->active_ac,
                'current_ampere' => $currentAc1,
                'recorded_at' => Carbon::parse($latestAc1->recorded_at)->format('H:i:s'),
                'is_on' => str_contains($latestAc1->active_ac, 'ON'),
            ] : null,
            'latest_ac2' => $latestAc2 ? [
                'active_ac' => $latestAc2->active_ac,
                'current_ampere' => $currentAc2,
                'recorded_at' => Carbon::parse($latestAc2->recorded_at)->format('H:i:s'),
                'is_on' => str_contains($latestAc2->active_ac, 'ON'),
            ] : null,
            'shift_ac1' => $this->getActiveShiftText(1),
            'shift_ac2' => $this->getActiveShiftText(2),
            'total_current' => $totalCurrent,
            'estimated_watt' => $estimatedWatt,
            'device_online' => $isDeviceOnline,
            'chart' => [
                'labels' => $chartLabels,
                'ac1' => $ac1Points,
                'ac2' => $ac2Points
            ]
        ]);
    }

    /**
     * Toggle AC relay manually via MQTT.
     */
    public function toggleAc(Request $request)
    {
        $request->validate([
            'relay' => 'required|integer|in:1,2',
            'command' => 'required|string|in:ON,OFF'
        ]);

        $relay = (int) $request->input('relay');
        $command = strtoupper($request->input('command'));
        $deviceId = $request->input('device_id', 'RPI3B_PINDAD_ROOM_1');

        $payload = json_encode([
            'relay' => $relay,
            'command' => $command,
            'device_id' => $deviceId,
            'source' => 'manual'
        ]);

        AcLog::create([
            'device_id' => $deviceId,
            'active_ac' => "AC_{$relay}_{$command}",
            'current_ampere' => ($command === 'ON' ? 2.1500 : 0.0000),
            'recorded_at' => Carbon::now(),
        ]);

        $success = false;
        try {
            $success = $this->mqttService->publish('pindad/ac/schedule', $payload, 1);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning("MQTT Publish non-fatal error: " . $e->getMessage());
        }

        return response()->json([
            'status' => 'success', 
            'mqtt_sent' => $success,
            'relay' => $relay,
            'command' => $command,
            'device_id' => $deviceId
        ]);
    }

    /**
     * Store a newly created schedule.
     */
    public function storeSchedule(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:100',
            'start_time' => 'required',
            'end_time' => 'required',
            'target_ac' => 'nullable|string|in:1,2,all',
        ]);

        Schedule::create([
            'label' => $request->input('label'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'target_ac' => $request->input('target_ac', 'all'),
            'is_active' => true
        ]);

        return redirect()->back()->with('success', 'Jadwal baru berhasil disimpan!');
    }

    /**
     * Update an existing schedule.
     */
    public function updateSchedule(Request $request, $id)
    {
        $request->validate([
            'label' => 'required|string|max:100',
            'start_time' => 'required',
            'end_time' => 'required',
            'target_ac' => 'nullable|string|in:1,2,all',
        ]);

        $schedule = Schedule::findOrFail($id);
        $schedule->update([
            'label' => $request->input('label'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'target_ac' => $request->input('target_ac', 'all'),
        ]);

        return redirect()->back()->with('success', 'Aturan jadwal berhasil diperbarui!');
    }

    /**
     * Toggle the status of a schedule.
     */
    public function toggleSchedule($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->is_active = !$schedule->is_active;
        $schedule->save();

        return redirect()->back()->with('success', 'Status jadwal berhasil diubah!');
    }

    /**
     * Delete a schedule.
     */
    public function deleteSchedule($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return redirect()->back()->with('success', 'Jadwal berhasil dihapus!');
    }

    /**
     * Store a newly created IoT Device in Fleet.
     */
    public function storeDevice(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'location' => 'required|string|max:150',
            'device_id' => 'required|string|max:100',
            'ip_address' => 'nullable|string|max:50',
            'hardware_type' => 'nullable|string|max:100',
            'num_ac' => 'nullable|integer|min:1|max:8',
            'description' => 'nullable|string|max:300',
        ]);

        $deviceId = strtoupper(str_replace([' ', '-'], '_', $request->input('device_id')));

        Device::create([
            'name' => $request->input('name'),
            'location' => $request->input('location'),
            'device_id' => $deviceId,
            'ip_address' => $request->input('ip_address', '192.168.196.x'),
            'hardware_type' => $request->input('hardware_type', 'Raspberry Pi 3B+'),
            'status' => 'standby',
            'auth_token' => 'PINDAD_' . strtoupper(Str::random(16)),
            'num_ac' => (int) $request->input('num_ac', 2),
            'description' => $request->input('description', 'Unit monitoring pendingin ruangan server PT PINDAD.'),
        ]);

        return redirect()->back()->with('success', 'Perangkat IoT baru berhasil didaftarkan ke Fleet Platform!');
    }

    /**
     * Update an existing IoT Device in Fleet.
     */
    public function updateDevice(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'location' => 'required|string|max:150',
            'ip_address' => 'nullable|string|max:50',
            'hardware_type' => 'nullable|string|max:100',
            'num_ac' => 'nullable|integer|min:1|max:8',
            'description' => 'nullable|string|max:300',
        ]);

        $device = Device::findOrFail($id);
        $device->update($request->only(['name', 'location', 'ip_address', 'hardware_type', 'num_ac', 'description']));

        return redirect()->back()->with('success', 'Informasi perangkat berhasil diperbarui!');
    }

    /**
     * Delete an IoT Device from Fleet.
     */
    public function deleteDevice($id)
    {
        $device = Device::findOrFail($id);
        if ($device->device_id === 'RPI3B_PINDAD_ROOM_1') {
            return redirect()->back()->with('error', 'Perangkat utama Ruang Server 1 tidak boleh dihapus.');
        }
        $device->delete();

        return redirect()->back()->with('success', 'Perangkat berhasil dihapus dari Fleet!');
    }

    /**
     * Master Fleet Control: Turn all ACs ON / OFF across all rooms.
     */
    public function masterControl(Request $request)
    {
        $command = strtoupper($request->input('command', 'ON'));

        $payload1 = json_encode(['relay' => 1, 'command' => $command, 'source' => 'manual']);
        $payload2 = json_encode(['relay' => 2, 'command' => $command, 'source' => 'manual']);

        try {
            $this->mqttService->publish('pindad/ac/schedule', $payload1);
            $this->mqttService->publish('pindad/ac/schedule', $payload2);
        } catch (\Exception $e) {}

        return redirect()->back()->with('success', "Perintah Massal ({$command}) berhasil dikirim ke seluruh unit AC!");
    }

    /**
     * Export telemetry logs to CSV format with Device filter.
     */
    public function exportCsv(Request $request)
    {
        $unit = $request->query('unit', 'all'); // 'all', 'ac1', 'ac2'
        $deviceId = $request->query('device_id', 'all');
        
        $query = AcLog::latest('recorded_at');
        if ($deviceId !== 'all') {
            $query->where('device_id', $deviceId);
        }

        if ($unit === 'ac1') {
            $query->where('active_ac', 'like', 'AC_1%');
        } elseif ($unit === 'ac2') {
            $query->where('active_ac', 'like', 'AC_2%');
        }
        
        $logs = $query->take(500)->get();
        
        $fileName = 'PINDAD_AC_TELEMETRY_' . strtoupper($unit) . '_' . date('Ymd_His') . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        
        $columns = ['ID Log', 'ID Perangkat', 'Status Telemetri', 'Arus Listrik (Ampere)', 'Estimasi Beban (Watt)', 'Waktu Pencatatan'];
        
        $callback = function() use ($logs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($logs as $log) {
                $watt = round($log->current_ampere * 220, 2);
                fputcsv($file, [
                    $log->id,
                    $log->device_id,
                    $log->active_ac,
                    number_format($log->current_ampere, 4),
                    $watt . ' W',
                    $log->recorded_at
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
