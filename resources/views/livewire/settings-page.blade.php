<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-900">Pengaturan Sistem</h2>
        <p class="text-gray-600">Atur kebijakan potongan gaji dan toleransi absensi.</p>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
        <form wire:submit.prevent="save" class="p-6 space-y-6">

            <!-- Potongan Keterlambatan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center border-b border-gray-100 pb-6">
                <div class="md:col-span-1">
                    <h3 class="text-lg font-medium text-gray-900">Keterlambatan</h3>
                    <p class="text-sm text-gray-500">Potongan gaji setiap kali karyawan datang terlambat.</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nominal Potongan (Rp)</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" wire:model.defer="late_deduction"
                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 pr-12 sm:text-sm border-gray-300 rounded-md"
                            placeholder="0">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">/ kejadian</span>
                        </div>
                    </div>
                    @error('late_deduction')
                        <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Toleransi Waktu -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center border-b border-gray-100 pb-6">
                <div class="md:col-span-1">
                    <h3 class="text-lg font-medium text-gray-900">Toleransi Waktu</h3>
                    <p class="text-sm text-gray-500">Batas waktu (menit) sebelum karyawan dianggap terlambat.</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Durasi Toleransi</label>
                    <div class="relative rounded-md shadow-sm">
                        <input type="number" wire:model.defer="late_tolerance_minutes"
                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md"
                            placeholder="0">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Menit</span>
                        </div>
                    </div>
                    @error('late_tolerance_minutes')
                        <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Potongan Alpha -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center pb-6">
                <div class="md:col-span-1">
                    <h3 class="text-lg font-medium text-gray-900">Tidak Hadir (Alpha)</h3>
                    <p class="text-sm text-gray-500">Potongan gaji jika karyawan tidak hadir tanpa keterangan.</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nominal Potongan (Rp)</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" wire:model.defer="alpha_deduction"
                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 pr-12 sm:text-sm border-gray-300 rounded-md"
                            placeholder="0">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">/ hari</span>
                        </div>
                    </div>
                    @error('alpha_deduction')
                        <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Tombol Simpan -->
            <div class="flex justify-end pt-4 border-t border-gray-200">
                <button type="submit"
                    class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">

                    <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>

                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
