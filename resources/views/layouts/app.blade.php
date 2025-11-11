<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
    {{-- <!-- Tambahkan di dalam <head> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}



    <!-- Scripts & Styles -->
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="flex h-screen bg-gray-100" x-data="{ sidebarOpen: window.innerWidth >= 1024 ? true : false }">

        <!-- Sidebar (Desktop) -->
        <aside class="flex-shrink-0 w-64 bg-white border-r transition-all duration-300"
            :class="{ 'w-64': sidebarOpen, 'w-0': !sidebarOpen }" aria-label="Sidebar">
            <div class="flex flex-col h-full" x-show="sidebarOpen">
                <!-- Sidebar Header -->
                <div class="flex items-center justify-center h-16 flex-shrink-0 px-4">
                    <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-gray-800">
                        POS CAFE
                    </a>
                </div>
                <!-- Sidebar Links -->
                <nav class="flex-1 overflow-y-auto p-4 space-y-2">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-gray-100' : '' }}">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6-4h.01M5 10l7-7 7 7">
                            </path>
                        </svg>
                        <span class="ml-3">Dashboard</span>
                    </a>

                    {{-- Link yang akan kita buat di Sprint 2 --}}
                    @role('Admin')
                        <a href="#" {{-- Nanti kita ganti ke route('users.index') --}}
                            class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100">
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            <span class="ml-3">User Management</span>
                        </a>
                    @endrole

                </nav>
            </div>
        </aside>

        <!-- Main content area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="flex items-center justify-between h-16 bg-white border-b px-4 lg:px-8">
                <!-- Tombol Buka/Tutup Sidebar (Desktop & Mobile) -->
                <button @click.stop="sidebarOpen = !sidebarOpen">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <!-- Spacer -->
                <div class="flex-1"></div>

                <!-- User Dropdown (Alpine.js) -->
                <div class="flex items-center">
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-2">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 w-48 mt-2 py-2 bg-white border rounded-md shadow-xl z-10"
                            style="display: none;">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>

                            <!-- Logout Form (Wajib pakai form) -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Log Out
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Slot -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                {{ $slot }}
            </main>
        </div>

        <!-- Mobile Sidebar Overlay (Hanya muncul di mobile) -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="lg:hidden fixed inset-0 z-20 bg-black opacity-50"
            style="display: none;"></div>
        <!-- Mobile Sidebar (Hanya muncul di mobile) -->
        <div x-show="sidebarOpen" class="lg:hidden fixed inset-y-0 left-0 z-30 w-64 bg-white border-r overflow-y-auto"
            style="display: none;">
            <div class="flex flex-col h-full">
                <div class="flex items-center justify-center h-16 flex-shrink-0 px-4">
                    <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-gray-800">
                        POS CAFE
                    </a>
                </div>
                <nav class="flex-1 overflow-y-auto p-4 space-y-2">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-gray-100' : '' }}">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6-4h.01M5 10l7-7 7 7">
                            </path>
                        </svg>
                        <span class="ml-3">Dashboard</span>
                    </a>
                    @role('Admin')
                        <a href="{{ route('users.index') }}" {{-- <-- 1. UBAH href --}}
                            class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 {{ request()->routeIs('users.index') ? 'bg-gray-100' : '' }}">
                            {{-- 2. TAMBAHKAN LOGIKA 'active' --}}
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            <span class="ml-3">User Management</span>
                        </a>
                    @endrole
                </nav>
            </div>
        </div>
    </div>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Livewire.on('user-deleted', (data) => {
            console.log('🔥 Event diterima:', data);
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                showConfirmButton: false,
                timer: 1800
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
