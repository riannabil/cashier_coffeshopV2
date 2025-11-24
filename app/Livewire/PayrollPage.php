<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Payroll;
use App\Models\CompanySetting;
use App\Models\Attendance;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.app')]
class PayrollPage extends Component
{
    use WithPagination;

    public $selectedMonth; // Format: "Y-m" (misal "2025-11")
    public $isCalculated = false;

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        // Default bulan ini
        $this->selectedMonth = Carbon::now()->format('Y-m');
        $this->checkIfCalculated();
    }

    public function updatedSelectedMonth()
    {
        $this->resetPage();
        $this->checkIfCalculated();
    }

    // Cek apakah payroll untuk bulan ini sudah pernah dihitung
    public function checkIfCalculated()
    {
        $this->isCalculated = Payroll::where('period', $this->selectedMonth)->exists();
    }

    // FUNGSI UTAMA: HITUNG GAJI OTOMATIS
    public function calculatePayroll()
    {
        // 1. Ambil setting potongan dari database
        $lateFee = CompanySetting::where('setting_key', 'late_deduction_flat')->value('setting_value') ?? 50000;
        $alphaFee = CompanySetting::where('setting_key', 'alpha_deduction_flat')->value('setting_value') ?? 150000;
        $tolerance = CompanySetting::where('setting_key', 'late_tolerance_minutes')->value('setting_value') ?? 0;

        // 2. Tentukan rentang tanggal bulan yang dipilih
        $startDate = Carbon::parse($this->selectedMonth)->startOfMonth();
        $endDate = Carbon::parse($this->selectedMonth)->endOfMonth();

        // 3. Ambil semua karyawan aktif
        $users = User::all();

        foreach ($users as $user) {
            // A. Hitung total keterlambatan (jumlah kejadian)
            $lateCount = Attendance::where('user_id', $user->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('late_minutes', '>', $tolerance)
                ->where('status', 'Late') // Pastikan statusnya Late
                ->count();

            // B. Hitung total Alpha
            $alphaCount = Attendance::where('user_id', $user->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'Alpha')
                ->count();

            // C. Kalkulasi Rupiah
            $totalLateDeduction = $lateCount * $lateFee;
            $totalAlphaDeduction = $alphaCount * $alphaFee;

            // D. Gaji Final
            $finalSalary = $user->base_salary - $totalLateDeduction - $totalAlphaDeduction;

            // E. Simpan / Update ke tabel Payrolls
            Payroll::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'period' => $this->selectedMonth,
                ],
                [
                    'base_salary' => $user->base_salary,
                    'late_deduction_total' => $totalLateDeduction,
                    'alpha_deduction_total' => $totalAlphaDeduction,
                    'final_salary' => $finalSalary > 0 ? $finalSalary : 0, // Jangan sampai minus
                    'status' => 'Calculated',
                    'created_at' => now()
                ]
            );
        }

        $this->isCalculated = true;
        session()->flash('message', 'Gaji periode ' . $this->selectedMonth . ' berhasil dihitung!');
    }

    // Fungsi untuk menandai sudah dibayar (opsional)
    public function markAsPaid($payrollId)
    {
        $payroll = Payroll::find($payrollId);
        if ($payroll) {
            $payroll->update(['status' => 'Paid']);
        }
    }

    public function render()
    {
        $payrolls = Payroll::with('user')
            ->where('period', $this->selectedMonth)
            ->paginate(10);

        return view('livewire.payroll-page', [
            'payrolls' => $payrolls
        ]);
    }
}
