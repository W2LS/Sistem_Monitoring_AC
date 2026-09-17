<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Template;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DeveloperZoneController extends Controller
{
    protected function isSuperAdmin(): bool
    {
        $role = session('user_role', '');
        $roleType = session('user_role_type', '');
        $nip = session('user_nip', '');
        return ($role === 'Super Administrator' || $role === 'admin' || $roleType === 'admin' || $nip === 'admin' || $nip === 'PINDAD-IOT-2026');
    }

    /**
     * Buat Blueprint / Template Hardware Baru (Super Admin Only).
     */
    public function storeTemplate(Request $request)
    {
        if (!$this->isSuperAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Akses Developer Zone terbatas hanya untuk Super Administrator.');
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'hardware_type' => 'required|string|max:100',
            'connection_type' => 'required|string|max:50',
            'num_relays' => 'required|integer|min:1|max:8',
            'icon' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        $numRelays = (int)$request->input('num_relays');
        $datastreams = [];

        // Generate Datastreams otomatis
        for ($i = 0; $i < $numRelays; $i++) {
            $unit = $i + 1;
            $datastreams[] = [
                'pin' => "V{$i}",
                'name' => "Saklar Relay Unit AC {$unit}",
                'type' => 'Integer',
                'min' => 0,
                'max' => 1,
                'unit' => 'Binary',
                'description' => "Kontrol digital saklar ON/OFF Unit AC {$unit}"
            ];
        }

        $datastreams[] = [
            'pin' => 'V10',
            'name' => 'Suhu Ruangan',
            'type' => 'Double',
            'min' => 0,
            'max' => 50,
            'unit' => '°C',
            'description' => 'Sensor suhu DS18B20 / DHT22'
        ];

        for ($i = 0; $i < $numRelays; $i++) {
            $unit = $i + 1;
            $pinIndex = 20 + $i;
            $datastreams[] = [
                'pin' => "V{$pinIndex}",
                'name' => "Sensor Arus ACS712 AC {$unit}",
                'type' => 'Double',
                'min' => 0,
                'max' => 30,
                'unit' => 'Ampere',
                'description' => "Beban arus riil ACS712 Unit AC {$unit}"
            ];
        }

        $datastreams[] = [
            'pin' => 'V30',
            'name' => 'Status Turbo Cooling',
            'type' => 'Integer',
            'min' => 0,
            'max' => 1,
            'unit' => 'Binary',
            'description' => 'Mode darurat pendinginan ganda aktif'
        ];

        $userNip = session('user_nip', 'PINDAD-IOT-2026');

        $template = Template::create([
            'user_nip' => $userNip,
            'name' => trim($request->input('name')),
            'hardware_type' => $request->input('hardware_type'),
            'connection_type' => $request->input('connection_type'),
            'num_relays' => $numRelays,
            'icon' => $request->input('icon', '⚡'),
            'description' => $request->input('description', ''),
            'datastreams' => $datastreams,
            'created_at' => Carbon::now('Asia/Jakarta'),
            'updated_at' => Carbon::now('Asia/Jakarta')
        ]);

        return redirect()->route('dashboard', ['tab' => 'devzone'])
            ->with('success', "Template Blueprint '{$template->name}' berhasil dibuat dengan " . count($datastreams) . " Datastreams.");
    }

    /**
     * Perbarui Template Blueprint.
     */
    public function updateTemplate(Request $request, string $id)
    {
        if (!$this->isSuperAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $template = Template::find($id);
        if (!$template) {
            return redirect()->route('dashboard')->with('error', 'Template tidak ditemukan.');
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'hardware_type' => 'required|string',
            'connection_type' => 'required|string',
            'icon' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        $template->name = trim($request->input('name'));
        $template->hardware_type = $request->input('hardware_type');
        $template->connection_type = $request->input('connection_type');
        $template->icon = $request->input('icon', $template->icon);
        $template->description = $request->input('description', $template->description);
        $template->updated_at = Carbon::now('Asia/Jakarta');
        $template->save();

        return redirect()->route('dashboard', ['tab' => 'devzone'])
            ->with('success', "Template '{$template->name}' berhasil diperbarui.");
    }

    /**
     * Hapus Template Blueprint.
     */
    public function deleteTemplate(Request $request, string $id)
    {
        if (!$this->isSuperAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $template = Template::find($id);
        if (!$template) {
            return redirect()->route('dashboard')->with('error', 'Template tidak ditemukan.');
        }

        // Cek apakah sedang digunakan oleh device
        $usedBy = Device::where('template_id', $id)->count();
        if ($usedBy > 0) {
            return redirect()->route('dashboard', ['tab' => 'devzone'])
                ->with('error', "Template '{$template->name}' tidak dapat dihapus karena masih digunakan oleh {$usedBy} perangkat aktif.");
        }

        $name = $template->name;
        $template->delete();

        return redirect()->route('dashboard', ['tab' => 'devzone'])
            ->with('success', "Template '{$name}' berhasil dihapus.");
    }

    /**
     * Tambah Datastream Pin Baru ke Template.
     */
    public function addDatastream(Request $request, string $id)
    {
        if (!$this->isSuperAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $template = Template::find($id);
        if (!$template) {
            return redirect()->route('dashboard')->with('error', 'Template tidak ditemukan.');
        }

        $request->validate([
            'pin' => 'required|string|max:10',
            'name' => 'required|string|max:100',
            'type' => 'required|string|in:Integer,Double,String',
            'min' => 'nullable|numeric',
            'max' => 'nullable|numeric',
            'unit' => 'nullable|string|max:20',
            'description' => 'nullable|string'
        ]);

        $pin = strtoupper(trim($request->input('pin')));
        $datastreams = $template->datastreams ?? [];

        // Check if pin already exists
        foreach ($datastreams as $ds) {
            if (strtoupper($ds['pin'] ?? '') === $pin) {
                return redirect()->route('dashboard', ['tab' => 'devzone'])
                    ->with('error', "Pin Virtual '{$pin}' sudah ada di Template ini.");
            }
        }

        $datastreams[] = [
            'pin' => $pin,
            'name' => trim($request->input('name')),
            'type' => $request->input('type'),
            'min' => $request->input('min', 0),
            'max' => $request->input('max', 1),
            'unit' => $request->input('unit', ''),
            'description' => $request->input('description', '')
        ];

        $template->datastreams = $datastreams;
        $template->updated_at = Carbon::now('Asia/Jakarta');
        $template->save();

        return redirect()->route('dashboard', ['tab' => 'devzone'])
            ->with('success', "Pin Datastream '{$pin}' berhasil ditambahkan ke Template '{$template->name}'.");
    }

    /**
     * Hapus Pin Datastream dari Template.
     */
    public function removeDatastream(Request $request, string $id, string $pin)
    {
        if (!$this->isSuperAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $template = Template::find($id);
        if (!$template) {
            return redirect()->route('dashboard')->with('error', 'Template tidak ditemukan.');
        }

        $pinUpper = strtoupper($pin);
        $datastreams = $template->datastreams ?? [];
        $newDatastreams = [];

        foreach ($datastreams as $ds) {
            if (strtoupper($ds['pin'] ?? '') !== $pinUpper) {
                $newDatastreams[] = $ds;
            }
        }

        $template->datastreams = $newDatastreams;
        $template->updated_at = Carbon::now('Asia/Jakarta');
        $template->save();

        return redirect()->route('dashboard', ['tab' => 'devzone'])
            ->with('success', "Pin Datastream '{$pinUpper}' berhasil dihapus dari Template.");
    }

    /**
     * Buat Preset Template Cepat (1, 2, 4, 8 Channel).
     */
    public function createPresetTemplate(Request $request)
    {
        if (!$this->isSuperAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'preset_type' => 'required|string|in:1_channel,2_channel,4_channel,8_channel',
            'hardware_type' => 'nullable|string',
            'connection_type' => 'nullable|string'
        ]);

        $channelsMap = [
            '1_channel' => 1,
            '2_channel' => 2,
            '4_channel' => 4,
            '8_channel' => 8,
        ];

        $channels = $channelsMap[$request->input('preset_type')] ?? 2;
        $hardware = $request->input('hardware_type', 'Raspberry Pi 3B+ / 4B');
        $connection = $request->input('connection_type', 'MQTT Broker Terpusat (TCP 1883)');

        $req = new Request([
            'name' => "Template Standar {$channels} Channel PINDAD",
            'hardware_type' => $hardware,
            'connection_type' => $connection,
            'num_relays' => $channels,
            'icon' => '⚡',
            'description' => "Cetak biru konfigurasi otomatis {$channels} Channel Relai + Sensor Arus ACS712 & Suhu."
        ]);

        return $this->storeTemplate($req);
    }

    /**
     * Ekspor Template ke File JSON.
     */
    public function exportTemplate(string $id)
    {
        $template = Template::find($id);
        if (!$template) {
            return redirect()->route('dashboard')->with('error', 'Template tidak ditemukan.');
        }

        $exportData = [
            'sikomat_version' => '2.6.0',
            'exported_at' => Carbon::now('Asia/Jakarta')->toIso8601String(),
            'template' => [
                'name' => $template->name,
                'hardware_type' => $template->hardware_type,
                'connection_type' => $template->connection_type,
                'num_relays' => $template->num_relays,
                'icon' => $template->icon,
                'description' => $template->description,
                'datastreams' => $template->datastreams
            ]
        ];

        $filename = 'sikomat_template_' . Str::slug($template->name) . '_' . date('Ymd_His') . '.json';

        return response(json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    /**
     * Impor Template dari File JSON.
     */
    public function importTemplate(Request $request)
    {
        if (!$this->isSuperAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'template_file' => 'required|file|mimes:json,txt|max:2048'
        ]);

        $fileContent = file_get_contents($request->file('template_file')->getRealPath());
        $jsonData = json_decode($fileContent, true);

        if (!$jsonData || !isset($jsonData['template'])) {
            return redirect()->route('dashboard', ['tab' => 'devzone'])
                ->with('error', 'Format file JSON Blueprint tidak valid untuk SIKOMAT.');
        }

        $tmplData = $jsonData['template'];
        $userNip = session('user_nip', 'PINDAD-IOT-2026');

        $template = Template::create([
            'user_nip' => $userNip,
            'name' => ($tmplData['name'] ?? 'Imported Template') . ' (Imported)',
            'hardware_type' => $tmplData['hardware_type'] ?? 'Raspberry Pi 3B+',
            'connection_type' => $tmplData['connection_type'] ?? 'MQTT Broker Terpusat (TCP 1883)',
            'num_relays' => (int)($tmplData['num_relays'] ?? 2),
            'icon' => $tmplData['icon'] ?? '⚡',
            'description' => $tmplData['description'] ?? '',
            'datastreams' => $tmplData['datastreams'] ?? [],
            'created_at' => Carbon::now('Asia/Jakarta'),
            'updated_at' => Carbon::now('Asia/Jakarta')
        ]);

        return redirect()->route('dashboard', ['tab' => 'devzone'])
            ->with('success', "Template Blueprint '{$template->name}' berhasil diimpor!");
    }
}
