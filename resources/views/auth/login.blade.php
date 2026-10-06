<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SM Sport Center</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col justify-between font-sans antialiased">

    <!-- Top Header Navigation -->
    <header class="bg-white border-b border-gray-200 py-4 px-6 sm:px-12">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo SM Sport Center" class="h-9 w-auto object-contain">
                <span class="text-lg font-bold text-gray-900">SM Sport Center</span>
            </a>
            <div class="flex items-center space-x-4">
                <a href="{{ route('login') }}" class="text-sm font-medium text-blue-600">
                    Login
                </a>
                <a href="{{ route('register') }}" class="px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm transition-colors shadow-sm">
                    Daftar Akun
                </a>
            </div>
        </div>
    </header>

    <!-- Center Card Section -->
    <main class="flex-grow flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full bg-white border border-gray-200 rounded-lg p-8 shadow-sm">
            
            <!-- Alert Messages -->
            @if(session('success'))
            <div class="mb-6 p-3.5 rounded-md bg-green-50 border border-green-200 text-green-800 text-sm flex items-center space-x-2.5">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-green-600 flex-shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-3.5 rounded-md bg-red-50 border border-red-200 text-red-800 text-sm flex items-center space-x-2.5">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600 flex-shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 p-3.5 rounded-md bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm">
                <div class="flex items-center space-x-2 font-semibold mb-1">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-yellow-600"></i>
                    <span>Kesalahan input:</span>
                </div>
                <ul class="list-disc list-inside text-xs pl-1 space-y-0.5">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Title -->
            <div class="text-center mb-6">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-3 font-bold">
                    <i data-lucide="log-in" class="w-6 h-6"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Masuk ke Akun</h1>
                <p class="text-sm text-gray-600 mt-1">Silakan login untuk melakukan reservasi lapangan</p>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-4" x-data="{ showPass: false }">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </span>
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                            class="block w-full pl-10 pr-3.5 py-2 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="email@contoh.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </span>
                        <input id="password" name="password" :type="showPass ? 'text' : 'password'" autocomplete="current-password" required
                            class="block w-full pl-10 pr-10 py-2 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="••••••••">
                        <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <i x-show="!showPass" data-lucide="eye-off" class="w-4 h-4"></i>
                            <i x-show="showPass" data-lucide="eye" class="w-4 h-4" style="display: none;"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center cursor-pointer select-none">
                        <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded bg-white border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500 cursor-pointer">
                        <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm transition-colors flex items-center justify-center">
                        <span>Masuk Sekarang</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-xs">
                    <span class="bg-white px-3 text-gray-500 font-medium">atau</span>
                </div>
            </div>

            <!-- Bottom Link -->
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Belum memiliki akun?
                    <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:underline ml-1">Daftar Sekarang</a>
                </p>
            </div>
        </div>
    </main>

    <!-- Bottom Footer -->
    <footer class="bg-white border-t border-gray-200 py-6 text-center text-xs text-gray-500">
        <p>SM Sport Center &mdash; Sistem Reservasi Lapangan Olahraga</p>
    </footer>

    <!-- Initialize Lucide Icons -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            lucide.createIcons();
        });
    </script>
</body>
</html>
