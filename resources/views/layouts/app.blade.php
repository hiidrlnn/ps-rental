<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Rental PS Online')</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Custom scrollbar for dark theme if needed */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #2D3748; /* Dark gray track */
        }
        ::-webkit-scrollbar-thumb {
            background: #4A5568; /* Slightly lighter gray thumb */
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #6B7280; /* Even lighter gray on hover */
        }
        body {
            font-family: 'Poppins', sans-serif; /* Pastikan font Poppins diterapkan di seluruh aplikasi */
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-900 font-sans leading-normal tracking-normal text-gray-100">

    {{-- INI ADALAH WRAPPER UTAMA FLEXBOX UNTUK SELURUH LAYOUT (SIDEBAR + MAIN CONTENT) --}}
    <div class="flex h-screen overflow-hidden">
        <aside class="w-64 bg-gray-800 text-white p-4 space-y-6 flex flex-col shadow-lg">
            <div class="flex items-center space-x-3 mb-6">
                {{-- Logo di Sidebar --}}
                <img src="{{ asset('images/logo-rentalps.png') }}" alt="Logo" class="h-8 w-8">
                <span class="text-xl font-bold">Rental PS</span>
            </div>
            <nav class="flex-1">
                <ul>
                    @auth
                        @if(Auth::user()->role === 'petugas')
                            <li><a href="{{ route('petugas.dashboard') }}" class="flex items-center space-x-3 py-2 px-4 rounded-lg hover:bg-gray-700 transition duration-200 {{ request()->routeIs('petugas.dashboard') ? 'bg-blue-600' : '' }}"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 001 1h3m-6-16v4h4V4m-4 0h-4"/></svg><span>Home</span></a></li>
                            <li><a href="{{ route('petugas.rental.form') }}" class="flex items-center space-x-3 py-2 px-4 rounded-lg hover:bg-gray-700 transition duration-200 {{ request()->routeIs('petugas.rental.*') ? 'bg-blue-600' : '' }}"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg><span>Penyewaan</span></a></li>
                            <li><a href="{{ route('petugas.return.list') }}" class="flex items-center space-x-3 py-2 px-4 rounded-lg hover:bg-gray-700 transition duration-200 {{ request()->routeIs('petugas.return.*') ? 'bg-blue-600' : '' }}"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><span>Pengembalian</span></a></li>
                        @elseif(Auth::user()->role === 'owner')
                            <li><a href="{{ route('owner.dashboard') }}" class="flex items-center space-x-3 py-2 px-4 rounded-lg hover:bg-gray-700 transition duration-200 {{ request()->routeIs('owner.dashboard') ? 'bg-blue-600' : '' }}"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9 0 0120.488 9z"></path></svg><span>Dashboard Statistik</span></a></li>
                            <li><a href="{{ route('owner.units.index') }}" class="flex items-center space-x-3 py-2 px-4 rounded-lg hover:bg-gray-700 transition duration-200 {{ request()->routeIs('owner.units.*') ? 'bg-blue-600' : '' }}"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10m0 0h10m-10 0L9 14m4-4L9 7m4 4h-4m4 0l-4-4m4 4v4m4-4h4"></path></svg><span>Stock Unit PS</span></a></li>
                            <li><a href="{{ route('owner.reports.index') }}" class="flex items-center space-x-3 py-2 px-4 rounded-lg hover:bg-gray-700 transition duration-200 {{ request()->routeIs('owner.reports.*') ? 'bg-blue-600' : '' }}"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-5m3 5v-5m3 5v-5m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg><span>Laporan Penyewaan</span></a></li>
                        @endif
                    @endauth
                </ul>
            </nav>
        </aside>

        {{-- INI ADALAH WRAPPER UNTUK HEADER DAN KONTEN UTAMA --}}
        <div class="flex-1 flex flex-col bg-gray-900 overflow-y-auto">
            <header class="flex justify-between items-center bg-gray-800 p-4 shadow-xl z-10 sticky top-0">
                <h1 class="text-2xl font-semibold text-gray-100">@yield('page_title', 'Dashboard')</h1>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 text-gray-300 hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full p-2 transition duration-200">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Guest') }}&background=0D8ABC&color=fff&size=30" class="h-8 w-8 rounded-full" alt="User Avatar">
                        <span class="font-medium hidden md:block">{{ Auth::user()->name ?? 'Guest' }}</span>
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-3 w-48 bg-gray-700 border border-gray-600 rounded-md shadow-lg py-1 z-20">
                        <a href="{{ route('profile.show') }}" class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-200 hover:bg-gray-600 transition duration-200">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span>Profile</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center space-x-2 w-full text-left px-4 py-2 text-sm text-gray-200 hover:bg-gray-600 transition duration-200">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6">
                @if (session('success'))
                    <div class="bg-green-700 border border-green-600 text-green-100 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Sukses!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <svg class="fill-current h-6 w-6 text-green-200" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 01-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 11-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 111.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 111.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 010 1.698z"/></svg>
                        </span>
                    </div>
                @endif

               

                @yield('content') {{-- <--- KONTEN DARI FILE LAIN (misal owner/dashboard.blade.php) MASUK DI SINI --}}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>