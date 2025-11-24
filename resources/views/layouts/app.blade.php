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

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased">
    {{-- 
        File layout ini mengontrol 'bingkai' aplikasi.
        Sidebar Desktop & Mobile ada di dalam file ini.
    --}}
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
                    <a href="{{ route('attendance.index') }}"
                        class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.index') ? 'bg-gray-100' : '' }}">
                        <!-- Icon Jam/Absen -->
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="ml-3">Absensi Saya</span>
                    </a>
                    <!-- Link POS (Untuk Semua Role) -->
                    <a href="{{ route('pos.index') }}"
                        class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 {{ request()->routeIs('pos.index') ? 'bg-gray-100' : '' }}">
                        <!-- Icon Mesin Kasir/Kalkulator -->
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span class="ml-3">Point of Sale</span>
                    </a>
                    {{-- Link User Management --}}
                    @role('Admin')
                        <a href="{{ route('users.index') }}"
                            class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 {{ request()->routeIs('users.index') ? 'bg-gray-100' : '' }}">
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            <span class="ml-3">User Management</span>
                        </a>
                    @endrole

                    <!-- Link untuk Kategori -->
                    @role('Admin')
                        <a href="{{ route('categories.index') }}"
                            class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 {{ request()->routeIs('categories.index') ? 'bg-gray-100' : '' }}">
                            <!-- Icon baru untuk Kategori (Tag) -->
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A2 2 0 013 8V5a2 2 0 012-2h2z">
                                </path>
                            </svg>
                            <span class="ml-3">Kategori</span>
                        </a>
                    @endrole

                    <!-- Link untuk Menu -->
                    @role('Admin')
                        <a href="{{ route('menus.index') }}"
                            class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 {{ request()->routeIs('menus.index') ? 'bg-gray-100' : '' }}">
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                            <span class="ml-3">Menu Management</span>
                        </a>
                    @endrole

                    <!-- Link untuk Supplier -->
                    @role('Admin')
                        <a href="{{ route('suppliers.index') }}"
                            class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 {{ request()->routeIs('suppliers.index') ? 'bg-gray-100' : '' }}">
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                            </svg>
                            <span class="ml-3">Supplier</span>
                        </a>
                    @endrole
                    @role('Admin')
                        {{-- Nanti kita ubah jadi @role(['Admin', 'Manajer']) --}}
                        <a href="{{ route('shifts.index') }}"
                            class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 {{ request()->routeIs('shifts.index') ? 'bg-gray-100' : '' }}">
                            <!-- Icon Jam -->
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="ml-3">Shift Management</span>
                        </a>
                    @endrole
                    @role('Admin')
                        <a href="{{ route('schedules.index') }}"
                            class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 {{ request()->routeIs('schedules.index') ? 'bg-gray-100' : '' }}">
                            <!-- Icon Kalender -->
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span class="ml-3">Manajemen Jadwal</span>
                        </a>
                    @endrole
                    @role('Admin')
                        <a href="{{ route('reports.index') }}"
                            class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.index') ? 'bg-gray-100' : '' }}">
                            <!-- Icon Chart/Grafik -->
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                            <span class="ml-3">Laporan Penjualan</span>
                        </a>
                    @endrole
                    @role('Admin')
                        <a href="{{ route('settings.index') }}"
                            class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 {{ request()->routeIs('settings.index') ? 'bg-gray-100' : '' }}">
                            <!-- Icon Gear/Setting -->
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="ml-3">Pengaturan Sistem</span>
                        </a>
                    @endrole
                    @role(['Admin', 'Manajer'])
                        <a href="{{ route('attendance.validation') }}"
                            class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.validation') ? 'bg-gray-100' : '' }}">
                            <!-- Icon Checklist/Validasi -->
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                </path>
                            </svg>
                            <span class="ml-3">Validasi Absensi</span>
                        </a>
                    @endrole
                    @role('Admin')
                        <a href="{{ route('payroll.index') }}"
                            class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 {{ request()->routeIs('payroll.index') ? 'bg-gray-100' : '' }}">
                            <!-- Icon Uang/Dollar -->
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            <span class="ml-3">Penggajian</span>
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
                <div class="flex-1"></div>
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
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            class="lg:hidden fixed inset-0 z-20 bg-black opacity-50" style="display: none;"></div>
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
                        <a href="{{ route('users.index') }}"
                            class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 {{ request()->routeIs('users.index') ? 'bg-gray-100' : '' }}">
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            <span class="ml-3">User Management</span>
                        </a>
                    @endrole

                    @role('Admin')
                        <a href="{{ route('categories.index') }}"
                            class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 {{ request()->routeIs('categories.index') ? 'bg-gray-100' : '' }}">
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A2 2 0 013 8V5a2 2 0 012-2h2z">
                                </path>
                            </svg>
                            <span class="ml-3">Kategori</span>
                        </a>
                    @endrole

                    @role('Admin')
                        <a href="{{ route('menus.index') }}"
                            class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 {{ request()->routeIs('menus.index') ? 'bg-gray-100' : '' }}">
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                            <span class="ml-3">Menu Management</span>
                        </a>
                    @endrole

                    @role('Admin')
                        <a href="{{ route('suppliers.index') }}"
                            class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 {{ request()->routeIs('suppliers.index') ? 'bg-gray-100' : '' }}">
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                            </svg>
                            <span class="ml-3">Supplier</span>
                        </a>
                    @endrole
                    @role('Admin')
                        {{-- Nanti kita ubah jadi @role(['Admin', 'Manajer']) --}}
                        <a href="{{ route('shifts.index') }}"
                            class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 {{ request()->routeIs('shifts.index') ? 'bg-gray-100' : '' }}">
                            <!-- Icon Jam -->
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="ml-3">Shift Management</span>
                        </a>
                    @endrole
                    @role('Admin')
                        <a href="{{ route('schedules.index') }}"
                            class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 {{ request()->routeIs('schedules.index') ? 'bg-gray-100' : '' }}">
                            <!-- Icon Kalender -->
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span class="ml-3">Manajemen Jadwal</span>
                        </a>
                    @endrole
                </nav>
            </div>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>

</html>
