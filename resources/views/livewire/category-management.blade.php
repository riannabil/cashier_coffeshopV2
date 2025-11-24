<div>
    <!-- Header Halaman -->
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-semibold text-gray-900">Manajemen Kategori</h2>

        <!-- Tombol "Tambah Kategori" -->
        <button type="button" wire:click="openModal"
            class="inline-flex items-center rounded-lg border border-transparent bg-gray-800 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
            + Tambah Kategori
        </button>
    </div>

    <!-- Kotak Konten (Wrapper Tabel & Search) -->
    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
        <div class="p-6 space-y-4">

            <!-- Area Search & Filter -->
            <div class="flex justify-between">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kategori..."
                    class="block w-64 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">

                <select wire:model.live="perPage"
                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="5">5 per Halaman</option>
                    <option value="10">10 per Halaman</option>
                    <option value="20">20 per Halaman</option>
                </select>
            </div>

            <!-- Tabel -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Nama Kategori
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">

                        {{-- Gunakan @forelse untuk menangani jika data kosong --}}
                        @forelse($categories as $category)
                            <tr>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                    <button wire:click="edit({{ $category->id }})"
                                        class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                    <button wire:click="delete({{ $category->id }})"
                                        wire:confirm="Anda yakin ingin menghapus kategori '{{ $category->name }}'?"
                                        class="ml-4 text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Tidak ada data kategori ditemukan.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $categories->links() }}
            </div>

        </div>
    </div>

    <!-- =================================================================== -->
    <!-- ==================   MODAL "ADD/EDIT KATEGORI"   ================== -->
    <!-- =================================================================== -->

    {{-- Modal ini akan tampil/sembunyi berdasarkan properti $showModal di PHP --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-gray-500 bg-opacity-75"
            x-transition>

            <!-- Modal Panel -->
            <div class="mx-auto w-full max-w-lg overflow-hidden rounded-lg bg-white shadow-xl"
                @click.away="$wire.closeModal()"> {{-- Tutup modal jika klik di luar --}}

                <!-- Form -->
                <form wire:submit.prevent="save">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $isEditing ? 'Edit Kategori' : 'Tambah Kategori Baru' }}
                        </h3>
                        <button type="button" wire:click="closeModal" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body (Form Inputs) -->
                    <div class="space-y-6 bg-white p-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                            <input type="text" id="name" wire:model.defer="name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Misal: Kopi, Non-Kopi, Makanan">
                            @error('name')
                                <span class="mt-2 text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Modal Footer (Tombol Aksi) -->
                    <div class="flex justify-end space-x-4 bg-gray-50 px-6 py-4">
                        <button type="button" wire:click="closeModal"
                            class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Batal
                        </button>

                        <button type="submit"
                            class="inline-flex justify-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">

                            {{-- Tampilkan spinner saat loading --}}
                            <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>

                            {{ $isEditing ? 'Simpan Perubahan' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
