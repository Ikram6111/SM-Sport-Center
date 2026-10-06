@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Manajemen Data Pelanggan</h1>
            <p class="text-sm text-gray-600 mt-1">Kelola data pelanggan dan member terdaftar di SM Sport Center</p>
        </div>
        <div>
            <a href="{{ route('admin.pelanggan.create') }}" class="px-4 py-2.5 rounded-md bg-green-600 hover:bg-green-700 text-white font-medium text-sm shadow-sm transition-colors inline-flex items-center">
                <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i> Tambah Pelanggan (Walk-in)
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <form method="GET" action="{{ route('admin.pelanggan.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama pelanggan, nomor telepon/WhatsApp, atau domisili..."
                    class="block w-full pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
            </div>
            <button type="submit" class="px-5 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm shadow-sm transition-colors flex items-center justify-center">
                Cari Data
            </button>
            @if($search)
            <a href="{{ route('admin.pelanggan.index') }}" class="px-4 py-2 rounded-md bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-900 font-medium text-sm transition-colors text-center border border-gray-200 flex items-center justify-center">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Pelanggan Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-blue-600 text-white text-xs uppercase font-semibold">
                        <th class="py-3 px-4">No</th>
                        <th class="py-3 px-4">Nama Pelanggan</th>
                        <th class="py-3 px-4">Nomor Telepon / WA</th>
                        <th class="py-3 px-4">Alamat / Domisili</th>
                        <th class="py-3 px-4">Status Akun</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm bg-white">
                    @forelse($pelanggans as $key => $pelanggan)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 font-mono text-gray-500">{{ $pelanggans->firstItem() + $key }}</td>
                        <td class="py-3 px-4 font-bold text-gray-900">{{ $pelanggan->nama_pelanggan }}</td>
                        <td class="py-3 px-4 font-mono text-blue-600 font-medium">{{ $pelanggan->nomor_telepon }}</td>
                        <td class="py-3 px-4 text-gray-600 max-w-xs truncate">{{ $pelanggan->alamat ?? '-' }}</td>
                        <td class="py-3 px-4">
                            @if($pelanggan->user_id)
                            <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-green-50 text-green-700 border border-green-200 inline-flex items-center">
                                <i data-lucide="shield-check" class="w-3.5 h-3.5 mr-1 text-green-600"></i> Terdaftar Online
                            </span>
                            @else
                            <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                Walk-In / Offline
                            </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right space-x-1">
                            <a href="{{ route('admin.pelanggan.edit', $pelanggan) }}" class="p-1.5 rounded-md bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 inline-block transition-colors" title="Edit Pelanggan">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.pelanggan.destroy', $pelanggan) }}" class="inline-block" onsubmit="return confirm('Hapus data pelanggan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-md bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 transition-colors" title="Hapus Pelanggan">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-500">
                            <i data-lucide="users" class="w-10 h-10 mx-auto mb-3 opacity-40 text-gray-400"></i>
                            <p class="font-medium text-gray-700">Data pelanggan tidak ditemukan</p>
                            <p class="text-xs text-gray-500 mt-1">Coba gunakan kata kunci pencarian lain atau tambah pelanggan baru.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pelanggans->hasPages())
        <div class="p-4 border-t border-gray-200 bg-gray-50">
            {{ $pelanggans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
