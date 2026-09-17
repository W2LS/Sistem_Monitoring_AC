<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLoginForm()
    {
        if (session('logged_in')) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Memproses percobaan login operator / admin.
     */
    public function login(Request $request)
    {
        $request->validate([
            'nip' => 'required|string',
            'password' => 'required|string',
        ], [
            'nip.required' => 'NIP / Username wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $nipInput = trim($request->input('nip'));
        $passwordInput = trim($request->input('password'));

        // Matriks Akun Valid SIKOMAT PT PINDAD (Super Admin vs Operator Ruangan)
        $validAccounts = [
            'admin' => [
                'password' => 'admin123',
                'name' => 'Administrator Server',
                'division' => 'Divisi Mutu & Teknologi Informasi',
                'role' => 'Super Administrator',
                'role_type' => 'admin',
                'assigned_devices' => ['*']
            ],
            'PINDAD-IOT-2026' => [
                'password' => 'pindad123',
                'name' => 'Dicky Akbar Syah Putra',
                'division' => 'Divisi Mutu & Teknologi Informasi',
                'role' => 'Super Administrator',
                'role_type' => 'admin',
                'assigned_devices' => ['*']
            ],
            'operator' => [
                'password' => 'pindad123',
                'name' => 'Dicky Akbar Syah Putra',
                'division' => 'Divisi Mutu & TI - Ruang Server',
                'role' => 'Operator Ruangan',
                'role_type' => 'operator',
                'assigned_devices' => ['RPI3B_PINDAD_ROOM_1', 'RPI3B_SERVER_TELEPON']
            ],
            'OP-MUTU-01' => [
                'password' => 'pindad123',
                'name' => 'Operator Divisi Mutu',
                'division' => 'Divisi Mutu & Inspeksi Fasilitas',
                'role' => 'Operator Ruangan',
                'role_type' => 'operator',
                'assigned_devices' => ['RPI3B_PINDAD_ROOM_1']
            ],
        ];

        if (array_key_exists($nipInput, $validAccounts) && $validAccounts[$nipInput]['password'] === $passwordInput) {
            $user = $validAccounts[$nipInput];

            session([
                'logged_in' => true,
                'user_nip' => $nipInput,
                'user_name' => $user['name'],
                'user_division' => $user['division'],
                'user_role' => $user['role'],
                'user_role_type' => $user['role_type'],
                'assigned_devices' => $user['assigned_devices'],
                'login_time' => now()->format('d M Y, H:i:s WIB')
            ]);

            return redirect()->route('dashboard')->with('success', 'Selamat datang kembali, ' . $user['name'] . ' (' . $user['role'] . ')!');
        }

        return back()->withInput()->withErrors([
            'login_error' => 'NIP/Username atau Password tidak sesuai. Silakan periksa kembali.'
        ]);
    }

    /**
     * Mengakhiri sesi login.
     */
    public function logout(Request $request)
    {
        $request->session()->forget(['logged_in', 'user_nip', 'user_name', 'user_division', 'user_role', 'user_role_type', 'assigned_devices', 'login_time']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
