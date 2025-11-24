<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.app')]
class ReportPage extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        // Default: Tampilkan data bulan ini
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function updatingStartDate()
    {
        $this->resetPage();
    }
    public function updatingEndDate()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Query dasar dengan filter tanggal
        $query = Order::with(['user', 'items'])
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->orderBy('created_at', 'desc');

        // Hitung Ringkasan (dari query yang sama)
        // Kita clone query agar pagination tidak rusak
        $summaryQuery = clone $query;
        $totalRevenue = $summaryQuery->sum('total_amount');
        $totalTransactions = $summaryQuery->count();

        // Ambil data untuk tabel (dengan pagination)
        $orders = $query->paginate(10);

        return view('livewire.report-page', [
            'orders' => $orders,
            'totalRevenue' => $totalRevenue,
            'totalTransactions' => $totalTransactions
        ]);
    }
}
