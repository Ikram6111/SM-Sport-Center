<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SM Sport Center' }} - Sistem Reservasi Lapangan</title>
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        },
                        success: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans min-h-screen flex flex-col antialiased">

    <!-- Navigation Bar -->
    <header class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo SM Sport Center" class="h-10 w-auto object-contain">
                        <div>
                            <span class="text-lg font-bold tracking-tight text-gray-900">SM Sport Center</span>
                            <span class="block text-[11px] text-blue-600 font-semibold tracking-wider uppercase">Sistem Reservasi</span>
                        </div>
                    </a>
                </div>

                @auth
                <!-- Desktop Nav Links -->
                <nav class="hidden md:flex items-center space-x-1 lg:space-x-2">
                    <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 font-semibold border border-blue-200' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        Dasbor
                    </a>
                    <a href="{{ route('ketersediaan.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('ketersediaan.*') ? 'bg-blue-50 text-blue-600 font-semibold border border-blue-200' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        Cek Jadwal
                    </a>
                    <a href="{{ route('lapangan.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('lapangan.*') ? 'bg-blue-50 text-blue-600 font-semibold border border-blue-200' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        Lapangan
                    </a>
                    <a href="{{ route('reservasi.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('reservasi.*') ? 'bg-blue-50 text-blue-600 font-semibold border border-blue-200' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        {{ auth()->user()->isAdmin() ? 'Semua Reservasi' : 'Reservasi Saya' }}
                    </a>

                    @if(auth()->user()->isAdmin())
                    <!-- Admin Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.*') ? 'bg-amber-50 text-amber-700 font-semibold border border-amber-200' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            Admin Panel
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 ml-1.5 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="absolute right-0 mt-2 w-56 rounded-md bg-white py-2 shadow-md border border-gray-200 z-50" style="display: none;">
                            <div class="px-4 py-2 bg-gray-50 border-b border-gray-200">
                                <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Manajemen</span>
                            </div>
                            <a href="{{ route('admin.lapangan.create') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                Tambah Lapangan
                            </a>
                            <a href="{{ route('admin.pelanggan.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                Data Pelanggan
                            </a>
                            <a href="{{ route('admin.laporan.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                Laporan Penggunaan
                            </a>
                        </div>
                    </div>
                    @endif
                </nav>

                <!-- User Profile & Action -->
                <div class="hidden md:flex items-center space-x-3">
                    @if(!auth()->user()->isAdmin())
                    <a href="{{ route('reservasi.create') }}" class="px-4 py-2 rounded-md bg-green-600 hover:bg-green-700 text-white font-medium text-sm shadow-sm transition flex items-center">
                        <i data-lucide="plus" class="w-4 h-4 mr-1.5"></i> Booking Sekarang
                    </a>
                    @endif

                    <div class="relative" x-data="{ userMenu: false }" @click.away="userMenu = false">
                        <button @click="userMenu = !userMenu" class="flex items-center space-x-2 p-1 rounded-md hover:bg-gray-100 transition-colors border border-transparent hover:border-gray-200">
                            <div class="w-8 h-8 rounded-full {{ auth()->user()->isAdmin() ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }} flex items-center justify-center font-bold text-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="text-left hidden lg:block pr-1">
                                <div class="text-sm font-semibold text-gray-800 leading-tight">{{ auth()->user()->name }}</div>
                                <div class="text-[11px] text-gray-500 font-medium uppercase tracking-wider">{{ auth()->user()->role }}</div>
                            </div>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                        </button>

                        <div x-show="userMenu" x-transition class="absolute right-0 mt-2 w-56 rounded-md bg-white py-2 shadow-md border border-gray-200 z-50" style="display: none;">
                            <div class="px-4 py-2.5 bg-gray-50 border-b border-gray-200">
                                <p class="text-xs text-gray-500">Login sebagai</p>
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <i data-lucide="log-out" class="w-4 h-4 mr-2.5"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <!-- Guest Nav Links -->
                <div class="flex items-center space-x-2">
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 font-medium text-sm transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm shadow-sm transition-colors">
                        Daftar Sekarang
                    </a>
                </div>
                @endauth

                <!-- Mobile Menu Button -->
                <div class="flex md:hidden" x-data="{ mobileMenu: false }">
                    <button @click="mobileMenu = !mobileMenu" class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                    <!-- Mobile Dropdown -->
                    <div x-show="mobileMenu" @click.away="mobileMenu = false" class="absolute top-16 left-0 right-0 bg-white border-b border-gray-200 p-4 space-y-2 shadow-md z-50" style="display: none;">
                        @auth
                        <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100">Dasbor</a>
                        <a href="{{ route('ketersediaan.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100">Cek Jadwal</a>
                        <a href="{{ route('lapangan.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100">Lapangan</a>
                        <a href="{{ route('reservasi.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100">{{ auth()->user()->isAdmin() ? 'Semua Reservasi' : 'Reservasi Saya' }}</a>
                        @if(auth()->user()->isAdmin())
                        <div class="pt-2 border-t border-gray-200">
                            <span class="px-3 text-xs font-semibold text-amber-600 uppercase">Admin Panel</span>
                            <a href="{{ route('admin.lapangan.create') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 mt-1">Tambah Lapangan</a>
                            <a href="{{ route('admin.pelanggan.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">Data Pelanggan</a>
                            <a href="{{ route('admin.laporan.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">Laporan Penggunaan</a>
                        </div>
                        @else
                        <a href="{{ route('reservasi.create') }}" class="block px-3 py-2 rounded-md bg-green-600 text-white font-medium text-sm text-center shadow-sm">Booking Sekarang</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-gray-200">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-md font-medium">Logout</button>
                        </form>
                        @else
                        <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 text-center font-medium">Login</a>
                        <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md bg-blue-600 text-white text-center font-medium shadow-sm">Daftar Sekarang</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Toast / Alert Messages -->
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 rounded-md bg-green-50 border border-green-200 text-green-800 flex items-center justify-between shadow-sm">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-md bg-green-100 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-green-600"></i>
                </div>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-green-600 hover:text-green-800 p-1">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 rounded-md bg-red-50 border border-red-200 text-red-800 flex items-center justify-between shadow-sm">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-md bg-red-100 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>
                </div>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
            <button @click="show = false" class="text-red-600 hover:text-red-800 p-1">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 p-4 rounded-md bg-yellow-50 border border-yellow-200 text-yellow-800 shadow-sm">
            <div class="flex items-center space-x-2 mb-2 font-semibold">
                <i data-lucide="alert-circle" class="w-5 h-5 text-yellow-600"></i>
                <span>Terdapat kesalahan pada input Anda:</span>
            </div>
            <ul class="list-disc list-inside text-sm space-y-1 pl-2 text-yellow-700">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Page Content -->
        @yield('content')
    </main>
    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-center text-sm text-gray-500">
            <div class="flex items-center space-x-2">
                <div class="w-6 h-6 rounded bg-blue-600 flex items-center justify-center font-bold text-white text-xs">SM</div>
                <span class="font-semibold text-gray-700">SM Sport Center</span>
                <span>&mdash; Sistem Reservasi Lapangan Olahraga</span>
            </div>
        </div>
    </footer>

    <!-- Initialize Lucide Icons -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            lucide.createIcons();
        });
    </script>
    @stack('scripts')
</body>
</html>
