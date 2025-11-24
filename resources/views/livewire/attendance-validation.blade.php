<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-900">Validasi Absensi</h2>
        <p class="text-gray-600">Periksa dan koreksi status kehadiran karyawan sebelum penggajian.</p>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
        <div class="p-6 space-y-4">

            <!-- Filter -->
            <div class="flex flex-col md:flex-row justify-between gap-4">
                <div class="w-full md:w-1/3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" wire:model.live="date"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div class="w-full md:w-1/3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari Karyawan</label>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Nama..."
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
            </div>

            <!-- Tabel Validasi -->
            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Karyawan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Shift</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Jam Masuk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Keterlambatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Status (Aksi)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($schedules as $schedule)
                            @php
                                // Cek apakah ada data attendance untuk jadwal ini
                                $attendance = $attendances[$schedule->id] ?? null;
                            @endphp
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $schedule->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $schedule->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $schedule->shift->name }} <br>
                                    <span class="text-xs">({{ substr($schedule->shift->start_time, 0, 5) }} -
                                        {{ substr($schedule->shift->end_time, 0, 5) }})</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if ($attendance && $attendance->clock_in)
                                        {{ $attendance->clock_in->format('H:i') }}
                                    @else
                                        <span class="text-gray-400 italic">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($attendance && $attendance->late_minutes > 0)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ $attendance->late_minutes }} Menit
                                        </span>
                                    @elseif($attendance && $attendance->clock_in)
                                        <span class="text-xs text-green-600 font-medium">Tepat Waktu</span>
                                    @else
                                        <span class="text-gray-400 italic">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($attendance)
                                        <!-- Jika sudah ada data absen, bisa edit status -->
                                        <select wire:change="updateStatus({{ $attendance->id }}, $event.target.value)"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm
                                            {{ $attendance->status == 'Alpha' ? 'text-red-600 font-bold' : '' }}
                                            {{ $attendance->status == 'Sakit' || $attendance->status == 'Izin' ? 'text-blue-600 font-bold' : '' }}">
                                            <option value="On-Time"
                                                {{ $attendance->status == 'On-Time' ? 'selected' : '' }}>Hadir
                                                (On-Time)</option>
                                            <option value="Late"
                                                {{ $attendance->status == 'Late' ? 'selected' : '' }}>Terlambat
                                            </option>
                                            <option value="Sakit"
                                                {{ $attendance->status == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                            <option value="Izin"
                                                {{ $attendance->status == 'Izin' ? 'selected' : '' }}>Izin</option>
                                            <option value="Alpha"
                                                {{ $attendance->status == 'Alpha' ? 'selected' : '' }}>Alpha (Tanpa
                                                Ket.)</option>
                                        </select>
                                    @else
                                        <!-- Jika belum absen, buat data manual -->
                                        <select
                                            wire:change="createManualAttendance({{ $schedule->id }}, $event.target.value)"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-500">
                                            <option value="" selected disabled>-- Pilih Status --</option>
                                            <option value="Sakit">Set Sakit</option>
                                            <option value="Izin">Set Izin</option>
                                            <option value="Alpha">Set Alpha</option>
                                        </select>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Tidak ada jadwal kerja pada tanggal ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $schedules->links() }}
            </div>
        </div>
    </div>
</div>
