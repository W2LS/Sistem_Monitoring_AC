<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;

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

