<?php

namespace App\Livewire;

use App\Models\CompanySetting;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class SettingsPage extends Component
{
    public $late_deduction;
    public $alpha_deduction;
    public $late_tolerance_minutes;

    public function mount()
    {
        // Ambil setting, atau buat default jika belum ada
        $this->late_deduction = $this->getSetting('late_deduction_flat', '50000', 'Potongan gaji jika terlambat (per kejadian)');
        $this->alpha_deduction = $this->getSetting('alpha_deduction_flat', '150000', 'Potongan gaji jika alpha (per hari)');
        $this->late_tolerance_minutes = $this->getSetting('late_tolerance_minutes', '0', 'Toleransi keterlambatan (menit)');
    }

    // Helper untuk mengambil/membuat setting
    private function getSetting($key, $defaultValue, $description)
    {
        $setting = CompanySetting::firstOrCreate(
            ['setting_key' => $key],
            ['setting_value' => $defaultValue, 'description' => $description]
        );
        return $setting->setting_value;
    }

    public function save()
    {
        // Validasi
        $this->validate([
            'late_deduction' => 'required|numeric|min:0',
            'alpha_deduction' => 'required|numeric|min:0',
            'late_tolerance_minutes' => 'required|numeric|min:0',
        ]);

        // Simpan ke database
        CompanySetting::where('setting_key', 'late_deduction_flat')
            ->update(['setting_value' => $this->late_deduction]);

        CompanySetting::where('setting_key', 'alpha_deduction_flat')
            ->update(['setting_value' => $this->alpha_deduction]);

        CompanySetting::where('setting_key', 'late_tolerance_minutes')
            ->update(['setting_value' => $this->late_tolerance_minutes]);

        session()->flash('message', 'Pengaturan berhasil disimpan!');
    }

    public function render()
    {
        return view('livewire.settings-page');
    }
}
