@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-6">
    <div class="bg-white p-8 rounded-lg border border-gray-200 shadow-sm relative">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $isEdit ? 'Edit Data Pelanggan' : 'Tambah Pelanggan (Walk-In)' }}</h1>
                <p class="text-xs text-gray-500 mt-1">Simpan data kontak pelanggan untuk keperluan reservasi dan laporan</p>
            </div>
            <a href="{{ route('admin.pelanggan.index') }}" class="p-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-900 transition-colors" title="Kembali">
                <i data-lucide="x" class="w-5 h-5"></i>
            </a>
        </div>

        <form action="{{ $isEdit ? route('admin.pelanggan.update', $pelanggan) : route('admin.pelanggan.store') }}" method="POST" class="space-y-5">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div>
                <label for="nama_pelanggan" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap / Klub *</label>
                <input type="text" id="nama_pelanggan" name="nama_pelanggan" required value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan) }}"
                    class="block w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                    placeholder="Contoh: Budi Santoso atau PB Tangkas">
            </div>

            <div>
                <label for="nomor_telepon" class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Telepon / WhatsApp *</label>
                <input type="text" id="nomor_telepon" name="nomor_telepon" required value="{{ old('nomor_telepon', $pelanggan->nomor_telepon) }}"
                    class="block w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                    placeholder="08123456789">
            </div>

            <div>
                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat / Domisili (Opsi)</label>
                <textarea id="alamat" name="alamat" rows="3"
                    class="block w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                    placeholder="Alamat lengkap atau kota domisili">{{ old('alamat', $pelanggan->alamat) }}</textarea>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-gray-200">
                <a href="{{ route('admin.pelanggan.index') }}" class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium text-sm transition-colors border border-gray-300">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm transition-colors">
                    {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Pelanggan' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
