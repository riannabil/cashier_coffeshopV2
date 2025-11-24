<div>
    <!-- Header Halaman -->
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-semibold text-gray-900">User Management</h2>

        <!-- Tombol "Add User" - SEKARANG SUDAH BERFUNGSI -->
        <button type="button" wire:click="openModal"
            class="inline-flex items-center rounded-lg border border-transparent bg-gray-800 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
            + Add User
        </button>
    </div>

    <!-- Kotak Konten (Wrapper Tabel) -->
    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
        <div class="p-6">

            <!-- Tabel (Ini masih sama seperti sebelumnya) -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Name
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Role
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Gaji Pokok
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">

                        {{-- Loop data $users dari PHP --}}
                        @if (isset($users))
                            @foreach ($users as $user)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="ml-4 text-sm text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span
                                            class="inline-flex rounded-full bg-blue-100 px-2 text-xs font-semibold leading-5{{ $user->roles->first()->name === 'Admin' ? 'bg-red-100 text-red-800' : '' }}
    {{ $user->roles->first()->name === 'Manajer' ? 'bg-blue-100 text-blue-800' : '' }}
    {{ $user->roles->first()->name === 'Karyawan' ? 'bg-green-100 text-green-800' : '' }}">
                                            {{ $user->roles->first()->name ?? 'Tanpa Role' }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm text-gray-900">Rp
                                            {{ number_format($user->base_salary, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                        <button wire:click="editUser({{ $user->id }})"
                                            class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                        <button wire:click="confirmDelete({{ $user->id }})"
                                            class="ml-4 text-red-600 hover:text-red-900">Delete</button>
                                    </td>

                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Tidak ada data user.
                                </td>
                            </tr>
                        @endif

                    </tbody>


                </table>
                <div class="mt-4">
                    {{ $users->links() }}
                </div>

            </div>

        </div>
    </div>

    <!-- Modal Add User (Pines Style + Livewire Reactive) -->
    <div x-data="{ modalOpen: @entangle('showModal') }" @keydown.escape.window="modalOpen = false" :class="{ 'z-40': modalOpen }"
        class="relative w-auto h-auto">

        <template x-teleport="body">
            <div x-show="modalOpen" x-cloak
                class="fixed top-0 left-0 z-[99] flex items-center justify-center w-screen h-screen">
                <!-- Overlay -->
                <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-300"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    @click="modalOpen = false; $wire.closeModal()"
                    class="absolute inset-0 w-full h-full backdrop-blur-sm bg-gray-900/50">
                </div>

                <!-- Modal Content -->
                <div x-show="modalOpen" x-trap.inert.noscroll="modalOpen" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-90"
                    class="relative px-7 py-6 w-full shadow-lg backdrop-blur-sm bg-white/90 sm:max-w-lg sm:rounded-lg">

                    <!-- Header -->
                    <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                        <h3 class="text-lg font-semibold">Add New User</h3>
                        <button @click="modalOpen = false; $wire.closeModal()"
                            class="flex justify-center items-center w-8 h-8 text-gray-600 rounded-full hover:text-gray-800 hover:bg-gray-50">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <form wire:submit.prevent="{{ $userId ? 'updateUser' : 'saveUser' }}">
                        <!-- Nama -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" id="name" wire:model.defer="name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Masukkan nama lengkap">
                            @error('name')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="email" wire:model.defer="email"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="example@domain.com">
                            @error('email')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" id="password" wire:model.defer="password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Minimal 8 karakter">
                            @error('password')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Gaji Pokok -->
                        <div>
                            <label for="base_salary" class="block text-sm font-medium text-gray-700">Gaji
                                Pokok</label>
                            <input type="number" id="base_salary" wire:model.defer="base_salary"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Contoh: 5000000">
                            @error('base_salary')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div>
                            <label for="role_id" class="block text-sm font-medium text-gray-700">Role</label>
                            <select id="role_id" wire:model.defer="role_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Pilih Role...</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tombol -->
                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                            <button type="button" @click="modalOpen = false; $wire.closeModal()"
                                class="px-4 py-2 text-sm rounded-md border border-gray-300 bg-white hover:bg-gray-50">
                                Cancel
                            </button>

                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white rounded-md bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                <svg wire:loading wire:target="saveUser"
                                    class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                </svg>

                                {{ $userId ? 'Update' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
    @if ($confirmingDelete)
        <div class="fixed inset-0 flex items-center justify-center bg-gray-900/50 z-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-sm">
                <h2 class="text-lg font-semibold mb-4">Yakin ingin menghapus user ini?</h2>
                <div class="flex justify-end space-x-3">
                    <button wire:click="$set('confirmingDelete', false)"
                        class="px-4 py-2 bg-gray-200 rounded-md">Batal</button>
                    <button wire:click="deleteUser" class="px-4 py-2 bg-red-600 text-white rounded-md"><a
                            href="#" wire:click.prevent="confirmDelete({{ $user->id }})"
                            class="ml-4 text-red-600 hover:text-red-900">

                        </a>Hapus</button>
                </div>
            </div>
        </div>
    @endif
</div>
