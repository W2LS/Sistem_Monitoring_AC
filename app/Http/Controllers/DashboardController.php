<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcLog;
use App\Models\Schedule;
use App\Services\MqttService;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    protected MqttService $mqttService;

    public function __construct(MqttService $mqttService)
    {
        $this->mqttService = $mqttService;
    }

    /**
     * Display the dashboard page.
     */
    public function index()
    {
        // Get the latest log for AC 1 and AC 2
        $latestAc1 = AcLog::where('active_ac', 'like', 'AC_1%')->latest('recorded_at')->first();
        $latestAc2 = AcLog::where('active_ac', 'like', 'AC_2%')->latest('recorded_at')->first();

        // Get recent logs separated for AC1 and AC2
        $recentLogsAll = AcLog::latest('recorded_at')->take(50)->get();
        $recentLogsAc1 = AcLog::where('active_ac', 'like', 'AC_1%')->latest('recorded_at')->take(50)->get();
        $recentLogsAc2 = AcLog::where('active_ac', 'like', 'AC_2%')->latest('recorded_at')->take(50)->get();

        // Get all schedules
        $schedules = Schedule::orderBy('start_time')->get();

        return view('dashboard', compact('latestAc1', 'latestAc2', 'recentLogsAll', 'recentLogsAc1', 'recentLogsAc2', 'schedules'));
    }

    /**
     * Get real-time telemetry data for AJAX polling (JSON format).
     */
    public function apiLogs()
    {
        $latestAc1 = AcLog::where('active_ac', 'like', 'AC_1%')->latest('recorded_at')->first();
        $latestAc2 = AcLog::where('active_ac', 'like', 'AC_2%')->latest('recorded_at')->first();

        $currentAc1 = $latestAc1 ? (float) $latestAc1->current_ampere : 0.0000;
        $currentAc2 = $latestAc2 ? (float) $latestAc2->current_ampere : 0.0000;
        $totalCurrent = round($currentAc1 + $currentAc2, 4);
        $estimatedWatt = round($totalCurrent * 220);

        // Check if ESP32 device is actively sending telemetry (within last 45 seconds)
        $latestAny = AcLog::latest('recorded_at')->first();
        $isDeviceOnline = false;
        if ($latestAny && $latestAny->recorded_at) {
            $secondsAgo = Carbon::parse($latestAny->recorded_at)->diffInSeconds(now());
            $isDeviceOnline = $secondsAgo <= 45;
        }

        // Take the latest 15 distinct chronological timestamps for Chart.js
        $logs = AcLog::latest('recorded_at')->take(30)->get()->reverse()->values();

        // Build distinct time series for AC1 and AC2
        $chartLabels = [];
        $ac1Points = [];
        $ac2Points = [];

        foreach ($logs as $log) {
            $timeLabel = Carbon::parse($log->recorded_at)->format('H:i:s');
            if (!in_array($timeLabel, $chartLabels)) {
                $chartLabels[] = $timeLabel;
            }
        }
        // Keep up to 12 labels
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

        // Package message payload matching ESP32 ArduinoJson format
        $payload = json_encode([
            'relay' => $relay,
            'command' => $command
        ]);

        // Record the updated state immediately into the database
        AcLog::create([
            'device_id' => 'ESP32_PINDAD_ROOM_1',
            'active_ac' => "AC_{$relay}_{$command}",
            'current_ampere' => ($command === 'ON' ? 2.1500 : 0.0000),
            'recorded_at' => Carbon::now(),
        ]);

        // Publish to EMQX broker topic
        $success = false;
        try {
            $success = $this->mqttService->publish('pindad/ac/schedule', $payload, 1);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning("MQTT Publish non-fatal error: " . $e->getMessage());
        }

        return response()->json([
            'status' => 'success', 
            'mqtt_sent' => $success,
            'message' => "Status AC {$relay} berhasil diubah menjadi {$command}." . ($success ? " (Perintah MQTT Terkirim)" : "")
        ]);
    }

    /**
     * Save a new schedule.
     */
    public function storeSchedule(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:100',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        Schedule::create([
            'label' => $request->input('label'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'is_active' => true
        ]);

        return redirect()->back()->with('success', 'Jadwal baru berhasil disimpan!');
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
     * Export telemetry logs to CSV format.
     */
    public function exportCsv(Request $request)
    {
        $unit = $request->query('unit', 'all'); // 'all', 'ac1', 'ac2'
        
        $query = AcLog::latest('recorded_at');
        if ($unit === 'ac1') {
            $query->where('active_ac', 'like', 'AC_1%');
        } elseif ($unit === 'ac2') {
            $query->where('active_ac', 'like', 'AC_2%');
        }
        
        $logs = $query->take(500)->get();
        
        $fileName = 'PINDAD_AC_TELEMETRY_LOG_' . strtoupper($unit) . '_' . date('Ymd_His') . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        
        $columns = ['ID Log', 'Nama Perangkat', 'Status Telemetri', 'Arus Listrik (Ampere)', 'Estimasi Beban (Watt)', 'Waktu Pencatatan'];
        
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
