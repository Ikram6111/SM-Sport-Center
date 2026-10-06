<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SM Sport Center - Pesan Lapangan Olahraga Online dengan Mudah</title>
    <meta name="description" content="Sistem Reservasi Lapangan Futsal dan Badminton SM Sport Center. Pesan lapangan online dengan mudah dan cepat.">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col justify-between font-sans antialiased" x-data="{ mobileMenu: false }">

    <!-- Top Header Navigation -->
    <header class="bg-white border-b border-gray-200 py-4 px-6 sm:px-12 sticky top-0 z-30 shadow-sm">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo SM Sport Center" class="h-9 w-auto object-contain">
                <span class="text-lg font-bold text-gray-900">SM Sport Center</span>
            </a>

            <div class="hidden sm:flex items-center space-x-6">
                <a href="#fasilitas" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                    Fasilitas
                </a>
                <a href="{{ route('ketersediaan.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                    Cek Jadwal
                </a>
                <div class="h-4 w-px bg-gray-300"></div>
                <a href="{{ route('login') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
                    Login
                </a>
                <a href="{{ route('register') }}" class="px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm transition-colors shadow-sm">
                    Daftar Sekarang
                </a>
            </div>

            <div class="sm:hidden relative">
                <button @click="mobileMenu = !mobileMenu" type="button" class="p-2 text-gray-600 hover:text-gray-900 focus:outline-none rounded-md hover:bg-gray-100 transition-colors">
                    <i x-show="!mobileMenu" data-lucide="menu" class="w-6 h-6"></i>
                    <i x-show="mobileMenu" data-lucide="x" class="w-6 h-6" style="display: none;"></i>
                </button>

                <!-- Mobile Dropdown Menu -->
                <div x-show="mobileMenu" 
                     @click.away="mobileMenu = false"
                     x-transition
                     class="absolute right-0 mt-2 w-48 rounded-md bg-white border border-gray-200 shadow-lg py-2 px-2 z-50 text-sm"
                     style="display: none;">
                    <a href="#fasilitas" @click="mobileMenu = false" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Fasilitas</a>
                    <a href="{{ route('ketersediaan.index') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Cek Jadwal</a>
                    <div class="my-1 border-t border-gray-200"></div>
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-blue-600 font-medium hover:bg-blue-50 rounded-md">Login</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-md text-center mt-1">Daftar Sekarang</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow">
        
        <!-- Hero Section -->
        <section class="max-w-6xl mx-auto px-6 py-16 md:py-24 grid grid-cols-1 md:grid-cols-12 gap-12 items-center">
            <!-- Left Text Column -->
            <div class="md:col-span-7 space-y-6 text-left">
                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                    <span class="w-2 h-2 rounded-full bg-blue-600 mr-2 animate-pulse"></span> Sistem Reservasi Lapangan Online
                </div>
                <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 tracking-tight leading-tight">
                    Pesan Lapangan Olahraga dengan Mudah &amp; Cepat
                </h1>
                <p class="text-base sm:text-lg text-gray-600 leading-relaxed max-w-lg">
                    Selamat datang di SM Sport Center. Nikmati kemudahan booking jadwal lapangan Futsal dan Badminton secara real-time tanpa ribet.
                </p>
                <div class="flex flex-wrap gap-4 pt-2">
                    <a href="{{ route('ketersediaan.index') }}" class="px-6 py-3 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm transition-colors flex items-center">
                        <i data-lucide="calendar-check" class="w-4 h-4 mr-2"></i> Cek Jadwal Kosong
                    </a>
                    <a href="#fasilitas" class="px-6 py-3 rounded-md bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 font-semibold text-sm transition-colors flex items-center">
                        Lihat Fasilitas
                    </a>
                </div>
            </div>

            <!-- Right Visual Column -->
            <div class="md:col-span-5 flex justify-center">
                <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm w-full max-w-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Info Jam Operasional</span>
                        <span class="px-2 py-0.5 rounded text-xs bg-green-100 text-green-800 font-semibold">Buka Setiap Hari</span>
                    </div>

                    <div class="space-y-3">
                        <div class="p-3.5 rounded-md bg-gray-50 border border-gray-200 flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-md bg-blue-100 text-blue-700 flex items-center justify-center font-bold flex-shrink-0">
                                <i data-lucide="clock" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">08:00 - 22:00 WIB</h4>
                                <p class="text-xs text-gray-500">Jadwal main dibagi per slot 1 jam</p>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-md bg-gray-50 border border-gray-200 flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-md bg-green-100 text-green-700 flex items-center justify-center font-bold flex-shrink-0">
                                <i data-lucide="shield-check" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">Fasilitas Terawat &amp; Bersih</h4>
                                <p class="text-xs text-gray-500">Standar kenyamanan bermain terbaik</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('register') }}" class="block w-full py-2.5 rounded-md bg-green-600 hover:bg-green-700 text-white font-semibold text-xs text-center transition-colors shadow-sm">
                            Daftar Member Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Fasilitas Kami Section -->
        <section id="fasilitas" class="max-w-6xl mx-auto px-6 py-16 border-t border-gray-200">
            <div class="text-center mb-12">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Fasilitas SM Sport Center</h2>
                <p class="text-sm text-gray-600 mt-2 max-w-md mx-auto">Kami menyediakan lapangan berkualitas tinggi yang dirawat secara berkala demi kenyamanan bermain Anda.</p>
            </div>

            <!-- Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                
                <!-- Card 1: Lapangan Badminton -->
                <a href="{{ route('lapangan.index') }}" class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow group block">
                    <div class="h-56 overflow-hidden relative bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?q=80&w=1000&auto=format&fit=crop" 
                             alt="Lapangan Badminton" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute top-3 left-3">
                            <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-600 text-white shadow-sm">Badminton</span>
                        </div>
                    </div>

                    <div class="p-6 text-left">
                        <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                            3 Lapangan Badminton
                        </h3>
                        <p class="text-sm text-gray-600 mt-2 leading-relaxed">
                            Lantai standar kompetisi dengan pencahayaan LED superior dan sirkulasi udara yang sejuk untuk permainan maksimal.
                        </p>

                    </div>
                </a>

                <!-- Card 2: Lapangan Futsal -->
                <a href="{{ route('lapangan.index') }}" class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow group block">
                    <div class="h-56 overflow-hidden relative bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=1000&auto=format&fit=crop" 
                             alt="Lapangan Futsal" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute top-3 left-3">
                            <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-green-600 text-white shadow-sm">Futsal</span>
                        </div>
                    </div>

                    <div class="p-6 text-left">
                        <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                            2 Lapangan Futsal
                        </h3>
                        <p class="text-sm text-gray-600 mt-2 leading-relaxed">
                            Permukaan rumput sintetis interlock berdaya cengkeram tinggi yang aman untuk lutut serta jaring keliling yang kokoh.
                        </p>

                    </div>
                </a>

            </div>
        </section>

    </main>

    <!-- Footer Section -->
    <footer class="bg-white border-t border-gray-200 py-8 px-6 text-center">
        <div class="max-w-6xl mx-auto flex flex-col items-center justify-center space-y-3">
            <div class="flex items-center space-x-2.5 text-lg font-bold text-gray-900">
                <img src="{{ asset('images/logo.png') }}" alt="Logo SM Sport Center" class="h-8 w-auto object-contain">
                <span>SM Sport Center</span>
            </div>
            <p class="text-xs text-gray-500 max-w-sm">
                Sistem Informasi Reservasi dan Manajemen Lapangan Olahraga.
            </p>
        </div>
    </footer>

    <!-- Initialize Lucide Icons -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
