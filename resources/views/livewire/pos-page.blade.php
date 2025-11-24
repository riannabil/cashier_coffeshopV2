<div class="flex h-[calc(100vh-64px)] -m-6"> <!-- Full height minus header -->

    <!-- KOLOM KIRI: DAFTAR MENU -->
    <div class="w-2/3 flex flex-col bg-gray-100 border-r border-gray-200 p-6 overflow-hidden">

        <!-- Header & Filter -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Menu</h2>
                <p class="text-sm text-gray-500">Pilih kategori untuk memfilter</p>
            </div>

            <!-- Search -->
            <div class="w-64">
                <input type="text" wire:model.live.debounce.300ms="search"
                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Cari menu...">
            </div>
        </div>

        <!-- Kategori Tabs -->
        <div class="flex space-x-2 overflow-x-auto pb-4 mb-2">
            <button wire:click="$set('selectedCategory', 'all')"
                class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $selectedCategory == 'all' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-200' }}">
                Semua
            </button>
            @foreach ($categories as $category)
                <button wire:click="$set('selectedCategory', {{ $category->id }})"
                    class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $selectedCategory == $category->id ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-200' }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>

        <!-- Grid Menu (Scrollable) -->
        <div class="flex-1 overflow-y-auto pr-2">
            <div class="grid grid-cols-3 gap-4">
                @foreach ($menus as $menu)
                    <div wire:click="addToCart({{ $menu->id }})"
                        class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow cursor-pointer overflow-hidden flex flex-col h-full relative group">

                        <!-- Stok Badge -->
                        <div
                            class="absolute top-2 right-2 bg-gray-900/70 text-white text-xs px-2 py-1 rounded-md backdrop-blur-sm">
                            Stok: {{ $menu->stock }}
                        </div>

                        <!-- Placeholder Gambar (Bisa diganti img tag jika ada foto) -->
                        <div class="h-32 bg-gray-200 flex items-center justify-center text-gray-400 text-4xl font-bold">
                            {{ substr($menu->name, 0, 1) }}
                        </div>

                        <div class="p-4 flex flex-col flex-1">
                            <h3 class="font-semibold text-gray-800 mb-1 leading-tight">{{ $menu->name }}</h3>
                            <p class="text-blue-600 font-bold mt-auto">Rp {{ number_format($menu->price, 0, ',', '.') }}
                            </p>
                        </div>

                        <!-- Overlay saat di-hover -->
                        <div
                            class="absolute inset-0 bg-blue-600/0 group-hover:bg-blue-600/10 transition-colors flex items-center justify-center">
                            <span
                                class="opacity-0 group-hover:opacity-100 bg-blue-600 text-white px-4 py-2 rounded-full font-medium shadow-lg transform translate-y-2 group-hover:translate-y-0 transition-all">
                                + Tambah
                            </span>
                        </div>
                    </div>
                @endforeach

                @if ($menus->isEmpty())
                    <div class="col-span-3 text-center py-10 text-gray-500">
                        Tidak ada menu ditemukan.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- KOLOM KANAN: CART (KERANJANG) -->
    <div class="w-1/3 bg-white flex flex-col h-full shadow-xl z-10">
        <div class="p-6 border-b border-gray-100 bg-gray-50">
            <h2 class="text-xl font-bold text-gray-800">Keranjang Pesanan</h2>
            <p class="text-sm text-gray-500">Order #{{ rand(1000, 9999) }}</p>
        </div>

        <!-- List Item Cart (Scrollable) -->
        <div class="flex-1 overflow-y-auto p-6 space-y-4">
            @forelse($cart as $key => $item)
                <div class="flex items-start space-x-4">
                    <!-- Qty Control -->
                    <div class="flex flex-col items-center border border-gray-200 rounded-lg">
                        <button wire:click="increaseQty({{ $key }})"
                            class="p-1 text-gray-500 hover:text-blue-600 hover:bg-gray-100 rounded-t-lg">+</button>
                        <span
                            class="text-sm font-bold py-1 w-8 text-center border-y border-gray-100">{{ $item['qty'] }}</span>
                        <button wire:click="decreaseQty({{ $key }})"
                            class="p-1 text-gray-500 hover:text-red-600 hover:bg-gray-100 rounded-b-lg">-</button>
                    </div>

                    <div class="flex-1">
                        <div class="flex justify-between mb-1">
                            <h4 class="font-semibold text-gray-800">{{ $item['name'] }}</h4>
                            <span class="font-medium text-gray-900">
                                {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                            </span>
                        </div>

                        <!-- Input Note (Modifier) -->
                        <input type="text" wire:model.defer="cart.{{ $key }}.note"
                            placeholder="Catatan (mis: less sugar)..."
                            class="w-full text-xs border-none bg-gray-100 rounded px-2 py-1 focus:ring-0 text-gray-600 placeholder-gray-400">
                    </div>
                </div>
            @empty
                <div class="h-full flex flex-col items-center justify-center text-gray-400 space-y-4 opacity-50">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <p>Keranjang masih kosong</p>
                </div>
            @endforelse
        </div>

        <!-- Footer: Total & Button -->
        <div class="p-6 bg-gray-50 border-t border-gray-200">
            @if (session()->has('message'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 text-sm rounded-lg">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-4 p-3 bg-red-100 text-red-700 text-sm rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex justify-between items-end mb-4">
                <span class="text-gray-500">Total Tagihan</span>
                <span class="text-3xl font-bold text-gray-900">
                    Rp {{ number_format($totalAmount, 0, ',', '.') }}
                </span>
            </div>

            <button wire:click="checkout" wire:confirm="Konfirmasi pembayaran ini?"
                {{ empty($cart) ? 'disabled' : '' }}
                class="w-full py-4 bg-gray-900 hover:bg-black text-white text-lg font-bold rounded-xl shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center group">
                <span class="mr-2">Konfirmasi Pembayaran</span>
                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
            </button>
        </div>
    </div>
</div>
