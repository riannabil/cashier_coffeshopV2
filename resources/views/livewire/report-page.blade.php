<div>
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-900">Laporan Penjualan</h2>
    </div>

    <!-- Filter Tanggal -->
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6 flex items-end space-x-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
            <input type="date" wire:model.live="startDate"
                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
            <input type="date" wire:model.live="endDate"
                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
    </div>

    <!-- Ringkasan (Cards) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Card Total Omzet -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-green-500">
            <div class="p-6">
                <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Omzet</div>
                <div class="mt-2 text-3xl font-bold text-gray-900">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </div>
                <div class="mt-1 text-sm text-gray-500">
                    Periode {{ \Carbon\Carbon::parse($startDate)->format('d M') }} -
                    {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                </div>
            </div>
        </div>

        <!-- Card Total Transaksi -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-blue-500">
            <div class="p-6">
                <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Transaksi</div>
                <div class="mt-2 text-3xl font-bold text-gray-900">
                    {{ $totalTransactions }} <span class="text-lg font-normal text-gray-500">Pesanan</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Riwayat Transaksi -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Transaksi</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                ID Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Kasir</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($orders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                    #{{ $order->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $order->user->name ?? 'Unknown' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $order->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Tidak ada transaksi pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
