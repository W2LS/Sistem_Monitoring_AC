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
     * Memproses percobaan login operator.
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

        // Kredensial Valid (Mendukung NIP resmi & username operator)
        $validAccounts = [
            'PINDAD-IOT-2026' => [
                'password' => 'pindad123',
                'name' => 'Dicky Akbar Syah Putra',
                'division' => 'Divisi Sistem Informasi & Fasilitas',
                'role' => 'Operator System Control'
            ],
            'operator' => [
                'password' => 'pindad123',
                'name' => 'Dicky Akbar Syah Putra',
                'division' => 'Divisi Sistem Informasi & Fasilitas',
                'role' => 'Operator System Control'
            ],
            'admin' => [
                'password' => 'admin123',
                'name' => 'Administrator Server',
                'division' => 'Divisi Sistem Informasi',
                'role' => 'Super Administrator'
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
                'login_time' => now()->format('d M Y, H:i:s WIB')
            ]);

            return redirect()->route('dashboard')->with('success', 'Selamat datang kembali, ' . $user['name'] . '!');
        }

        return back()->withInput()->withErrors([
            'login_error' => 'NIP/Username atau Password tidak sesuai. Silakan periksa kembali.'
        ]);
    }

    /**
     * Mengakhiri sesi login operator.
     */
    public function logout(Request $request)
    {
        $request->session()->forget(['logged_in', 'user_nip', 'user_name', 'user_division', 'user_role', 'login_time']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
