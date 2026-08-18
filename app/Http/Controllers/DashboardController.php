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

        // Get all schedules
        $schedules = Schedule::orderBy('start_time')->get();

        return view('dashboard', compact('latestAc1', 'latestAc2', 'schedules'));
    }

    /**
     * Get the latest logs for AJAX polling (JSON format).
     */
    public function apiLogs()
    {
        $latestAc1 = AcLog::where('active_ac', 'like', 'AC_1%')->latest('recorded_at')->first();
        $latestAc2 = AcLog::where('active_ac', 'like', 'AC_2%')->latest('recorded_at')->first();

        // Take the latest 20 logs for Chart.js and reverse to keep chronological order
        $logs = AcLog::latest('recorded_at')->take(20)->get()->reverse()->values();

        return response()->json([
            'latest_ac1' => $latestAc1,
            'latest_ac2' => $latestAc2,
            'chart_logs' => $logs
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

        $relay = $request->input('relay');
        $command = $request->input('command');

        // Package message payload
        $payload = json_encode([
            'relay' => $relay,
            'command' => $command
        ]);

        // Publish to EMQX broker topic
        $success = $this->mqttService->publish('pindad/ac/schedule', $payload, 1);

        if ($success) {
            return response()->json(['status' => 'success', 'message' => "Command sent: Relay {$relay} -> {$command}"]);
        }

        return response()->json(['status' => 'error', 'message' => 'Failed to send command via MQTT'], 500);
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

        return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan!');
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
}
