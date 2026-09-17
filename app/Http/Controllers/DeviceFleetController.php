<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Schedule;
use App\Models\Template;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DeviceFleetController extends Controller
{
    protected function isSuperAdmin(): bool
    {
        $role = session('user_role', '');
        $roleType = session('user_role_type', '');
        $nip = session('user_nip', '');
        return ($role === 'Super Administrator' || $role === 'admin' || $roleType === 'admin' || $nip === 'admin' || $nip === 'PINDAD-IOT-2026');
    }

    /**
     * Daftarkan Node / Perangkat IoT Baru ke Armada (Super Admin Only).
     */
    public function storeDevice(Request $request)
    {
        if (!$this->isSuperAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Hanya Super Administrator yang berhak mendaftarkan perangkat baru secara langsung.');
        }

        $request->validate([
            'device_id' => 'required|string|unique:devices,device_id|alpha_dash|max:50',
            'name' => 'required|string|max:100',
            'location' => 'required|string|max:100',
            'ip_address' => 'required|ip|unique:devices,ip_address',
            'template_id' => 'required|string',
            'hardware_type' => 'nullable|string',
            'icon' => 'nullable|string',
            'description' => 'nullable|string'
        ], [
            'device_id.unique' => 'Device ID sudah terdaftar di armada SIKOMAT. Gunakan ID unik.',
            'ip_address.unique' => 'IP Address sudah digunakan oleh perangkat lain.',
            'ip_address.ip' => 'Format IP Address tidak valid (Contoh: 192.168.1.100).'
        ]);

        $template = Template::find($request->input('template_id'));
        $numAc = $template ? (int)($template->num_relays ?? 2) : 2;

        $userNip = session('user_nip', 'PINDAD-IOT-2026');

        $initialValues = [];
        for ($i = 0; $i < $numAc; $i++) {
            $initialValues["V{$i}"] = 0;
        }

        $device = Device::create([
            'user_nip' => $userNip,
            'device_id' => strtoupper(trim($request->input('device_id'))),
            'name' => trim($request->input('name')),
            'location' => trim($request->input('location')),
            'ip_address' => trim($request->input('ip_address')),
            'template_id' => $request->input('template_id'),
            'hardware_type' => $request->input('hardware_type', 'Raspberry Pi 3B+'),
            'icon' => $request->input('icon', '⚡'),
            'status' => 'offline',
            'auth_token' => Str::random(32),
            'num_ac' => $numAc,
            'description' => $request->input('description', ''),
            'current_values' => $initialValues,
            'created_at' => Carbon::now('Asia/Jakarta'),
            'updated_at' => Carbon::now('Asia/Jakarta')
        ]);

        return redirect()->route('dashboard', ['device_id' => $device->device_id])
            ->with('success', "Perangkat {$device->name} ({$device->device_id}) berhasil didaftarkan ke armada!");
    }

    /**
     * Perbarui Konfigurasi Perangkat IoT.
     */
    public function updateDevice(Request $request, string $id)
    {
        if (!$this->isSuperAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $device = Device::find($id);
        if (!$device) {
            return redirect()->route('dashboard')->with('error', 'Perangkat tidak ditemukan.');
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'location' => 'required|string|max:100',
            'ip_address' => 'required|ip',
            'template_id' => 'required|string',
            'hardware_type' => 'nullable|string',
            'icon' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        $template = Template::find($request->input('template_id'));
        $numAc = $template ? (int)($template->num_relays ?? 2) : $device->num_ac;

        $device->name = trim($request->input('name'));
        $device->location = trim($request->input('location'));
        $device->ip_address = trim($request->input('ip_address'));
        $device->template_id = $request->input('template_id');
        $device->hardware_type = $request->input('hardware_type', $device->hardware_type);
        $device->icon = $request->input('icon', $device->icon);
        $device->description = $request->input('description', $device->description);
        $device->num_ac = $numAc;
        $device->updated_at = Carbon::now('Asia/Jakarta');
        $device->save();

        return redirect()->route('dashboard', ['device_id' => $device->device_id])
            ->with('success', "Konfigurasi perangkat {$device->name} berhasil diperbarui.");
    }

    /**
     * Hapus Perangkat dari Armada IoT.
     */
    public function deleteDevice(Request $request, string $id)
    {
        if (!$this->isSuperAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $device = Device::find($id);
        if (!$device) {
            return redirect()->route('dashboard')->with('error', 'Perangkat tidak ditemukan.');
        }

        $name = $device->name;
        $deviceId = $device->device_id;

        // Hapus jadwal terkait
        Schedule::where('device_id', $deviceId)->delete();

        $device->delete();

        return redirect()->route('dashboard')->with('success', "Perangkat {$name} ({$deviceId}) berhasil dihapus dari armada.");
    }
}
