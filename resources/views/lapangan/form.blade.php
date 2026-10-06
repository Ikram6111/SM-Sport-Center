@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-6">
    <div class="bg-white p-8 rounded-lg border border-gray-200 shadow-sm relative">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $isEdit ? 'Edit Data Lapangan' : 'Tambah Lapangan Baru' }}</h1>
                <p class="text-xs text-gray-500 mt-1">Kelola data spesifikasi dan tarif lapangan SM Sport Center</p>
            </div>
            <a href="{{ route('lapangan.index') }}" class="p-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-900 transition-colors" title="Kembali">
                <i data-lucide="x" class="w-5 h-5"></i>
            </a>
        </div>

        <form action="{{ $isEdit ? route('admin.lapangan.update', $lapangan) : route('admin.lapangan.store') }}" method="POST" class="space-y-5">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div>
                <label for="nama_lapangan" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lapangan *</label>
                <input type="text" id="nama_lapangan" name="nama_lapangan" required value="{{ old('nama_lapangan', $lapangan->nama_lapangan) }}"
                    class="block w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                    placeholder="Contoh: Lapangan Futsal A (Rumput Sintetis)">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="jenis_lapangan" class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Lapangan *</label>
                    <select id="jenis_lapangan" name="jenis_lapangan" required
                        class="block w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-md text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="Futsal" {{ old('jenis_lapangan', $lapangan->jenis_lapangan) === 'Futsal' ? 'selected' : '' }}>Futsal</option>
                        <option value="Badminton" {{ old('jenis_lapangan', $lapangan->jenis_lapangan) === 'Badminton' ? 'selected' : '' }}>Badminton</option>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status Ketersediaan *</label>
                    <select id="status" name="status" required
                        class="block w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-md text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="tersedia" {{ old('status', $lapangan->status) === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="pemeliharaan" {{ old('status', $lapangan->status) === 'pemeliharaan' ? 'selected' : '' }}>Pemeliharaan (Maintenance)</option>
                        <option value="tidak_tersedia" {{ old('status', $lapangan->status) === 'tidak_tersedia' ? 'selected' : '' }}>Tidak Tersedia / Ditutup</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="harga_per_jam" class="block text-sm font-medium text-gray-700 mb-1.5">Harga Sewa per Jam (Rp) *</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500 font-semibold text-sm">Rp</span>
                    <input type="number" id="harga_per_jam" name="harga_per_jam" required min="0" step="1000" value="{{ old('harga_per_jam', $lapangan->harga_per_jam ?? 0) }}"
                        class="block w-full pl-11 pr-3.5 py-2.5 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        placeholder="150000">
                </div>
            </div>

            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi &amp; Spesifikasi (Opsi)</label>
                <textarea id="deskripsi" name="deskripsi" rows="3"
                    class="block w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                    placeholder="Tuliskan spesifikasi lantai, pencahayaan, fasilitas...">{{ old('deskripsi', $lapangan->deskripsi) }}</textarea>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-gray-200">
                <a href="{{ route('lapangan.index') }}" class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium text-sm transition-colors border border-gray-300">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm transition-colors">
                    {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Lapangan' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
