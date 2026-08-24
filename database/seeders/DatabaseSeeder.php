<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Schedule;
use App\Models\AcLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database in MongoDB.
     */
    public function run(): void
    {
        // 1. Seed Administrator User
        User::updateOrCreate(
            ['email' => 'dicky.akbar@pindad.com'],
            [
                'name' => 'Dicky Akbar Syahputra',
                'password' => Hash::make('pindad123'),
            ]
        );

        // 2. Seed Official 12-Hour Server Room Schedules
        Schedule::truncate();
        
        Schedule::create([
            'label' => 'Shift Siang (Panasonic 1)',
            'start_time' => '06:00:00',
            'end_time' => '18:00:00',
            'is_active' => true,
        ]);

        Schedule::create([
            'label' => 'Shift Malam (Panasonic 2)',
            'start_time' => '18:00:00',
            'end_time' => '06:00:00',
            'is_active' => true,
        ]);

        // 3. Seed Initial Telemetry Logs
        AcLog::create([
            'device_id' => 'ESP32_PINDAD_ROOM_1',
            'active_ac' => 'AC_1_ON',
            'current_ampere' => 2.1500,
            'recorded_at' => Carbon::now()->subSeconds(10),
        ]);

        AcLog::create([
            'device_id' => 'ESP32_PINDAD_ROOM_1',
            'active_ac' => 'AC_2_OFF',
            'current_ampere' => 0.0000,
            'recorded_at' => Carbon::now()->subSeconds(5),
        ]);
    }
}
