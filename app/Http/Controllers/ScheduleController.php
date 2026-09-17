<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    protected function isSuperAdmin(): bool
    {
        $role = session('user_role', '');
        $roleType = session('user_role_type', '');
        $nip = session('user_nip', '');
        return ($role === 'Super Administrator' || $role === 'admin' || $roleType === 'admin' || $nip === 'admin' || $nip === 'PINDAD-IOT-2026');
    }

    /**
     * Simpan Aturan Jadwal Rotasi Shift Baru.
     */
    public function storeSchedule(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string',
            'ac_number' => 'required|integer|min:1|max:8',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'description' => 'nullable|string|max:200'
        ]);

        $deviceId = $request->input('device_id');
        $userNip = session('user_nip', 'PINDAD-IOT-2026');

        $schedule = Schedule::create([
            'user_nip' => $userNip,
            'device_id' => $deviceId,
            'ac_number' => (int)$request->input('ac_number'),
            'target_units' => [(int)$request->input('ac_number')],
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'is_active' => true,
            'description' => $request->input('description', "Shift Unit {$request->input('ac_number')}"),
            'created_at' => Carbon::now('Asia/Jakarta'),
            'updated_at' => Carbon::now('Asia/Jakarta')
        ]);

        return redirect()->route('dashboard', ['device_id' => $deviceId])
            ->with('success', "Aturan jadwal rotasi untuk Unit AC {$schedule->ac_number} ({$schedule->start_time} - {$schedule->end_time} WIB) berhasil ditambahkan.");
    }

    /**
     * Perbarui Aturan Jadwal Rotasi Shift.
     */
    public function updateSchedule(Request $request, string $id)
    {
        $schedule = Schedule::find($id);
        if (!$schedule) {
            return redirect()->route('dashboard')->with('error', 'Jadwal tidak ditemukan.');
        }

        $request->validate([
            'ac_number' => 'required|integer|min:1|max:8',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'description' => 'nullable|string|max:200'
        ]);

        $schedule->ac_number = (int)$request->input('ac_number');
        $schedule->target_units = [(int)$request->input('ac_number')];
        $schedule->start_time = $request->input('start_time');
        $schedule->end_time = $request->input('end_time');
        $schedule->description = $request->input('description', $schedule->description);
        $schedule->updated_at = Carbon::now('Asia/Jakarta');
        $schedule->save();

        return redirect()->route('dashboard', ['device_id' => $schedule->device_id])
            ->with('success', "Aturan jadwal rotasi berhasil diperbarui.");
    }

    /**
     * Toggle Aktif/Nonaktif Jadwal Rotasi Shift (AJAX / Form).
     */
    public function toggleSchedule(Request $request, string $id)
    {
        $schedule = Schedule::find($id);
        if (!$schedule) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Jadwal tidak ditemukan.'], 404);
            }
            return redirect()->route('dashboard')->with('error', 'Jadwal tidak ditemukan.');
        }

        $schedule->is_active = !$schedule->is_active;
        $schedule->updated_at = Carbon::now('Asia/Jakarta');
        $schedule->save();

        $statusText = $schedule->is_active ? 'diaktifkan' : 'dinonaktifkan';

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'is_active' => $schedule->is_active,
                'message' => "Jadwal Unit AC {$schedule->ac_number} berhasil {$statusText}."
            ]);
        }

        return redirect()->route('dashboard', ['device_id' => $schedule->device_id])
            ->with('success', "Jadwal Unit AC {$schedule->ac_number} berhasil {$statusText}.");
    }

    /**
     * Hapus Aturan Jadwal.
     */
    public function deleteSchedule(Request $request, string $id)
    {
        $schedule = Schedule::find($id);
        if (!$schedule) {
            return redirect()->route('dashboard')->with('error', 'Jadwal tidak ditemukan.');
        }

        $deviceId = $schedule->device_id;
        $acNumber = $schedule->ac_number;
        $schedule->delete();

        return redirect()->route('dashboard', ['device_id' => $deviceId])
            ->with('success', "Jadwal rotasi Unit AC {$acNumber} berhasil dihapus.");
    }
}
