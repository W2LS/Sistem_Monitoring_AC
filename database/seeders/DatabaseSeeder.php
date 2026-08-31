<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Schedule;
use App\Models\AcLog;
use App\Models\Template;
use App\Models\Device;
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
            'target_ac' => 'all',
            'start_time' => '06:00:00',
            'end_time' => '18:00:00',
            'is_active' => true,
            'device_id' => 'RPI3B_PINDAD_ROOM_1',
        ]);

        Schedule::create([
            'label' => 'Shift Malam (Panasonic 2)',
            'target_ac' => 'all',
            'start_time' => '18:00:00',
            'end_time' => '06:00:00',
            'is_active' => true,
            'device_id' => 'RPI3B_PINDAD_ROOM_1',
        ]);

        // 3. Seed IoT Device Templates (Blynk Standard Datastreams)
        Template::truncate();

        $templateAc = Template::create([
            'name' => 'Dual AC Relay Controller',
            'hardware_type' => 'Raspberry Pi 3B+',
            'connection_type' => 'MQTT Broker (TCP 1883)',
            'icon' => '❄️',
            'description' => 'Blueprint kontrol 2 relay AC dengan sensor arus ACS712 & RTC DS3231.',
            'datastreams' => [
                ['pin' => 'V0', 'name' => 'Relay AC 1 (Panasonic 1)', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'unit' => '', 'desc' => 'Saklar manual/otomatis unit AC 1'],
                ['pin' => 'V1', 'name' => 'Relay AC 2 (Panasonic 2)', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'unit' => '', 'desc' => 'Saklar manual/otomatis unit AC 2'],
                ['pin' => 'V2', 'name' => 'Arus Listrik AC 1', 'type' => 'Double', 'min' => 0, 'max' => 30, 'unit' => 'A', 'desc' => 'Sensor arus ACS712 AC 1'],
                ['pin' => 'V3', 'name' => 'Arus Listrik AC 2', 'type' => 'Double', 'min' => 0, 'max' => 30, 'unit' => 'A', 'desc' => 'Sensor arus ACS712 AC 2'],
                ['pin' => 'V4', 'name' => 'Total Konsumsi Daya', 'type' => 'Integer', 'min' => 0, 'max' => 10000, 'unit' => 'W', 'desc' => 'Akumulasi beban daya terukur'],
                ['pin' => 'V5', 'name' => 'Turbo Cooling Priority', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'unit' => '', 'desc' => 'Status proteksi prioritas pendinginan'],
            ]
        ]);

        $templateLampu = Template::create([
            'name' => 'Smart Industrial Lighting',
            'hardware_type' => 'ESP32 Dual-Core IoT',
            'connection_type' => 'WiFi (IEEE 802.11 b/g/n)',
            'icon' => '💡',
            'description' => 'Blueprint saklar otomatis lampu koridor gedung & sensor intensitas cahaya LDR.',
            'datastreams' => [
                ['pin' => 'V0', 'name' => 'Saklar Lampu Utama', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'unit' => '', 'desc' => 'Kontrol relay lampu selasar'],
                ['pin' => 'V1', 'name' => 'Intensitas Cahaya Ambient', 'type' => 'Integer', 'min' => 0, 'max' => 1024, 'unit' => 'Lux', 'desc' => 'Sensor LDR koridor'],
                ['pin' => 'V2', 'name' => 'Konsumsi Daya Lampu', 'type' => 'Integer', 'min' => 0, 'max' => 500, 'unit' => 'W', 'desc' => 'Daya lampu LED terukur'],
            ]
        ]);

        $templateDc = Template::create([
            'name' => 'Data Center Precision Cooler',
            'hardware_type' => 'Raspberry Pi 4 Model B',
            'connection_type' => 'Gigabit Ethernet LAN',
            'icon' => '🏢',
            'description' => 'Blueprint pendingin presisi ruang server rack data center pusat PT PINDAD.',
            'datastreams' => [
                ['pin' => 'V0', 'name' => 'Cooling Unit 1 & 2', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'unit' => '', 'desc' => 'Compressor 1 & 2'],
                ['pin' => 'V1', 'name' => 'Cooling Unit 3 & 4', 'type' => 'Integer', 'min' => 0, 'max' => 1, 'unit' => '', 'desc' => 'Compressor 3 & 4'],
                ['pin' => 'V2', 'name' => 'Total Beban Rack DC', 'type' => 'Integer', 'min' => 0, 'max' => 20000, 'unit' => 'W', 'desc' => 'Daya pendingin data center'],
            ]
        ]);

        // 4. Seed Default IoT Device Fleet
        Device::truncate();

        Device::create([
            'device_id' => 'RPI3B_PINDAD_ROOM_1',
            'template_id' => (string)$templateAc->_id,
            'name' => 'Monitoring AC Ruang Server 1',
            'type' => 'ac_monitoring',
            'icon' => '❄️',
            'location' => 'Gedung Divisi Mutu & TI (Lt. 1)',
            'ip_address' => '192.168.197.64',
            'hardware_type' => 'Raspberry Pi 3B+',
            'status' => 'online',
            'auth_token' => '2zT3Crp6HA5DZQaxI26aftTrFUAuwo3F',
            'num_ac' => 2,
            'description' => 'Sistem monitoring & kontrol 2 AC Panasonic ruang server utama.',
            'current_values' => [
                'V0' => 1, // AC 1 ON
                'V1' => 0, // AC 2 OFF
                'V2' => 4.23, // 4.23 A
                'V3' => 0.00, // 0.00 A
                'V4' => 935, // 935 Watt
                'V5' => 0,
            ]
        ]);

        Device::create([
            'device_id' => 'ESP32_LAMPU_GEDUNG_MUTU',
            'template_id' => (string)$templateLampu->_id,
            'name' => 'Lampu Otomatis Selasar TI',
            'type' => 'smart_lighting',
            'icon' => '💡',
            'location' => 'Gedung Divisi Mutu & TI (Selasar)',
            'ip_address' => '192.168.196.88',
            'hardware_type' => 'ESP32 Dual-Core IoT',
            'status' => 'online',
            'auth_token' => 'TMPL_PINDAD_LAMPU_KEY',
            'num_ac' => 0,
            'description' => 'Sistem otomatisasi lampu selasar & sensor cahaya LDR.',
            'current_values' => [
                'V0' => 1, // Lampu ON
                'V1' => 450, // 450 Lux
                'V2' => 120, // 120 Watt
            ]
        ]);

        Device::create([
            'device_id' => 'RPI4_PINDAD_DC_1',
            'template_id' => (string)$templateDc->_id,
            'name' => 'Pendingin Presisi Data Center',
            'type' => 'datacenter',
            'icon' => '🏢',
            'location' => 'Gedung Data Center Utama',
            'ip_address' => '192.168.196.100',
            'hardware_type' => 'Raspberry Pi 4 Model B',
            'status' => 'standby',
            'auth_token' => 'TMPL_PINDAD_DC_KEY',
            'num_ac' => 4,
            'description' => 'Monitoring beban pendingin ruang server rack data center pusat.',
            'current_values' => [
                'V0' => 0,
                'V1' => 0,
                'V2' => 0,
            ]
        ]);
    }
}
