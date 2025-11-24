<?php

namespace App\Livewire;

use App\Models\Shift;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ShiftManagement extends Component
{
    use WithPagination;

    public $name, $start_time, $end_time, $shiftId;
    public $showModal = false;
    public $isEditing = false;
    public $search = '';
    public $perPage = 10;

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i', // Format jam:menit (24h)
            'end_time' => 'required|date_format:H:i|different:start_time',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->reset(['name', 'start_time', 'end_time', 'shiftId', 'isEditing']);
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
            $shift = Shift::find($this->shiftId);
            $shift->update([
                'name' => $this->name,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
            ]);
        } else {
            Shift::create([
                'name' => $this->name,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
            ]);
        }

        $this->closeModal();
    }

    public function edit($id)
    {
        $shift = Shift::findOrFail($id);
        $this->shiftId = $id;
        $this->name = $shift->name;
        // Kita ambil 5 karakter pertama (HH:MM) dari format waktu database (HH:MM:SS)
        $this->start_time = substr($shift->start_time, 0, 5);
        $this->end_time = substr($shift->end_time, 0, 5);

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function delete($id)
    {
        $shift = Shift::find($id);
        if ($shift) $shift->delete();
    }

    public function render()
    {
        $shifts = Shift::where('name', 'like', '%' . $this->search . '%')
            ->paginate($this->perPage);

        return view('livewire.shift-management', [
            'shifts' => $shifts
        ]);
    }
}
