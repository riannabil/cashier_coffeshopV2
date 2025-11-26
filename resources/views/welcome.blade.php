<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'POS Cafe') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full font-sans antialiased text-gray-600">

    <div class="flex min-h-full">

        <!-- Bagian Kiri: Gambar Artistik (Hanya tampil di layar besar/desktop) -->
        <div class="relative hidden w-0 flex-1 lg:block">
            <!-- Gambar Background Kafe -->
            <img class="absolute inset-0 h-full w-full object-cover"
                src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1920&q=80"
                alt="Cafe Atmosphere">

            <!-- Overlay Gelap agar tulisan terbaca -->
            <div class="absolute inset-0 bg-gray-900/60 mix-blend-multiply"></div>

            <!-- Konten di atas gambar -->
            <div class="absolute inset-0 flex flex-col justify-center px-12 text-white">
                <div class="mb-6">
                    <svg class="h-12 w-12 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h2 class="text-4xl font-bold tracking-tight sm:text-5xl">
                    Start your shift with <br> good energy.
                </h2>
                <p class="mt-6 max-w-lg text-lg text-gray-300">
                    Sistem manajemen kafe terintegrasi untuk efisiensi pelayanan dan pengelolaan stok yang lebih baik.
                </p>
                <blockquote class="mt-8 border-l-4 border-yellow-400 pl-4 italic text-gray-300">
                    "Coffee is a language in itself."
                </blockquote>
            </div>
        </div>

        <!-- Bagian Kanan: Form Login -->
        <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24 bg-white">
            <div class="mx-auto w-full max-w-sm lg:w-96">

                <!-- Logo Mobile / Header Form (DIPERBAIKI: Dibuat rata tengah selalu) -->
                <div class="text-center">
                    <div class="flex justify-center">
                        <div
                            class="h-12 w-12 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg lg:hidden">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <h2 class="mt-6 text-3xl font-bold tracking-tight text-gray-900">
                        POS CAFE SYSTEM
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Silakan login untuk mengakses dashboard
                    </p>
                </div>

                <div class="mt-8">
                    <div class="mt-6">

                        <!-- Logika Tombol Login -->
                        <div class="space-y-6">
                            @if (Route::has('login'))
                                @auth
                                    <!-- Jika user SUDAH login -->
                                    <div class="rounded-md bg-blue-50 p-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3 flex-1 md:flex md:justify-between">
                                                <p class="text-sm text-blue-700">
                                                    Anda sudah login sebagai <span
                                                        class="font-bold">{{ Auth::user()->name }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <a href="{{ url('/dashboard') }}"
                                        class="flex w-full justify-center rounded-md border border-transparent bg-indigo-600 py-3 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                                        Lanjutkan ke Dashboard &rarr;
                                    </a>
                                @else
                                    <!-- Jika BELUM login -->
                                    <div class="bg-gray-50 px-4 py-5 sm:rounded-lg sm:px-6 border border-gray-200">
                                        <div class="text-center mb-4">
                                            <h3 class="text-lg font-medium leading-6 text-gray-900">Portal Karyawan</h3>
                                            <p class="mt-1 text-sm text-gray-500">
                                                Masuk untuk memulai shift Anda.
                                            </p>
                                        </div>

                                        <a href="{{ route('login') }}"
                                            class="flex w-full justify-center rounded-md border border-transparent bg-gray-900 py-3 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 transition-all">
                                            Login Akun
                                        </a>
                                    </div>

                                    <div class="relative">
                                        <div class="absolute inset-0 flex items-center">
                                            <div class="w-full border-t border-gray-300"></div>
                                        </div>
                                        <div class="relative flex justify-center text-sm">
                                            <span class="bg-white px-2 text-gray-500">Butuh bantuan?</span>
                                        </div>
                                    </div>

                                    <div class="text-center text-sm">
                                        <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">
                                            Hubungi Administrator Sistem
                                        </a> untuk reset password atau pembuatan akun baru.
                                    </div>
                                @endauth
                            @endif
                        </div>

                    </div>
                </div>

                <!-- Footer Kecil -->
                <div class="mt-10 border-t border-gray-200 pt-6">
                    <p class="text-center text-xs text-gray-400">
                        &copy; {{ date('Y') }} POS Cafe System v1.0. All rights reserved.
                    </p>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
