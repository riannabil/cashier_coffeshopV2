<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-900">Absensi Harian</h2>
        <p class="text-gray-600">Halo, {{ Auth::user()->name }}!</p>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <!-- Kartu Status Jadwal -->
    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden mb-6">
        <div class="p-6 text-center">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Jadwal Hari Ini</h3>
            <div class="text-4xl font-bold text-gray-800 mb-2">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </div>

            @if ($todaySchedule)
                <div
                    class="mt-4 inline-flex items-center px-4 py-2 rounded-full bg-blue-50 text-blue-700 text-lg font-medium">
                    <span class="mr-2">Shift:</span>
                    {{ $todaySchedule->shift->name }}
                    ({{ substr($todaySchedule->shift->start_time, 0, 5) }} -
                    {{ substr($todaySchedule->shift->end_time, 0, 5) }})
                </div>
            @else
                <div class="mt-4 p-4 bg-yellow-50 text-yellow-700 rounded-lg">
                    Anda tidak memiliki jadwal kerja hari ini.
                </div>
            @endif
        </div>
    </div>

    <!-- Area Tombol Aksi -->
    @if ($todaySchedule)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Status Saat Ini -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Status Kehadiran</h4>

                @if (!$attendanceToday)
                    <div
                        class="flex items-center justify-center h-32 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <span class="text-gray-500 font-medium">Belum Absen Masuk</span>
                    </div>
                @else
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                            <span class="text-green-700 font-medium">Masuk (Clock In)</span>
                            <span class="text-green-800 font-bold text-xl">
                                {{ $attendanceToday->clock_in->format('H:i') }}
                            </span>
                        </div>

                        @if ($attendanceToday->late_minutes > 0)
                            <div class="p-3 bg-red-50 rounded-lg text-center">
                                <span class="text-red-600 font-medium text-sm">
                                    Terlambat {{ $attendanceToday->late_minutes }} Menit
                                </span>
                            </div>
                        @else
                            <div class="p-3 bg-blue-50 rounded-lg text-center">
                                <span class="text-blue-600 font-medium text-sm">Tepat Waktu 👍</span>
                            </div>
                        @endif

                        @if ($attendanceToday->clock_out)
                            <div class="flex justify-between items-center p-3 bg-gray-100 rounded-lg">
                                <span class="text-gray-700 font-medium">Pulang (Clock Out)</span>
                                <span class="text-gray-900 font-bold text-xl">
                                    {{ $attendanceToday->clock_out->format('H:i') }}
                                </span>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Tombol Aksi -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 flex flex-col justify-center">
                @if (!$attendanceToday)
                    {{-- Tombol Clock In --}}
                    <button wire:click="clockIn" wire:confirm="Apakah Anda yakin ingin Clock In sekarang?"
                        class="w-full py-4 px-6 bg-green-600 hover:bg-green-700 text-white text-xl font-bold rounded-xl shadow-lg transform transition hover:scale-105 flex items-center justify-center">
                        <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                            </path>
                        </svg>
                        CLOCK IN
                    </button>
                    <p class="text-center text-sm text-gray-500 mt-3">
                        Tekan tombol di atas saat Anda mulai bekerja.
                    </p>
                @elseif(!$attendanceToday->clock_out)
                    {{-- Tombol Clock Out --}}
                    <button wire:click="clockOut" wire:confirm="Apakah Anda yakin ingin mengakhiri shift dan Clock Out?"
                        class="w-full py-4 px-6 bg-red-600 hover:bg-red-700 text-white text-xl font-bold rounded-xl shadow-lg transform transition hover:scale-105 flex items-center justify-center">
                        <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        CLOCK OUT
                    </button>
                    <p class="text-center text-sm text-gray-500 mt-3">
                        Tekan tombol ini HANYA saat shift Anda berakhir.
                    </p>
                @else
                    {{-- Sudah Selesai --}}
                    <div class="text-center py-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Shift Selesai</h3>
                        <p class="text-gray-500">Anda sudah menyelesaikan absensi hari ini.</p>
                    </div>
                @endif
            </div>

        </div>
    @endif
</div>
