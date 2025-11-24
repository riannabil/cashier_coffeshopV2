<div>
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-semibold text-gray-900">Jadwal Kerja</h2>
        <button type="button" wire:click="openModal"
            class="inline-flex items-center rounded-lg border border-transparent bg-gray-800 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-gray-900">
            + Buat Jadwal
        </button>
    </div>

    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
        <div class="p-6 space-y-4">
            <div class="flex justify-between">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama karyawan..."
                    class="block w-64 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Karyawan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Shift</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Jam</th>
                            <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($schedules as $schedule)
                            <tr>
                                <td class="whitespace-nowrap px-6 py-4 text-gray-900">
                                    {{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('d F Y') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-gray-900">
                                    {{ $schedule->user->name ?? 'User Terhapus' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-gray-500">
                                    <span
                                        class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                        {{ $schedule->shift->name ?? 'Shift Terhapus' }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-gray-500">
                                    @if ($schedule->shift)
                                        {{ substr($schedule->shift->start_time, 0, 5) }} -
                                        {{ substr($schedule->shift->end_time, 0, 5) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                    <button wire:click="edit({{ $schedule->id }})"
                                        class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                    <button wire:click="delete({{ $schedule->id }})" wire:confirm="Hapus jadwal ini?"
                                        class="ml-4 text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada jadwal.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $schedules->links() }}</div>
        </div>
    </div>

    {{-- MODAL --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-gray-500 bg-opacity-75"
            x-transition>
            <div class="mx-auto w-full max-w-lg overflow-hidden rounded-lg bg-white shadow-xl"
                @click.away="$wire.closeModal()">
                <form wire:submit.prevent="save">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex justify-between">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $isEditing ? 'Edit Jadwal' : 'Buat Jadwal Baru' }}</h3>
                        <button type="button" wire:click="closeModal"
                            class="text-gray-400 hover:text-gray-500">X</button>
                    </div>
                    <div class="space-y-6 bg-white p-6">

                        <!-- Input Tanggal -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <input type="date" wire:model.defer="date"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('date')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Input Karyawan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Karyawan</label>
                            <select wire:model.defer="user_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Pilih Karyawan...</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Input Shift -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Shift</label>
                            <select wire:model.defer="shift_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Pilih Shift...</option>
                                @foreach ($shifts as $shift)
                                    <option value="{{ $shift->id }}">
                                        {{ $shift->name }} ({{ substr($shift->start_time, 0, 5) }} -
                                        {{ substr($shift->end_time, 0, 5) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('shift_id')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <div class="flex justify-end space-x-4 bg-gray-50 px-6 py-4">
                        <button type="button" wire:click="closeModal"
                            class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">Batal</button>
                        <button type="submit"
                            class="rounded-md bg-gray-800 px-4 py-2 text-sm font-medium text-white hover:bg-gray-900">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
