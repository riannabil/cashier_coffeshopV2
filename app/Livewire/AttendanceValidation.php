<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.app')]
class AttendanceValidation extends Component
{
    use WithPagination;

    public $date; // Filter tanggal
    public $search = '';

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        // Default tampilkan hari ini
        $this->date = Carbon::today()->format('Y-m-d');
    }

    public function updatingDate()
    {
        $this->resetPage();
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Fungsi untuk mengubah status absensi secara langsung
    public function updateStatus($attendanceId, $newStatus)
    {
        $attendance = Attendance::find($attendanceId);

        if ($attendance) {
            $attendance->update([
                'status' => $newStatus
            ]);

            // Jika diubah jadi 'Sakit'/'Izin', nol-kan keterlambatan agar tidak dipotong
            if (in_array($newStatus, ['Sakit', 'Izin'])) {
                $attendance->update(['late_minutes' => 0]);
            }

            session()->flash('message', 'Status absensi berhasil diperbarui.');
        }
    }

    // Fungsi untuk membuat data absensi manual (jika karyawan lupa absen sama sekali)
    // Ini menangani kasus "Alpha" otomatis
    public function createManualAttendance($scheduleId, $status)
    {
        $schedule = Schedule::find($scheduleId);

        if ($schedule) {
            Attendance::create([
                'user_id' => $schedule->user_id,
                'schedule_id' => $schedule->id,
                'clock_in' => null, // Tidak ada jam masuk
                'clock_out' => null,
                'late_minutes' => 0,
                'status' => $status,
            ]);

            session()->flash('message', 'Status berhasil dibuat manual.');
        }
    }

    public function render()
    {
        // Ambil semua Jadwal pada tanggal yang dipilih
        // Kita join dengan Attendance untuk melihat siapa yang sudah absen dan siapa yang belum
        $schedules = Schedule::with(['user', 'shift'])
            ->where('date', $this->date)
            ->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        // Ambil data attendance yang sudah ada untuk jadwal-jadwal tersebut
        $attendances = Attendance::whereIn('schedule_id', $schedules->pluck('id'))->get()->keyBy('schedule_id');

        return view('livewire.attendance-validation', [
            'schedules' => $schedules,
            'attendances' => $attendances
        ]);
    }
}
