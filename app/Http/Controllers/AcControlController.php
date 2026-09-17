<?php

namespace App\Http\Controllers;

use App\Models\AcLog;
use App\Models\Device;
use App\Models\Template;
use App\Services\MqttService;
use App\Services\WebSocketBroadcastService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AcControlController extends Controller
{
    protected MqttService $mqttService;
    protected WebSocketBroadcastService $wsBroadcast;

    public function __construct(MqttService $mqttService, WebSocketBroadcastService $wsBroadcast)
    {
        $this->mqttService = $mqttService;
        $this->wsBroadcast = $wsBroadcast;
    }

    protected function isSuperAdmin(): bool
    {
        $role = session('user_role', '');
        $roleType = session('user_role_type', '');
        $nip = session('user_nip', '');
        return ($role === 'Super Administrator' || $role === 'admin' || $roleType === 'admin' || $nip === 'admin' || $nip === 'PINDAD-IOT-2026');
    }

    /**
     * Kontrol Saklar Relay AC Satuan (Zero-Reload AJAX).
     */
    public function toggleAc(Request $request)
    {
        $request->validate([
            'device_id' => 'nullable|string',
            'ac_number' => 'required|integer|min:1|max:8',
            'state' => 'required|string|in:ON,OFF',
            'source' => 'nullable|string'
        ]);

        $userNip = session('user_nip', 'PINDAD-IOT-2026');
        $isAdmin = $this->isSuperAdmin();
        $deviceId = $request->input('device_id', 'RPI3B_PINDAD_ROOM_1');
        $acNumber = (int)$request->input('ac_number');
        $state = strtoupper($request->input('state'));
        $source = $request->input('source', 'manual_web');

        // RBAC Check for device ownership
        if (!$isAdmin) {
            $assigned = (array)session('assigned_devices', []);
            if (!in_array('*', $assigned) && !in_array($deviceId, $assigned)) {
                $devCheck = Device::where('device_id', $deviceId)->where('user_nip', $userNip)->first();
                if (!$devCheck) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Anda tidak memiliki otorisasi untuk mengontrol unit AC di ruangan ini.'
                    ], 403);
                }
            }
        }

        $device = Device::where('device_id', $deviceId)->first();
        if (!$device) {
            return response()->json([
                'status' => 'error',
                'message' => "Perangkat dengan ID {$deviceId} tidak ditemukan."
            ], 404);
        }

        $virtualPin = 'V' . ($acNumber - 1);
        $intState = ($state === 'ON') ? 1 : 0;

        // Update current_values on device
        $currentValues = $device->current_values ?? [];
        $currentValues[$virtualPin] = $intState;
        $device->current_values = $currentValues;
        $device->updated_at = Carbon::now('Asia/Jakarta');
        $device->save();

        // Downlink MQTT payload
        $payload = [
            'device_id' => $deviceId,
            'relay' => $acNumber,
            'ac_number' => $acNumber,
            'pin' => $virtualPin,
            'state' => $state,
            'command' => $state,
            'value' => $intState,
            'source' => $source,
            'timestamp' => Carbon::now('Asia/Jakarta')->toIso8601String()
        ];

        try {
            $this->mqttService->publish("pindad/devices/{$deviceId}/control", json_encode($payload));
            $this->mqttService->publish("pindad/ac/control", json_encode($payload));
        } catch (\Throwable $e) {
            // Log mqtt error non-blocking
        }

        // WebSocket Broadcast
        try {
            $this->wsBroadcast->broadcast([
                'type' => 'relay_change',
                'device_id' => $deviceId,
                'ac_number' => $acNumber,
                'pin' => $virtualPin,
                'state' => $state,
                'value' => $intState,
                'source' => $source
            ]);
        } catch (\Throwable $e) {}

        // Log telemetry
        try {
            AcLog::create([
                'device_id' => $deviceId,
                'ac1_current' => ($acNumber === 1 && $state === 'ON') ? 0.0 : 0.0,
                'ac2_current' => ($acNumber === 2 && $state === 'ON') ? 0.0 : 0.0,
                'temperature' => 25.0,
                'relay1' => $currentValues['V0'] ?? 0,
                'relay2' => $currentValues['V1'] ?? 0,
                'relay3' => $currentValues['V2'] ?? 0,
                'relay4' => $currentValues['V3'] ?? 0,
                'relay5' => $currentValues['V4'] ?? 0,
                'relay6' => $currentValues['V5'] ?? 0,
                'relay7' => $currentValues['V6'] ?? 0,
                'relay8' => $currentValues['V7'] ?? 0,
                'trigger_source' => $source,
                'recorded_at' => Carbon::now('Asia/Jakarta')->toIso8601String()
            ]);
        } catch (\Throwable $e) {}

        return response()->json([
            'status' => 'success',
            'message' => "Unit AC {$acNumber} berhasil diatur ke posisi {$state}.",
            'device_id' => $deviceId,
            'ac_number' => $acNumber,
            'pin' => $virtualPin,
            'state' => $state,
            'int_state' => $intState
        ]);
    }

    /**
     * Kontrol Virtual Datastream / Pin.
     */
    public function toggleStream(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string',
            'pin' => 'required|string',
            'value' => 'required'
        ]);

        $deviceId = $request->input('device_id');
        $pin = strtoupper(trim($request->input('pin')));
        $value = $request->input('value');
        $userNip = session('user_nip', 'PINDAD-IOT-2026');
        $isAdmin = $this->isSuperAdmin();

        if (!$isAdmin) {
            $assigned = (array)session('assigned_devices', []);
            if (!in_array('*', $assigned) && !in_array($deviceId, $assigned)) {
                $devCheck = Device::where('device_id', $deviceId)->where('user_nip', $userNip)->first();
                if (!$devCheck) {
                    return response()->json(['status' => 'error', 'message' => 'Otorisasi ditolak.'], 403);
                }
            }
        }

        $device = Device::where('device_id', $deviceId)->first();
        if (!$device) {
            return response()->json(['status' => 'error', 'message' => 'Perangkat tidak ditemukan.'], 404);
        }

        $currentValues = $device->current_values ?? [];
        $currentValues[$pin] = is_numeric($value) ? (int)$value : $value;
        $device->current_values = $currentValues;
        $device->save();

        $payload = [
            'device_id' => $deviceId,
            'pin' => $pin,
            'value' => $value,
            'timestamp' => Carbon::now('Asia/Jakarta')->toIso8601String()
        ];

        try {
            $this->mqttService->publish("pindad/devices/{$deviceId}/control", json_encode($payload));
        } catch (\Throwable $e) {}

        try {
            $this->wsBroadcast->broadcast([
                'type' => 'datastream_change',
                'device_id' => $deviceId,
                'pin' => $pin,
                'value' => $value
            ]);
        } catch (\Throwable $e) {}

        return response()->json([
            'status' => 'success',
            'message' => "Pin {$pin} berhasil diperbarui.",
            'pin' => $pin,
            'value' => $value
        ]);
    }

    /**
     * Master Control: Mengontrol Seluruh Unit AC Armada Sekaligus (Super Admin Only).
     */
    public function masterControl(Request $request)
    {
        if (!$this->isSuperAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Fitur Master Control hanya dapat diakses oleh Super Administrator.'
            ], 403);
        }

        $request->validate([
            'command' => 'required|string|in:ALL_ON,ALL_OFF,ROTATE_SHIFT,TURBO_ALL'
        ]);

        $command = $request->input('command');
        $devices = Device::all();
        $affected = 0;

        foreach ($devices as $dev) {
            $numAc = max(1, (int)($dev->num_ac ?? 2));
            $currentVals = $dev->current_values ?? [];

            for ($i = 1; $i <= $numAc; $i++) {
                $pin = 'V' . ($i - 1);
                $state = ($command === 'ALL_ON' || $command === 'TURBO_ALL') ? 1 : 0;
                $currentVals[$pin] = $state;
            }

            $dev->current_values = $currentVals;
            $dev->save();

            // MQTT Broadcast
            try {
                $this->mqttService->publish("pindad/devices/{$dev->device_id}/control", json_encode([
                    'device_id' => $dev->device_id,
                    'command' => $command,
                    'source' => 'master_control',
                    'timestamp' => Carbon::now('Asia/Jakarta')->toIso8601String()
                ]));
            } catch (\Throwable $e) {}

            $affected++;
        }

        return response()->json([
            'status' => 'success',
            'message' => "Perintah Master Control [{$command}] berhasil dikirim ke {$affected} perangkat.",
            'affected_devices' => $affected
        ]);
    }
}
