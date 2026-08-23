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

    // CRUD Penjadwalan
    Route::post('/schedules', [DashboardController::class, 'storeSchedule'])->name('schedules.store');
    Route::patch('/schedules/{id}/toggle', [DashboardController::class, 'toggleSchedule'])->name('schedules.toggle');
    Route::delete('/schedules/{id}', [DashboardController::class, 'deleteSchedule'])->name('schedules.destroy');

    // Ekspor Log Telemetri CSV
    Route::get('/logs/export', [DashboardController::class, 'exportCsv'])->name('logs.export');

});
