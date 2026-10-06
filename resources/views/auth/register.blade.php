@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-6">
    <div class="bg-white p-8 rounded-lg border border-gray-200 shadow-sm">
        <div class="text-center mb-6">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-3 font-bold">
                <i data-lucide="user-plus" class="w-6 h-6"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Akun Pelanggan</h1>
            <p class="text-sm text-gray-600 mt-1">Lengkapi data diri Anda untuk kemudahan booking lapangan</p>
        </div>

        <form class="space-y-4" action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i data-lucide="user" class="w-4 h-4"></i>
                        </span>
                        <input id="name" name="name" type="text" required value="{{ old('name') }}"
                            class="block w-full pl-10 pr-3.5 py-2 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Nama Lengkap">
                    </div>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                        </span>
                        <input id="phone" name="phone" type="text" required value="{{ old('phone') }}"
                            class="block w-full pl-10 pr-3.5 py-2 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="08xxxxxxxxxx">
                    </div>
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email *</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                    </span>
                    <input id="email" name="email" type="email" required value="{{ old('email') }}"
                        class="block w-full pl-10 pr-3.5 py-2 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="email@contoh.com">
                </div>
            </div>

            <div>
                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat / Domisili (Opsi)</label>
                <div class="relative">
                    <span class="absolute top-2.5 left-0 pl-3.5 flex items-start pointer-events-none text-gray-400">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                    </span>
                    <textarea id="alamat" name="alamat" rows="2"
                        class="block w-full pl-10 pr-3.5 py-2 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Kota / Alamat singkat">{{ old('alamat') }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </span>
                        <input id="password" name="password" type="password" required
                            class="block w-full pl-10 pr-3.5 py-2 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Min. 6 karakter">
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                        </span>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="block w-full pl-10 pr-3.5 py-2 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Ulangi password">
                    </div>
                </div>
            </div>

            <div class="pt-3">
                <button type="submit" class="w-full py-2.5 px-4 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm transition-colors flex items-center justify-center">
                    <span>Daftar Akun Sekarang</span>
                    <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                </button>
            </div>
        </form>

        <div class="mt-6 pt-6 border-t border-gray-200 text-center">
            <p class="text-sm text-gray-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:underline ml-1">Masuk di sini</a>
            </p>
        </div>
    </div>
</div>
@endsection
