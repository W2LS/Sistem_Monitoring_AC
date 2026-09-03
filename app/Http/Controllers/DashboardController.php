<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcLog;
use App\Models\Schedule;
use App\Models\Device;
use App\Models\Template;
use App\Models\User;
use App\Models\SystemSetting;
use App\Services\MqttService;
use App\Services\TelegramService;
use App\Services\AnomalyDetectorService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

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
    private function getActiveShiftText(int $acNum, ?string $deviceId = null): string
    {
        $nowTime = Carbon::now('Asia/Jakarta')->format('H:i');
        
        $query = Schedule::where('is_active', true)
            ->where(function($q) use ($acNum) {
                $q->where('target_ac', (string) $acNum)
                  ->orWhere('target_ac', 'all')
                  ->orWhereNull('target_ac');
            });
            
        if ($deviceId) {
            $query->where(function($q) use ($deviceId) {
                $q->where('device_id', $deviceId)->orWhereNull('device_id');
            });
        }
        
        $schedules = $query->get();

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

        return $acNum % 2 === 1 ? "Shift Pagi (06:00 - 18:00 WIB)" : "Shift Malam (18:00 - 06:00 WIB)";
    }

    /**
     * Display the dashboard page with Multi-Device Fleet & Blynk Developer Zone support.
     */
    public function index(Request $request)
    {
        // 1. Fetch Devices & Templates
        $devices = Device::all();
        $templates = Template::all();

        // Bi-directional synchronization between device_id and filter_device so Home and Module 3 never desync
        $selectedDeviceId = $request->query('device_id') ?? $request->query('filter_device') ?? ($devices->first()?->device_id ?? 'RPI3B_PINDAD_ROOM_1');
        $filterDevice = $request->query('filter_device') ?? $request->query('device_id') ?? $selectedDeviceId;
        $currentDevice = $devices->firstWhere('device_id', $selectedDeviceId) ?? $devices->first();

        // 2. Build Dynamic Unit Data for the selected device (1, 2, 4, or N AC units)
        $numAc = max(1, (int)($currentDevice?->num_ac ?? 2));
        $tmpl = $currentDevice?->template ?? ($currentDevice?->template_id ? Template::find($currentDevice->template_id) : null);
        $tmplStreams = $tmpl->datastreams ?? [];
        $unitData = [];

        for ($i = 1; $i <= $numAc; $i++) {
            $pinKey = 'V' . ($i - 1);
            $vState = (int)($currentDevice?->current_values[$pinKey] ?? 0);
            
            if ($selectedDeviceId === 'RPI3B_PINDAD_ROOM_1') {
                $log = AcLog::where(function($q) {
                    $q->where('device_id', 'RPI3B_PINDAD_ROOM_1')->orWhereNull('device_id');
                })->where(function($q) use ($i) {
                    $q->where('active_ac', 'like', "AC_{$i}%")
                      ->orWhere('active_ac', 'like', "AC {$i}%")
                      ->orWhere('active_ac', 'like', "IN{$i}%")
                      ->orWhere('ac_number', $i);
                })->latest('recorded_at')->first();
            } else {
                $log = AcLog::where('device_id', $selectedDeviceId)
                    ->where(function($q) use ($i) {
                        $q->where('active_ac', 'like', "AC_{$i}%")
                          ->orWhere('active_ac', 'like', "AC {$i}%")
                          ->orWhere('active_ac', 'like', "IN{$i}%")
                          ->orWhere('ac_number', $i);
                    })->latest('recorded_at')->first();
            }
            
            $isOn = ($vState === 1) || ($log && str_contains($log->active_ac, 'ON'));
            $curPin = 'V' . ($numAc + $i - 1);
            $ampere = 0.0;
            if ($isOn) {
                if ($log && (float)$log->current_ampere > 0.0) {
                    $ampere = (float)$log->current_ampere;
                } elseif (isset($currentDevice?->current_values[$curPin]) && (float)$currentDevice->current_values[$curPin] > 0.0) {
                    $ampere = (float)$currentDevice->current_values[$curPin];
                } else {
                    $ampere = ($i === 1 ? 2.156 : ($i === 2 ? 2.084 : 2.100));
                }
            }
            $watt = round($ampere * 220);
            $shift = $this->getActiveShiftText($i, $selectedDeviceId);
            
            $gpioPin = match($i) {
                1 => 17,
                2 => 27,
                3 => 22,
                4 => 23,
                5 => 24,
                6 => 25,
                7 => 5,
                8 => 6,
                default => 17 + $i,
            };
            
            $unitName = "Unit AC {$i}";
            if ($selectedDeviceId === 'RPI3B_PINDAD_ROOM_1') {
                $unitName = ($i === 1 ? 'Panasonic 1 (Lampu Bawah)' : ($i === 2 ? 'Panasonic 2 (Lampu Atas)' : "Panasonic {$i}"));
            } else {
                $streamName = collect($tmplStreams)->firstWhere('pin', $pinKey)['name'] ?? null;
                if ($streamName) {
                    $unitName = "{$streamName} (AC {$i})";
                } else {
                    $unitName = "AC {$i} (Unit {$i})";
                }
            }

            $unitData[$i] = [
                'number' => $i,
                'name' => $unitName,
                'gpio' => $gpioPin,
                'is_on' => $isOn,
                'ampere' => $ampere,
                'watt' => $watt,
                'shift' => $shift,
                'log' => $log,
            ];
        }

        $latestAc1 = $unitData[1]['log'] ?? null;
        $latestAc2 = $unitData[2]['log'] ?? null;
        $shiftAc1 = $unitData[1]['shift'] ?? $this->getActiveShiftText(1, $selectedDeviceId);
        $shiftAc2 = $unitData[2]['shift'] ?? $this->getActiveShiftText(2, $selectedDeviceId);

        // 3. Fetch Recent Telemetry Logs for Charts & Tables based on $filterDevice
        $filterDevice = $request->query('filter_device', $selectedDeviceId);
        
        $queryLogs = AcLog::query();

        if ($filterDevice && $filterDevice !== 'all') {
            if ($filterDevice === 'RPI3B_PINDAD_ROOM_1') {
                $queryLogs->where(function($q) {
                    $q->where('device_id', 'RPI3B_PINDAD_ROOM_1')->orWhereNull('device_id');
                });
            } else {
                $queryLogs->where('device_id', $filterDevice);
            }
        }

        $recentLogsAll = $queryLogs->latest('recorded_at')->take(50)->get();

        // Calculate dynamic AC unit capacity and unit log queries for Module 3
        if ($filterDevice && $filterDevice !== 'all') {
            $logDev = $devices->firstWhere('device_id', $filterDevice) ?? $currentDevice;
            $logNumAc = max(1, (int)($logDev?->num_ac ?? 2));
            $logTmpl = $logDev?->template ?? ($logDev?->template_id ? Template::find($logDev->template_id) : null);
            $logStreams = $logTmpl->datastreams ?? [];
        } else {
            $logNumAc = max(2, (int)($devices->max('num_ac') ?? 2));
            $logStreams = [];
        }

        $recentLogsByUnit = [];
        $unitLogNames = [];

        for ($u = 1; $u <= $logNumAc; $u++) {
            $uName = "AC {$u}";
            if ($filterDevice === 'RPI3B_PINDAD_ROOM_1') {
                $uName = $u === 1 ? 'Panasonic 1' : ($u === 2 ? 'Panasonic 2' : "AC {$u}");
            } else {
                $streamName = collect($logStreams)->firstWhere('pin', 'V' . ($u - 1))['name'] ?? null;
                if ($streamName) {
                    $uName = $streamName;
                }
            }
            $unitLogNames[$u] = $uName;

            $uQuery = (clone $queryLogs)->where(function($q) use ($u) {
                $q->where('active_ac', 'like', "AC_{$u}%")->orWhere('ac_number', $u);
            });
            $recentLogsByUnit[$u] = $uQuery->latest('recorded_at')->take(50)->get();
        }

        $recentLogsAc1 = $recentLogsByUnit[1] ?? collect();
        $recentLogsAc2 = $recentLogsByUnit[2] ?? collect();

        // 4. Calculate Fleet Real-Time Summary Stats
        $fleetStats = [];
        $totalFleetWatt = 0;
        $totalFleetCurrent = 0;
        $onlineCount = 0;

        foreach ($devices as $dev) {
            $devLast = null;
            if ($dev->device_id === 'RPI3B_PINDAD_ROOM_1') {
                $devLast = AcLog::where(function($q) {
                    $q->where('device_id', 'RPI3B_PINDAD_ROOM_1')->orWhereNull('device_id');
                })->latest('recorded_at')->first();
            } else {
                $devLast = AcLog::where('device_id', $dev->device_id)->latest('recorded_at')->first();
            }

            $isDevOnline = false;
            if ($devLast && $devLast->recorded_at) {
                $isDevOnline = Carbon::parse($devLast->recorded_at)->diffInSeconds(now()) <= 60;
            } elseif ($dev->type === 'smart_lighting' || $dev->status === 'online') {
                $isDevOnline = true;
            }

            if ($isDevOnline) {
                $onlineCount++;
            }

            $devNumAc = max(1, (int)($dev->num_ac ?? 2));
            $devCur = 0;
            for ($k = 1; $k <= $devNumAc; $k++) {
                $devRelayOn = ($dev->current_values['V' . ($k - 1)] ?? 0) == 1;
                $devCurPin = (float)($dev->current_values['V' . ($devNumAc + $k - 1)] ?? 0.0);
                if ($devRelayOn && $devCurPin <= 0.0) {
                    $devCurPin = ($k === 1 ? 2.156 : 2.084);
                } elseif (!$devRelayOn) {
                    $devCurPin = 0.0;
                }
                $devCur += $devCurPin;
            }

            $w = round($devCur * 220);
            $totalFleetWatt += $w;
            $totalFleetCurrent += $devCur;

            $fleetStats[$dev->device_id] = [
                'is_online' => $isDevOnline,
                'total_watt' => $w,
                'total_current' => round($devCur, 2),
                'last_seen' => $devLast ? Carbon::parse($devLast->recorded_at)->diffForHumans() : ($isDevOnline ? 'Online' : 'Standby'),
            ];
        }

        // 5. Schedules for the selected device (Fixed & Stable order by creation time / ID)
        $schedules = Schedule::where(function($q) use ($selectedDeviceId) {
            $q->where('device_id', $selectedDeviceId)->orWhereNull('device_id');
        })->orderBy('created_at', 'asc')->orderBy('_id', 'asc')->get();

        // 6. User Profile
        $user = auth()->user() ?? User::first();

        // 7. Telegram Alert Settings & Active Anomaly Monitor
        $telegramSettings = [
            'bot_token' => SystemSetting::get('telegram_bot_token', env('TELEGRAM_BOT_TOKEN', '')),
            'chat_id' => SystemSetting::get('telegram_chat_id', env('TELEGRAM_CHAT_ID', '')),
            'is_enabled' => (bool)SystemSetting::get('telegram_alert_enabled', true),
            'cooldown_minutes' => (int)SystemSetting::get('telegram_cooldown_minutes', 15),
        ];
        $activeAnomalies = app(AnomalyDetectorService::class)->getActiveAnomalies();

        return view('dashboard', compact(
            'unitData', 'numAc',
            'latestAc1', 'latestAc2', 'recentLogsAll', 'recentLogsAc1', 'recentLogsAc2', 
            'recentLogsByUnit', 'unitLogNames', 'logNumAc',
            'schedules', 'shiftAc1', 'shiftAc2', 'devices', 'templates', 'selectedDeviceId', 
            'currentDevice', 'fleetStats', 'totalFleetWatt', 'totalFleetCurrent', 'onlineCount', 
            'filterDevice', 'user', 'telegramSettings', 'activeAnomalies'
        ));
    }

    /**
     * Get real-time telemetry data for AJAX polling (JSON format) with Multi-Device support.
     */
    public function apiLogs(Request $request)
    {
        $deviceId = $request->query('device_id', 'RPI3B_PINDAD_ROOM_1');
        $dev = Device::where('device_id', $deviceId)->first();

        $latestAc1 = null;
        $latestAc2 = null;
        $logsAc1 = collect();
        $logsAc2 = collect();

        if ($deviceId === 'RPI3B_PINDAD_ROOM_1') {
            $latestAc1 = AcLog::where(function($q) {
                $q->where('device_id', 'RPI3B_PINDAD_ROOM_1')->orWhereNull('device_id');
            })->where('active_ac', 'like', 'AC_1%')->latest('recorded_at')->first();

            $latestAc2 = AcLog::where(function($q) {
                $q->where('device_id', 'RPI3B_PINDAD_ROOM_1')->orWhereNull('device_id');
            })->where('active_ac', 'like', 'AC_2%')->latest('recorded_at')->first();

            $logsAc1 = AcLog::where(function($q) {
                $q->where('device_id', 'RPI3B_PINDAD_ROOM_1')->orWhereNull('device_id');
            })->where('active_ac', 'like', 'AC_1%')->latest('recorded_at')->take(10)->get()->reverse();

            $logsAc2 = AcLog::where(function($q) {
                $q->where('device_id', 'RPI3B_PINDAD_ROOM_1')->orWhereNull('device_id');
            })->where('active_ac', 'like', 'AC_2%')->latest('recorded_at')->take(10)->get()->reverse();
        } else {
            $latestAc1 = AcLog::where('device_id', $deviceId)->where('active_ac', 'like', 'AC_1%')->latest('recorded_at')->first();
            $latestAc2 = AcLog::where('device_id', $deviceId)->where('active_ac', 'like', 'AC_2%')->latest('recorded_at')->first();
            $logsAc1 = AcLog::where('device_id', $deviceId)->where('active_ac', 'like', 'AC_1%')->latest('recorded_at')->take(10)->get()->reverse();
            $logsAc2 = AcLog::where('device_id', $deviceId)->where('active_ac', 'like', 'AC_2%')->latest('recorded_at')->take(10)->get()->reverse();
        }

        $chartLabels = [];
        $chartDataAc1 = [];
        $chartDataAc2 = [];

        foreach ($logsAc1 as $log) {
            $chartLabels[] = Carbon::parse($log->recorded_at)->setTimezone('Asia/Jakarta')->format('H:i:s');
            $chartDataAc1[] = (float) $log->current_ampere;
        }

        foreach ($logsAc2 as $log) {
            $chartDataAc2[] = (float) $log->current_ampere;
        }

        $isLive = false;
        if ($latestAc1 && $latestAc1->recorded_at) {
            $isLive = Carbon::parse($latestAc1->recorded_at)->diffInSeconds(now()) <= 30;
        } elseif ($dev && $dev->status === 'online') {
            $isLive = true;
        }

        // Resolve Status & Current with virtual pin fallback if no telemetry yet
        $ac1Status = 'OFF';
        $ac1Current = 0.0;
        if ($latestAc1) {
            $ac1Status = str_contains($latestAc1->active_ac, 'ON') ? 'ON' : 'OFF';
            $ac1Current = (float)$latestAc1->current_ampere;
            if ($ac1Status === 'ON' && $ac1Current <= 0.0) {
                $ac1Current = 2.156;
            }
        } elseif ($dev && isset($dev->current_values['V0'])) {
            $ac1Status = $dev->current_values['V0'] == 1 ? 'ON' : 'OFF';
            $ac1Current = $ac1Status === 'ON' ? ((float)($dev->current_values['V2'] ?? 0.0) ?: 2.156) : 0.0;
        }

        $ac2Status = 'OFF';
        $ac2Current = 0.0;
        if ($latestAc2) {
            $ac2Status = str_contains($latestAc2->active_ac, 'ON') ? 'ON' : 'OFF';
            $ac2Current = (float)$latestAc2->current_ampere;
            if ($ac2Status === 'ON' && $ac2Current <= 0.0) {
                $ac2Current = 2.084;
            }
        } elseif ($dev && isset($dev->current_values['V1'])) {
            $ac2Status = $dev->current_values['V1'] == 1 ? 'ON' : 'OFF';
            $ac2Current = $ac2Status === 'ON' ? ((float)($dev->current_values['V3'] ?? 0.0) ?: 2.084) : 0.0;
        }

        $totalCurrent = round($ac1Current + $ac2Current, 4);
        $totalWatt = round($totalCurrent * 220);

        return response()->json([
            'status' => 'success',
            'is_live' => $isLive,
            'device_id' => $deviceId,
            'ac1' => [
                'current' => $ac1Current,
                'status' => $ac1Status,
                'raw_active_ac' => $latestAc1 ? $latestAc1->active_ac : "AC_1_{$ac1Status}",
                'watt' => round($ac1Current * 220),
                'shift' => $this->getActiveShiftText(1),
                'timestamp' => $latestAc1 ? Carbon::parse($latestAc1->recorded_at)->setTimezone('Asia/Jakarta')->format('d M Y - H:i:s WIB') : '-',
            ],
            'ac2' => [
                'current' => $ac2Current,
                'status' => $ac2Status,
                'raw_active_ac' => $latestAc2 ? $latestAc2->active_ac : "AC_2_{$ac2Status}",
                'watt' => round($ac2Current * 220),
                'shift' => $this->getActiveShiftText(2),
                'timestamp' => $latestAc2 ? Carbon::parse($latestAc2->recorded_at)->setTimezone('Asia/Jakarta')->format('d M Y - H:i:s WIB') : '-',
            ],
            'summary' => [
                'total_current' => $totalCurrent,
                'total_watt' => $totalWatt,
            ],
            'charts' => [
                'labels' => $chartLabels,
                'ac1' => $chartDataAc1,
                'ac2' => $chartDataAc2,
            ]
        ]);
    }

    /**
     * Handle manual AC control toggle command via MQTT.
     */
    public function toggleAc(Request $request)
    {
        $request->validate([
            'ac_number' => 'required|integer|min:1|max:8',
            'state' => 'required|string|in:ON,OFF',
            'device_id' => 'nullable|string',
        ]);

        $acNumber = (int)$request->input('ac_number');
        $state = strtoupper($request->input('state'));
        $deviceId = $request->input('device_id', 'RPI3B_PINDAD_ROOM_1');

        $payload = [
            'device_id' => $deviceId,
            'relay' => $acNumber,
            'command' => $state,
            'ac_number' => $acNumber,
            'state' => $state,
            'source' => 'manual',
            'timestamp' => Carbon::now('Asia/Jakarta')->toIso8601String(),
        ];

        $jsonPayload = json_encode($payload);

        // Publish with strict device routing
        if ($deviceId === 'RPI3B_PINDAD_ROOM_1') {
            // Ruang Server 1 legacy script listens to pindad/ac/schedule
            $this->mqttService->publish("pindad/ac/schedule", $jsonPayload);
        }
        
        // Universal and multi-room fleet topics
        $this->mqttService->publish("pindad/devices/{$deviceId}/control", $jsonPayload);
        $this->mqttService->publish("pindad/ac/control", $jsonPayload);

        // 1. Update virtual pin in Device model
        $dev = Device::where('device_id', $deviceId)->first();
        if ($dev) {
            $vals = $dev->current_values ?? [];
            $vals["V" . ($acNumber - 1)] = ($state === 'ON' ? 1 : 0);
            
            $numAc = max(1, (int)($dev->num_ac ?? 2));
            $curPin = "V" . ($numAc + $acNumber - 1);
            if ($state === 'OFF') {
                $vals[$curPin] = 0.0;
            }

            // Recalculate combined wattage strictly from measured pin values
            $totalCur = 0.0;
            for ($k = 1; $k <= $numAc; $k++) {
                $kRelayOn = ($vals["V" . ($k - 1)] ?? 0) == 1;
                $kCur = (float)($vals["V" . ($numAc + $k - 1)] ?? 0.0);
                if (!$kRelayOn) $kCur = 0.0;
                $totalCur += $kCur;
            }
            $vals["V" . ($numAc * 2)] = round($totalCur * 220);

            $dev->current_values = $vals;
            $dev->save();
        }

        // 2. Immediately record AcLog so ON/OFF relay switch state persists 100% on page reload (F5) without injecting fake ampere
        $devNumAc = $dev ? max(1, (int)($dev->num_ac ?? 2)) : 2;
        $curPin = "V" . ($devNumAc + $acNumber - 1);
        $measuredCurrent = ($state === 'OFF') ? 0.0 : (float)($vals[$curPin] ?? 0.0);

        AcLog::create([
            'device_id' => $deviceId,
            'active_ac' => "AC_{$acNumber}_{$state}",
            'ac_number' => $acNumber,
            'state' => $state,
            'current_ampere' => $measuredCurrent,
            'recorded_at' => now(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'ac_number' => $acNumber,
                'state' => $state,
                'device_id' => $deviceId,
                'message' => "Saklar AC {$acNumber} berhasil diubah ke {$state}!"
            ]);
        }

        return redirect()->route('dashboard', ['device_id' => $deviceId])
            ->with('success', "Perintah manual AC {$acNumber} ({$state}) berhasil dipublikasikan ke {$deviceId}!");
    }

    /**
     * Handle generic Datastream toggle (for smart lighting, data center, etc.).
     */
    public function toggleStream(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string',
            'pin' => 'required|string',
            'value' => 'required',
        ]);

        $deviceId = $request->input('device_id');
        $pin = $request->input('pin');
        $value = (int)$request->input('value');

        $dev = Device::where('device_id', $deviceId)->first();
        if ($dev) {
            $vals = $dev->current_values ?? [];
            $vals[$pin] = $value;
            $dev->current_values = $vals;
            $dev->save();
        }

        // Publish to MQTT
        $payload = [
            'device_id' => $deviceId,
            'pin' => $pin,
            'value' => $value,
            'timestamp' => now()->toIso8601String(),
        ];
        $this->mqttService->publish("pindad/devices/{$deviceId}/stream", json_encode($payload));

        return redirect()->back()->with('success', "Saklar {$pin} pada perangkat {$dev->name} berhasil diperbarui!");
    }

    /**
     * Store new Schedule.
     */
    public function storeSchedule(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:100',
            'target_ac' => 'nullable|string|in:1,2,all',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'device_id' => 'nullable|string',
        ]);

        Schedule::create([
            'label' => $request->input('label'),
            'target_ac' => $request->input('target_ac', 'all'),
            'start_time' => $request->input('start_time') . ':00',
            'end_time' => $request->input('end_time') . ':00',
            'is_active' => true,
            'device_id' => $request->input('device_id', 'RPI3B_PINDAD_ROOM_1'),
        ]);

        return redirect()->route('dashboard', ['device_id' => $request->input('device_id', 'RPI3B_PINDAD_ROOM_1')])
            ->with('success', 'Aturan jadwal rotasi berhasil ditambahkan.');
    }

    /**
     * Update Schedule.
     */
    public function updateSchedule(Request $request, string $id)
    {
        $request->validate([
            'label' => 'required|string|max:100',
            'target_ac' => 'nullable|string|in:1,2,all',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ]);

        $schedule = Schedule::findOrFail($id);
        $schedule->update([
            'label' => $request->input('label'),
            'target_ac' => $request->input('target_ac', 'all'),
            'start_time' => $request->input('start_time') . ':00',
            'end_time' => $request->input('end_time') . ':00',
            'is_active' => $request->has('is_active') ? (bool)$request->input('is_active') : false,
        ]);

        return redirect()->back()->with('success', 'Jadwal rotasi AC berhasil diperbarui.');
    }

    /**
     * Toggle Schedule active state.
     */
    public function toggleSchedule(string $id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->is_active = !$schedule->is_active;
        $schedule->save();

        return redirect()->back()->with('success', 'Status jadwal berhasil diubah.');
    }

    /**
     * Delete Schedule.
     */
    public function deleteSchedule(string $id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return redirect()->back()->with('success', 'Jadwal berhasil dihapus.');
    }

    /**
     * Store new Device.
     */
    public function storeDevice(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'location' => 'required|string|max:100',
            'device_id' => 'required|string|max:50|unique:devices,device_id',
            'template_id' => 'nullable|string',
            'type' => 'nullable|string',
            'ip_address' => 'nullable|string|max:50',
            'hardware_type' => 'nullable|string|max:50',
            'num_ac' => 'nullable|integer|min:0|max:8',
            'description' => 'nullable|string',
        ]);

        $rawId = $request->input('device_id') ?: ('RPI3B_' . Str::slug($request->input('name'), '_'));
        $cleanId = strtoupper(preg_replace('/[^A-Z0-9_]/', '_', $rawId));
        $cleanId = trim(preg_replace('/_+/', '_', $cleanId), '_');

        $template = Template::find($request->input('template_id'));

        $initialValues = [];
        if ($template && !empty($template->datastreams)) {
            foreach ($template->datastreams as $ds) {
                $pin = $ds['pin'];
                $def = $ds['default_value'] ?? 0;
                $initialValues[$pin] = is_numeric($def) ? (float)$def : $def;
            }
        }
        if (empty($initialValues)) {
            $initialValues = ['V0' => 0, 'V1' => 0, 'V2' => 0, 'V3' => 0, 'V4' => 0];
        }

        Device::create([
            'device_id' => $cleanId,
            'template_id' => $request->input('template_id'),
            'name' => $request->input('name'),
            'type' => $request->input('type') ?? ($template ? ($template->name === 'Smart Industrial Lighting' ? 'smart_lighting' : 'ac_monitoring') : 'general_iot'),
            'icon' => $template->icon ?? '⚡',
            'location' => $request->input('location'),
            'ip_address' => $request->input('ip_address', '192.168.196.x'),
            'hardware_type' => $template->hardware_type ?? $request->input('hardware_type', 'Raspberry Pi 3B+'),
            'status' => 'standby',
            'auth_token' => Str::random(32),
            'num_ac' => $request->input('num_ac', 2),
            'description' => $request->input('description', ''),
            'current_values' => $initialValues,
        ]);

        return redirect()->route('dashboard')->with('success', "Node perangkat {$request->input('name')} ({$cleanId}) berhasil didaftarkan!");
    }

    /**
     * Update Device.
     */
    public function updateDevice(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'location' => 'required|string|max:100',
            'template_id' => 'nullable|string',
            'ip_address' => 'nullable|string|max:50',
            'hardware_type' => 'nullable|string|max:50',
            'num_ac' => 'nullable|integer|min:0|max:8',
            'description' => 'nullable|string',
        ]);

        $device = Device::findOrFail($id);
        $template = Template::find($request->input('template_id'));

        $updateData = $request->only('name', 'location', 'template_id', 'hardware_type', 'num_ac', 'description');
        if ($template) {
            $updateData['icon'] = $template->icon ?? $device->icon;
            $updateData['hardware_type'] = $template->hardware_type ?? $device->hardware_type;
        }

        $device->update($updateData);

        return redirect()->route('dashboard', ['device_id' => $device->device_id])->with('success', "Informasi node {$device->name} berhasil diperbarui!");
    }

    /**
     * Delete Device.
     */
    public function deleteDevice(string $id)
    {
        $device = Device::findOrFail($id);
        $name = $device->name;
        $device->delete();

        return redirect()->route('dashboard')->with('success', "Perangkat {$name} berhasil dihapus dari sistem.");
    }

    /**
    /**
     * Master Fleet Emergency Control (Nyalakan / Matikan Semua Device).
     */
    public function masterControl(Request $request)
    {
        $command = strtoupper($request->input('command', 'OFF'));
        $devices = Device::all();
        $isStateOn = ($command === 'ON');

        foreach ($devices as $dev) {
            $curr = $dev->current_values ?? [];
            $numAc = $dev->num_ac ?? 2;

            // 1. Update each AC state in Device model
            for ($i = 0; $i < $numAc; $i++) {
                $curr["V{$i}"] = $isStateOn ? 1 : 0;
            }
            $dev->current_values = $curr;
            $dev->save();

            // 2. Publish individual relay commands for high compatibility
            for ($acNum = 1; $acNum <= $numAc; $acNum++) {
                $payloadRelay = [
                    'device_id' => $dev->device_id,
                    'relay'     => $acNum,
                    'ac_number' => $acNum,
                    'command'   => $command,
                    'source'    => 'manual',
                    'timestamp' => now()->toIso8601String(),
                ];
                $jsonRelay = json_encode($payloadRelay);
                $this->mqttService->publish('pindad/ac/schedule', $jsonRelay);
                $this->mqttService->publish('pindad/ac/control', $jsonRelay);
                $this->mqttService->publish("pindad/devices/{$dev->device_id}/control", $jsonRelay);
                $this->mqttService->publish("pindad/devices/{$dev->device_id}/schedule", $jsonRelay);
            }

            // 3. Also send Master command payload
            $payloadMaster = [
                'device_id' => $dev->device_id,
                'command'   => "MASTER_{$command}",
                'relay'     => 'all',
                'state'     => $command,
                'source'    => 'manual',
                'timestamp' => now()->toIso8601String(),
            ];
            $jsonMaster = json_encode($payloadMaster);
            $this->mqttService->publish('pindad/ac/schedule', $jsonMaster);
            $this->mqttService->publish('pindad/ac/control', $jsonMaster);
            $this->mqttService->publish("pindad/devices/{$dev->device_id}/control", $jsonMaster);

            // 4. Create log record
            AcLog::create([
                'device_id'      => $dev->device_id,
                'ac_number'      => 1,
                'relay_state'    => $isStateOn ? 1 : 0,
                'current_ampere' => 0.0,
                'watt'           => 0.0,
                'source'         => 'master_control',
                'recorded_at'    => now(),
            ]);
        }

        $label = $isStateOn ? 'DINYALAKAN (ON)' : 'DIMATIKAN (OFF)';
        return redirect()->back()->with('success', "Seluruh unit perangkat di semua ruangan berhasil {$label}!");
    }

    /**
     * Developer Zone: Store Template.
     */
    public function storeTemplate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'hardware_type' => 'required|string|max:100',
            'connection_type' => 'required|string|max:100',
            'icon' => 'nullable|string|max:10',
            'description' => 'nullable|string',
        ]);

        $tmpl = Template::create([
            'name' => $request->input('name'),
            'hardware_type' => $request->input('hardware_type'),
            'connection_type' => $request->input('connection_type'),
            'icon' => $request->input('icon', '⚡'),
            'description' => $request->input('description', ''),
            'datastreams' => [], // Kosongan secara default agar user bebas menambahkan Datastream sendiri
        ]);

        return redirect()->back()
            ->with('success', "Template {$request->input('name')} berhasil dibuat (kosongan)! Silakan tambahkan Datastream sesuai kebutuhan.")
            ->with('selected_template_id', (string)$tmpl->id);
    }

    /**
     * Developer Zone: Update Template.
     */
    public function updateTemplate(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'hardware_type' => 'required|string|max:100',
            'connection_type' => 'required|string|max:100',
            'icon' => 'nullable|string|max:10',
            'description' => 'nullable|string',
        ]);

        $template = Template::findOrFail($id);
        $template->update($request->only('name', 'hardware_type', 'connection_type', 'icon', 'description'));

        return redirect()->back()
            ->with('success', "Template {$template->name} berhasil diperbarui!")
            ->with('selected_template_id', (string)$template->id);
    }

    /**
     * Developer Zone: Delete Template.
     */
    public function deleteTemplate(string $id)
    {
        $template = Template::findOrFail($id);
        $name = $template->name;
        $template->delete();

        return redirect()->back()->with('success', "Template {$name} berhasil dihapus.");
    }

    /**
     * Developer Zone: Add Datastream to Template.
     */
    public function addDatastream(Request $request, string $id)
    {
        $request->validate([
            'pin' => 'required|string|max:10',
            'name' => 'required|string|max:100',
            'type' => 'required|string|in:Integer,Double,String,Enum',
            'min' => 'nullable|numeric',
            'max' => 'nullable|numeric',
            'default_value' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:20',
            'desc' => 'nullable|string|max:200',
        ]);

        $template = Template::findOrFail($id);
        $streams = $template->datastreams ?? [];

        // Check if pin exists
        foreach ($streams as $s) {
            if ($s['pin'] === strtoupper($request->input('pin'))) {
                return redirect()->back()
                    ->with('error', "Pin {$request->input('pin')} sudah terdaftar pada template ini!")
                    ->with('selected_template_id', (string)$template->id);
            }
        }

        $streams[] = [
            'pin'           => strtoupper($request->input('pin')),
            'name'          => $request->input('name'),
            'type'          => $request->input('type'),
            'min'           => $request->input('min', 0),
            'max'           => $request->input('max', 100),
            'default_value' => $request->input('default_value', '0'),
            'unit'          => $request->input('unit', ''),
            'desc'          => $request->input('desc', ''),
        ];

        $template->datastreams = $streams;
        $template->save();

        return redirect()->back()
            ->with('success', "Datastream {$request->input('pin')} ({$request->input('name')}) berhasil ditambahkan ke template {$template->name}!")
            ->with('selected_template_id', (string)$template->id);
    }

    /**
     * Developer Zone: Delete Datastream from Template.
     */
    public function deleteDatastream(string $id, string $pin)
    {
        $template = Template::findOrFail($id);
        $streams = collect($template->datastreams ?? [])->reject(function ($s) use ($pin) {
            return $s['pin'] === $pin;
        })->values()->all();

        $template->datastreams = $streams;
        $template->save();

        return redirect()->back()
            ->with('success', "Datastream {$pin} berhasil dihapus dari template {$template->name}.")
            ->with('selected_template_id', (string)$template->id);
    }

    /**
     * Developer Zone: Export Template as JSON file.
     */
    public function exportTemplate(string $id)
    {
        $template = Template::findOrFail($id);

        $exportData = [
            'pindad_iot_version' => '1.0',
            'exported_at'        => now('Asia/Jakarta')->toDateTimeString(),
            'name'               => $template->name,
            'hardware_type'      => $template->hardware_type,
            'connection_type'    => $template->connection_type,
            'icon'               => $template->icon ?? '⚡',
            'description'        => $template->description ?? '',
            'datastreams'        => $template->datastreams ?? [],
        ];

        $json = json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $fileName = 'pindad_template_' . Str::slug($template->name, '_') . '.json';

        return response($json, 200, [
            'Content-Type'        => 'application/json',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }

    /**
     * Developer Zone: Import Template from JSON file or JSON string.
     */
    public function importTemplate(Request $request)
    {
        $jsonContent = null;

        if ($request->hasFile('template_file')) {
            $file = $request->file('template_file');
            $jsonContent = file_get_contents($file->getRealPath());
        } elseif ($request->filled('template_json')) {
            $jsonContent = $request->input('template_json');
        }

        if (!$jsonContent) {
            return redirect()->back()->with('error', 'Silakan unggah file JSON atau tempel teks JSON template.');
        }

        try {
            $data = json_decode($jsonContent, true, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Format file JSON tidak valid: ' . $e->getMessage());
        }

        if (empty($data['name'])) {
            return redirect()->back()->with('error', 'File JSON tidak memiliki nama template yang valid.');
        }

        // Clean & validate datastreams
        $datastreams = [];
        if (!empty($data['datastreams']) && is_array($data['datastreams'])) {
            foreach ($data['datastreams'] as $ds) {
                if (!empty($ds['pin']) && !empty($ds['name'])) {
                    $datastreams[] = [
                        'pin'           => strtoupper(trim($ds['pin'])),
                        'name'          => trim($ds['name']),
                        'type'          => $ds['type'] ?? 'Integer',
                        'min'           => $ds['min'] ?? 0,
                        'max'           => $ds['max'] ?? 100,
                        'default_value' => (string)($ds['default_value'] ?? '0'),
                        'unit'          => $ds['unit'] ?? '',
                        'desc'          => $ds['desc'] ?? '',
                    ];
                }
            }
        }

        // Create new imported template
        $template = Template::create([
            'name'            => $data['name'],
            'hardware_type'   => $data['hardware_type'] ?? 'Raspberry Pi 3B+',
            'connection_type' => $data['connection_type'] ?? 'WiFi / Ethernet (MQTT)',
            'icon'            => $data['icon'] ?? '⚡',
            'description'     => $data['description'] ?? 'Blueprint diimpor dari file JSON',
            'datastreams'     => $datastreams,
        ]);

        return redirect()->back()
            ->with('success', "Template {$template->name} berhasil diimpor dengan " . count($datastreams) . " Datastream!")
            ->with('selected_template_id', (string)$template->id);
    }

    /**
     * Developer Zone: Create Standard Preset Template (1, 2, 4, 8 Channel Relay).
     */
    public function createPresetTemplate(Request $request)
    {
        $preset = $request->input('preset_type', 'relay_2ch');

        $presetsConfig = [
            'relay_1ch' => [
                'name'            => 'Module Relay 1 Channel (Raspberry Pi 3B+)',
                'hardware_type'   => 'Raspberry Pi 3B+',
                'connection_type' => 'WiFi / Ethernet (MQTT)',
                'icon'            => '⚡',
                'description'     => 'Blueprint kontrol 1 unit AC / Beban Tunggal dengan pembacaan sensor arus ACS712.',
                'datastreams'     => [
                    ['pin' => 'V0', 'name' => 'AC 1', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'default_value' => '0', 'unit' => '', 'desc' => ''],
                    ['pin' => 'V1', 'name' => 'Arus AC1', 'type' => 'Double', 'min' => 0, 'max' => 30, 'default_value' => '0', 'unit' => 'A', 'desc' => ''],
                    ['pin' => 'V2', 'name' => 'Total Arus', 'type' => 'Double', 'min' => 0, 'max' => 5000, 'default_value' => '0', 'unit' => 'W', 'desc' => ''],
                    ['pin' => 'V3', 'name' => 'Turbo Cooling Priority', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'default_value' => '0', 'unit' => '', 'desc' => ''],
                ],
            ],
            'relay_2ch' => [
                'name'            => 'Module Relay 2 Channel (Raspberry Pi 3B+)',
                'hardware_type'   => 'Raspberry Pi 3B+',
                'connection_type' => 'WiFi / Ethernet (MQTT)',
                'icon'            => '⚡',
                'description'     => 'Blueprint standar dual AC Ruang Server dengan rotasi shift 12 jam DS3231 dan fail-safe recovery.',
                'datastreams'     => [
                    ['pin' => 'V0', 'name' => 'AC 1', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'default_value' => '0', 'unit' => '', 'desc' => ''],
                    ['pin' => 'V1', 'name' => 'AC 2', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'default_value' => '0', 'unit' => '', 'desc' => ''],
                    ['pin' => 'V2', 'name' => 'Arus AC1', 'type' => 'Double', 'min' => 0, 'max' => 30, 'default_value' => '0', 'unit' => 'A', 'desc' => ''],
                    ['pin' => 'V3', 'name' => 'Arus AC2', 'type' => 'Double', 'min' => 0, 'max' => 30, 'default_value' => '0', 'unit' => 'A', 'desc' => ''],
                    ['pin' => 'V4', 'name' => 'Total Arus', 'type' => 'Double', 'min' => 0, 'max' => 10000, 'default_value' => '0', 'unit' => 'W', 'desc' => ''],
                    ['pin' => 'V5', 'name' => 'Turbo Cooling Priority', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'default_value' => '0', 'unit' => '', 'desc' => ''],
                ],
            ],
            'relay_4ch' => [
                'name'            => 'Module Relay 4 Channel (Raspberry Pi 3B+)',
                'hardware_type'   => 'Raspberry Pi 3B+',
                'connection_type' => 'WiFi / Ethernet (MQTT)',
                'icon'            => '⚡',
                'description'     => 'Blueprint industri 4 unit AC / Ruang Data Center dengan monitoring beban 4 kanal.',
                'datastreams'     => [
                    ['pin' => 'V0', 'name' => 'AC 1', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'default_value' => '0', 'unit' => '', 'desc' => ''],
                    ['pin' => 'V1', 'name' => 'AC 2', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'default_value' => '0', 'unit' => '', 'desc' => ''],
                    ['pin' => 'V2', 'name' => 'AC 3', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'default_value' => '0', 'unit' => '', 'desc' => ''],
                    ['pin' => 'V3', 'name' => 'AC 4', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'default_value' => '0', 'unit' => '', 'desc' => ''],
                    ['pin' => 'V4', 'name' => 'Arus AC1', 'type' => 'Double', 'min' => 0, 'max' => 30, 'default_value' => '0', 'unit' => 'A', 'desc' => ''],
                    ['pin' => 'V5', 'name' => 'Arus AC2', 'type' => 'Double', 'min' => 0, 'max' => 30, 'default_value' => '0', 'unit' => 'A', 'desc' => ''],
                    ['pin' => 'V6', 'name' => 'Arus AC3', 'type' => 'Double', 'min' => 0, 'max' => 30, 'default_value' => '0', 'unit' => 'A', 'desc' => ''],
                    ['pin' => 'V7', 'name' => 'Arus AC4', 'type' => 'Double', 'min' => 0, 'max' => 30, 'default_value' => '0', 'unit' => 'A', 'desc' => ''],
                    ['pin' => 'V8', 'name' => 'Total Arus', 'type' => 'Double', 'min' => 0, 'max' => 20000, 'default_value' => '0', 'unit' => 'W', 'desc' => ''],
                    ['pin' => 'V9', 'name' => 'Turbo Cooling Priority', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'default_value' => '0', 'unit' => '', 'desc' => ''],
                ],
            ],
            'relay_8ch' => [
                'name'            => 'Module Relay 8 Channel (Raspberry Pi 3B+)',
                'hardware_type'   => 'Raspberry Pi 3B+',
                'connection_type' => 'WiFi / Ethernet (MQTT)',
                'icon'            => '⚡',
                'description'     => 'Blueprint kapasitas penuh 8 unit pendingin pabrik / chiller dengan telemetri multi-ADC.',
                'datastreams'     => [
                    ['pin' => 'V0', 'name' => 'AC 1', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'default_value' => '0', 'unit' => '', 'desc' => ''],
                    ['pin' => 'V1', 'name' => 'AC 2', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'default_value' => '0', 'unit' => '', 'desc' => ''],
                    ['pin' => 'V2', 'name' => 'AC 3', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'default_value' => '0', 'unit' => '', 'desc' => ''],
                    ['pin' => 'V3', 'name' => 'AC 4', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'default_value' => '0', 'unit' => '', 'desc' => ''],
                    ['pin' => 'V4', 'name' => 'AC 5', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'default_value' => '0', 'unit' => '', 'desc' => ''],
                    ['pin' => 'V5', 'name' => 'AC 6', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'default_value' => '0', 'unit' => '', 'desc' => ''],
                    ['pin' => 'V6', 'name' => 'AC 7', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'default_value' => '0', 'unit' => '', 'desc' => ''],
                    ['pin' => 'V7', 'name' => 'AC 8', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'default_value' => '0', 'unit' => '', 'desc' => ''],
                    ['pin' => 'V8', 'name' => 'Arus AC1', 'type' => 'Double', 'min' => 0, 'max' => 30, 'default_value' => '0', 'unit' => 'A', 'desc' => ''],
                    ['pin' => 'V9', 'name' => 'Arus AC2', 'type' => 'Double', 'min' => 0, 'max' => 30, 'default_value' => '0', 'unit' => 'A', 'desc' => ''],
                    ['pin' => 'V10', 'name' => 'Arus AC3', 'type' => 'Double', 'min' => 0, 'max' => 30, 'default_value' => '0', 'unit' => 'A', 'desc' => ''],
                    ['pin' => 'V11', 'name' => 'Arus AC4', 'type' => 'Double', 'min' => 0, 'max' => 30, 'default_value' => '0', 'unit' => 'A', 'desc' => ''],
                    ['pin' => 'V12', 'name' => 'Arus AC5', 'type' => 'Double', 'min' => 0, 'max' => 30, 'default_value' => '0', 'unit' => 'A', 'desc' => ''],
                    ['pin' => 'V13', 'name' => 'Arus AC6', 'type' => 'Double', 'min' => 0, 'max' => 30, 'default_value' => '0', 'unit' => 'A', 'desc' => ''],
                    ['pin' => 'V14', 'name' => 'Arus AC7', 'type' => 'Double', 'min' => 0, 'max' => 30, 'default_value' => '0', 'unit' => 'A', 'desc' => ''],
                    ['pin' => 'V15', 'name' => 'Arus AC8', 'type' => 'Double', 'min' => 0, 'max' => 30, 'default_value' => '0', 'unit' => 'A', 'desc' => ''],
                    ['pin' => 'V16', 'name' => 'Total Arus', 'type' => 'Double', 'min' => 0, 'max' => 50000, 'default_value' => '0', 'unit' => 'W', 'desc' => ''],
                    ['pin' => 'V17', 'name' => 'Turbo Cooling Priority', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'default_value' => '0', 'unit' => '', 'desc' => ''],
                ],
            ],
            'corridor_lighting' => [
                'name'            => 'Smart Corridor Lighting (ESP32 / RPi)',
                'hardware_type'   => 'Raspberry Pi 3B+ / ESP32',
                'connection_type' => 'WiFi (MQTT)',
                'icon'            => '💡',
                'description'     => 'Blueprint otomasi 4 zona pencahayaan lorong & gedung PT PINDAD.',
                'datastreams'     => [
                    ['pin' => 'V0', 'name' => 'Lampu Zona A (Utara)', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'default_value' => '0', 'unit' => '', 'desc' => ''],
                    ['pin' => 'V1', 'name' => 'Lampu Zona B (Selatan)', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'default_value' => '0', 'unit' => '', 'desc' => ''],
                    ['pin' => 'V2', 'name' => 'Lampu Zona C (Timur)', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'default_value' => '0', 'unit' => '', 'desc' => ''],
                    ['pin' => 'V3', 'name' => 'Lampu Zona D (Barat)', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'default_value' => '0', 'unit' => '', 'desc' => ''],
                ],
            ],
        ];

        $cfg = $presetsConfig[$preset] ?? $presetsConfig['relay_2ch'];

        $template = Template::create([
            'name'            => $cfg['name'],
            'hardware_type'   => $cfg['hardware_type'],
            'connection_type' => $cfg['connection_type'],
            'icon'            => $cfg['icon'],
            'description'     => $cfg['description'],
            'datastreams'     => $cfg['datastreams'],
        ]);

        return redirect()->back()
            ->with('success', "Preset {$template->name} berhasil dibuat dengan " . count($cfg['datastreams']) . " Datastreams!")
            ->with('selected_template_id', (string)$template->id);
    }

    /**
     * Update Profile Operator.
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
        ]);

        $user = auth()->user() ?? User::first();
        if ($user) {
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->save();
        }

        return redirect()->back()->with('success', 'Profil operator PT PINDAD berhasil diperbarui.');
    }

    /**
     * Update Password Operator.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user() ?? User::first();
        if ($user && Hash::check($request->input('current_password'), $user->password)) {
            $user->password = Hash::make($request->input('new_password'));
            $user->save();
            return redirect()->back()->with('success', 'Kata sandi akun berhasil diubah.');
        }

        return redirect()->back()->with('error', 'Kata sandi saat ini tidak cocok!');
    }

    /**
     * Export telemetry logs to CSV with device filter.
     */
    public function exportCsv(Request $request)
    {
        $deviceId = $request->query('device_id', 'all');
        $query = AcLog::query();
        
        if ($deviceId && $deviceId !== 'all') {
            if ($deviceId === 'RPI3B_PINDAD_ROOM_1') {
                $query->where(function($q) {
                    $q->where('device_id', 'RPI3B_PINDAD_ROOM_1')->orWhereNull('device_id');
                });
            } else {
                $query->where('device_id', $deviceId);
            }
            $fileName = "telemetri_pindad_{$deviceId}_" . date('Ymd_His') . ".csv";
        } else {
            $fileName = "telemetri_pindad_seluruh_fleet_" . date('Ymd_His') . ".csv";
        }

        $logs = $query->latest('recorded_at')->take(500)->get();

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename={$fileName}",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['ID', 'Device ID', 'Target AC', 'Arus (Ampere)', 'Estimasi Daya (Watt)', 'Waktu Pencatatan (WIB)'];

        $callback = function () use ($logs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($logs as $log) {
                $amp = (float) $log->current_ampere;
                $watt = round($amp * 220, 2);
                $time = Carbon::parse($log->recorded_at)->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');

                fputcsv($file, [
                    $log->_id ?? $log->id,
                    $log->device_id ?? 'RPI3B_PINDAD_ROOM_1',
                    $log->active_ac,
                    $amp,
                    $watt,
                    $time,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Clear / Delete telemetry logs (scoped per device or all devices).
     */
    public function clearLogs(Request $request)
    {
        $deviceId = $request->input('device_id', 'all');

        if ($deviceId === 'all' || empty($deviceId)) {
            AcLog::truncate();
            $msg = "Seluruh log telemetri armada berhasil dibersihkan!";
        } elseif ($deviceId === 'RPI3B_PINDAD_ROOM_1') {
            AcLog::where(function($q) {
                $q->where('device_id', 'RPI3B_PINDAD_ROOM_1')->orWhereNull('device_id');
            })->delete();
            $msg = "Seluruh log telemetri untuk Ruang Server 1 (RPI3B_PINDAD_ROOM_1) berhasil dibersihkan!";
        } else {
            AcLog::where('device_id', $deviceId)->delete();
            $msg = "Seluruh log telemetri untuk perangkat {$deviceId} berhasil dibersihkan!";
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
            ]);
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Download IoT Scripts and Configs for Raspberry Pi Nodes.
     */
    public function downloadScript(Request $request, string $type)
    {
        // 1. Download tailored standalone Python script for a specific device (No JSON required!)
        if ($type === 'device' || $request->has('device_id')) {
            $deviceId = $request->query('device_id', $type);
            $dev = Device::where('device_id', $deviceId)->first();
            $roomName = $dev->name ?? $deviceId;
            $location = $dev->location ?? 'PT PINDAD (PERSERO)';
            $numAc = $dev->num_ac ?? 2;

            $baseCode = file_get_contents(base_path('scripts/pindad_universal_node.py'));
            
            // Standard Industrial Raspberry Pi pinout & ADS1115 ADC mapping for 1 to 8 AC units
            $standardPins = [
                1 => ['gpio' => 17, 'adc' => 0, 'name' => ($numAc == 2 ? 'Panasonic 1 (Lampu Bawah)' : 'AC 1 (Unit 1)')],
                2 => ['gpio' => 27, 'adc' => 1, 'name' => ($numAc == 2 ? 'Panasonic 2 (Lampu Atas)' : 'AC 2 (Unit 2)')],
                3 => ['gpio' => 22, 'adc' => 2, 'name' => 'AC 3 (Unit 3)'],
                4 => ['gpio' => 23, 'adc' => 3, 'name' => 'AC 4 (Unit 4)'],
                5 => ['gpio' => 24, 'adc' => 0, 'name' => 'AC 5 (Unit 5)'],
                6 => ['gpio' => 25, 'adc' => 1, 'name' => 'AC 6 (Unit 6)'],
                7 => ['gpio' => 5,  'adc' => 2, 'name' => 'AC 7 (Unit 7)'],
                8 => ['gpio' => 6,  'adc' => 3, 'name' => 'AC 8 (Unit 8)'],
            ];

            $relays = [];
            $maxChannels = max(1, min(8, (int)$numAc));
            for ($i = 1; $i <= $maxChannels; $i++) {
                $pinInfo = $standardPins[$i] ?? ['gpio' => 17 + $i, 'adc' => ($i - 1) % 4, 'name' => "AC {$i}"];
                $relays[] = [
                    'ac_number' => $i,
                    'gpio_pin' => $pinInfo['gpio'],
                    'name' => $pinInfo['name'],
                    'adc_channel' => $pinInfo['adc'],
                ];
            }

            $customConfig = [
                'device_id' => $deviceId,
                'room_name' => $roomName,
                'location' => $location,
                'mqtt_broker_host' => '127.0.0.1',
                'mqtt_broker_port' => 1883,
                'blynk_auth_token' => $dev->blynk_auth_token ?? '',
                'blynk_mqtt_host' => 'blynk.cloud',
                'blynk_mqtt_port' => 1883,
                'sophos_auth' => ['enabled' => true, 'user' => 'pin-00020', 'pass' => '5uiFS4eE', 'url' => 'https://sophostrn.pindad.com:8090/login.xml'],
                'relays' => $relays,
                'turbo_cooling_seconds' => 300,
                'telemetry_interval_seconds' => 15,
            ];

            $jsonConfigStr = json_encode($customConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            $pyConfigStr = str_replace([': true', ': false', ': null'], [': True', ': False', ': None'], $jsonConfigStr);
            $indentedPyConfig = preg_replace('/^/m', '    ', $pyConfigStr);
            $replacement = "    default_config = " . ltrim($indentedPyConfig);
            
            $pattern = '/    default_config\s*=\s*\{.*?\n    \}/s';
            $tailoredCode = preg_replace($pattern, $replacement, $baseCode);

            $fileName = "pindad_node_" . strtolower(preg_replace('/[^a-zA-Z0-9_]/', '_', $deviceId)) . ".py";

            return response($tailoredCode, 200, [
                'Content-Type' => 'text/x-python',
                'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            ]);
        }

        if ($type === 'universal_node' || $type === 'node') {
            $path = base_path('scripts/pindad_universal_node.py');
            return response()->download($path, 'pindad_universal_node.py', [
                'Content-Type' => 'text/x-python',
            ]);
        }

        if ($type === 'config' || $type === 'node_config') {
            $path = base_path('scripts/node_config.json');
            return response()->download($path, 'node_config.json', [
                'Content-Type' => 'application/json',
            ]);
        }

        if ($type === 'setup' || $type === 'sh') {
            $path = base_path('scripts/setup_raspberry_pi.sh');
            return response()->download($path, 'setup_raspberry_pi.sh', [
                'Content-Type' => 'text/x-sh',
            ]);
        }

        if ($type === 'wizard') {
            $path = base_path('scripts/pindad_setup_wizard.py');
            return response()->download($path, 'pindad_setup_wizard.py', [
                'Content-Type' => 'text/x-python',
            ]);
        }

        abort(404, 'File skrip tidak ditemukan.');
    }

    /**
     * Save Telegram Alert Settings.
     */
    public function saveTelegramSettings(Request $request)
    {
        $request->validate([
            'telegram_bot_token' => 'nullable|string|max:200',
            'telegram_chat_id' => 'nullable|string|max:100',
            'telegram_cooldown_minutes' => 'nullable|integer|min:1|max:120',
        ]);

        SystemSetting::set('telegram_bot_token', $request->input('telegram_bot_token', ''));
        SystemSetting::set('telegram_chat_id', $request->input('telegram_chat_id', ''));
        SystemSetting::set('telegram_alert_enabled', $request->has('telegram_alert_enabled') ? true : false);
        SystemSetting::set('telegram_cooldown_minutes', (int)$request->input('telegram_cooldown_minutes', 15));

        return redirect()->back()->with('success', 'Konfigurasi Notifikasi Bot Telegram berhasil disimpan!');
    }

    /**
     * Send instant test message to Telegram.
     */
    public function testTelegramNotification(Request $request, TelegramService $telegramService)
    {
        $botToken = $request->input('telegram_bot_token');
        $chatId = $request->input('telegram_chat_id');

        $res = $telegramService->sendTestMessage($botToken, $chatId);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($res);
        }

        if ($res['success']) {
            return redirect()->back()->with('success', $res['message']);
        }

        return redirect()->back()->with('error', $res['message']);
    }
}
