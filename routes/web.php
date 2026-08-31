<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

// --- ROUTE AUTENTIKASI ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- ROUTE TERPROTEKSI OPERATOR DASHBOARD ---
Route::middleware('auth.session')->group(function () {
    
    // Dashboard Halaman Utama
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // API logs untuk AJAX real-time chart & status
    Route::get('/api/logs', [DashboardController::class, 'apiLogs'])->name('api.logs');

    // Aksi kontrol relay manual lewat MQTT
    Route::post('/ac/control', [DashboardController::class, 'toggleAc'])->name('ac.control');
    Route::post('/devices/control-stream', [DashboardController::class, 'toggleStream'])->name('devices.controlStream');

    // CRUD Penjadwalan
    Route::post('/schedules', [DashboardController::class, 'storeSchedule'])->name('schedules.store');
    Route::put('/schedules/{id}', [DashboardController::class, 'updateSchedule'])->name('schedules.update');
    Route::patch('/schedules/{id}/toggle', [DashboardController::class, 'toggleSchedule'])->name('schedules.toggle');
    Route::delete('/schedules/{id}', [DashboardController::class, 'deleteSchedule'])->name('schedules.destroy');

    // CRUD Manajemen Perangkat IoT (Blynk Fleet-Style)
    Route::post('/devices', [DashboardController::class, 'storeDevice'])->name('devices.store');
    Route::put('/devices/{id}', [DashboardController::class, 'updateDevice'])->name('devices.update');
    Route::delete('/devices/{id}', [DashboardController::class, 'deleteDevice'])->name('devices.destroy');
    Route::post('/devices/master-control', [DashboardController::class, 'masterControl'])->name('devices.masterControl');

    // CRUD Developer Zone (Templates & Datastreams Console ala Blynk IoT)
    Route::post('/templates', [DashboardController::class, 'storeTemplate'])->name('templates.store');
    Route::put('/templates/{id}', [DashboardController::class, 'updateTemplate'])->name('templates.update');
    Route::delete('/templates/{id}', [DashboardController::class, 'deleteTemplate'])->name('templates.destroy');
    Route::post('/templates/{id}/datastreams', [DashboardController::class, 'addDatastream'])->name('templates.addDatastream');
    Route::delete('/templates/{id}/datastreams/{pin}', [DashboardController::class, 'deleteDatastream'])->name('templates.deleteDatastream');

    // Pengelolaan Akun & Profil Operator
    Route::post('/profile/update', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [DashboardController::class, 'updatePassword'])->name('profile.password');

    // Ekspor Log Telemetri CSV (Bisa per-device)
    Route::get('/logs/export', [DashboardController::class, 'exportCsv'])->name('logs.export');

    // Download Skrip & Konfigurasi IoT Raspberry Pi
    Route::get('/scripts/download/{type}', [DashboardController::class, 'downloadScript'])->name('scripts.download');

});
