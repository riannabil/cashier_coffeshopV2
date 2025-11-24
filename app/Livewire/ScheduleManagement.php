<?php

namespace App\Livewire;

use App\Models\Schedule;
use App\Models\User;
use App\Models\Shift;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rule;

#[Layout('layouts.app')]
class ScheduleManagement extends Component
{
    use WithPagination;

    // Properti Form
    public $user_id;
    public $shift_id;
    public $date;
    public $scheduleId;

    // Properti UI
    public $showModal = false;
    public $isEditing = false;
    public $search = '';
    public $perPage = 10;

    // Data untuk Dropdown
    public $users;
    public $shifts;

    protected $paginationTheme = 'tailwind';

    // Validasi khusus
    protected function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'shift_id' => 'required|exists:shifts,id',
            // Pastikan kombinasi user_id dan date itu UNIK (kecuali untuk id ini sendiri saat edit)
            'date' => [
                'required',
                'date',
                Rule::unique('schedules')->where(function ($query) {
                    return $query->where('user_id', $this->user_id);
                })->ignore($this->scheduleId)
            ],
        ];
    }

    public function mount()
    {
        // Ambil data untuk dropdown sekali saja saat load
        // Kita ambil user yang punya role Karyawan atau Manajer (opsional, semua user juga boleh)
        $this->users = User::all();
        $this->shifts = Shift::all();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->reset(['user_id', 'shift_id', 'date', 'scheduleId', 'isEditing']);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $schedule = Schedule::find($this->scheduleId);
            $schedule->update([
                'user_id' => $this->user_id,
                'shift_id' => $this->shift_id,
                'date' => $this->date,
            ]);
        } else {
            Schedule::create([
                'user_id' => $this->user_id,
                'shift_id' => $this->shift_id,
                'date' => $this->date,
            ]);
        }

        $this->closeModal();
    }

    public function edit($id)
    {
        $schedule = Schedule::findOrFail($id);
        $this->scheduleId = $id;
        $this->user_id = $schedule->user_id;
        $this->shift_id = $schedule->shift_id;
        $this->date = $schedule->date;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function delete($id)
    {
        $schedule = Schedule::find($id);
        if ($schedule) $schedule->delete();
    }

    public function render()
    {
        // Kita join/with user agar bisa search berdasarkan nama karyawan
        $schedules = Schedule::with(['user', 'shift'])
            ->whereHas('user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('date', 'desc') // Urutkan dari tanggal terbaru
            ->paginate($this->perPage);

        return view('livewire.schedule-management', [
            'schedules' => $schedules
        ]);
    }
}
