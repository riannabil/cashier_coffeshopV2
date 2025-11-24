<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Models\Schedule;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
class AttendancePage extends Component
{
    public $todaySchedule;
    public $attendanceToday;
    public $currentTime;

    public function mount()
    {
        $this->refreshData();
    }

    public function refreshData()
    {
        $today = Carbon::today()->format('Y-m-d');
        $userId = Auth::id();

        // 1. Cari jadwal user hari ini
        $this->todaySchedule = Schedule::with('shift')
            ->where('user_id', $userId)
            ->where('date', $today)
            ->first();

        // 2. Cari data absensi hari ini (jika sudah absen)
        if ($this->todaySchedule) {
            $this->attendanceToday = Attendance::where('schedule_id', $this->todaySchedule->id)->first();
        }

        // Update jam sekarang
        $this->currentTime = Carbon::now()->format('H:i');
    }

    public function clockIn()
    {
        if (!$this->todaySchedule) return;

        $now = Carbon::now();
        // Pastikan parsing jam shift menggunakan tanggal hari ini yang benar
        $shiftStart = Carbon::parse($this->todaySchedule->date . ' ' . $this->todaySchedule->shift->start_time);

        // Logika Perhitungan Keterlambatan yang Lebih Kuat
        $lateMinutes = 0;

        if ($now->greaterThan($shiftStart)) {
            // Hitung selisih menit
            $minutesDiff = $now->diffInMinutes($shiftStart);
            // Pastikan selalu positif (absolute value) dan integer
            $lateMinutes = intval(abs($minutesDiff));
        }

        // Tentukan status
        $status = $lateMinutes > 0 ? 'Late' : 'On-Time';

        Attendance::create([
            'user_id' => Auth::id(),
            'schedule_id' => $this->todaySchedule->id,
            'clock_in' => $now,
            'late_minutes' => $lateMinutes, // Nilai ini sekarang pasti positif
            'status' => $status,
        ]);

        session()->flash('message', 'Berhasil Clock In pada ' . $now->format('H:i'));
        $this->refreshData();
    }

    public function clockOut()
    {
        if (!$this->attendanceToday) return;

        $now = Carbon::now();

        $this->attendanceToday->update([
            'clock_out' => $now,
        ]);

        session()->flash('message', 'Berhasil Clock Out pada ' . $now->format('H:i') . '. Terima kasih!');
        $this->refreshData();
    }

    public function render()
    {
        return view('livewire.attendance-page');
    }
}
