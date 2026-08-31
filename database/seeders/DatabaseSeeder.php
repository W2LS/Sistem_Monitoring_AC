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

        // 4. Seed Default IoT Device Fleet (Blynk-style)
        \App\Models\Device::updateOrCreate(
            ['device_id' => 'RPI3B_PINDAD_ROOM_1'],
            [
                'name' => 'Ruang Server Utama (Lt. 1)',
                'location' => 'Gedung Divisi Mutu & TI',
                'ip_address' => '192.168.197.64',
                'hardware_type' => 'Raspberry Pi 3B+',
                'status' => 'online',
                'auth_token' => '2zT3Crp6HA5DZQaxI26aftTrFUAuwo3F',
                'num_ac' => 2,
                'description' => 'Sistem pemantauan 2 unit pendingin Panasonic server utama.'
            ]
        );

        \App\Models\Device::updateOrCreate(
            ['device_id' => 'RPI3B_PINDAD_ROOM_2'],
            [
                'name' => 'Ruang Server Cadangan (Lt. 2)',
                'location' => 'Gedung Divisi Rekayasa Industri',
                'ip_address' => '192.168.196.45',
                'hardware_type' => 'Raspberry Pi 3B+',
                'status' => 'standby',
                'auth_token' => 'TMPL_PINDAD_ROOM_2_KEY',
                'num_ac' => 2,
                'description' => 'Sistem monitoring ruang server cadangan divisi rekayasa.'
            ]
        );

        \App\Models\Device::updateOrCreate(
            ['device_id' => 'RPI4_PINDAD_DC_1'],
            [
                'name' => 'Pusat Data Center PINDAD',
                'location' => 'Gedung Data Center Utama',
                'ip_address' => '192.168.196.100',
                'hardware_type' => 'Raspberry Pi 4 Model B',
                'status' => 'standby',
                'auth_token' => 'TMPL_PINDAD_DC_KEY',
                'num_ac' => 4,
                'description' => 'Monitoring beban pendingin ruang server rack data center pusat.'
            ]
        );
    }
}
