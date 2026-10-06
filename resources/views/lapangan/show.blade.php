@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 py-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('lapangan.index') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1.5"></i> Kembali ke Daftar Lapangan
        </a>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.lapangan.edit', $lapangan) }}" class="px-4 py-2 rounded-md bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200 text-xs font-semibold inline-flex items-center transition-colors">
            <i data-lucide="edit" class="w-3.5 h-3.5 mr-1.5"></i> Edit Spesifikasi
        </a>
        @endif
    </div>

    <div class="bg-white p-8 rounded-lg border border-gray-200 shadow-sm relative">
        <div class="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b border-gray-200 gap-4">
            <div>
                <span class="px-2.5 py-1 rounded-md text-xs font-semibold {{ $lapangan->jenis_lapangan === 'Futsal' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-green-50 text-green-700 border border-green-200' }}">
                    {{ $lapangan->jenis_lapangan }}
                </span>
                <h1 class="text-3xl font-bold text-gray-900 mt-3">{{ $lapangan->nama_lapangan }}</h1>
            </div>
            <div class="text-left md:text-right">
                <span class="text-xs text-gray-500 block">Tarif Sewa Reguler</span>
                <span class="text-3xl font-bold text-gray-900">Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}<span class="text-sm font-normal text-gray-500">/jam</span></span>
            </div>
        </div>

        <div class="py-6 space-y-4">
            <h3 class="text-lg font-bold text-gray-900">Deskripsi &amp; Fasilitas Lapangan</h3>
            <p class="text-gray-700 leading-relaxed text-sm whitespace-pre-line">{{ $lapangan->deskripsi ?? 'Tidak ada deskripsi tambahan untuk lapangan ini.' }}</p>
        </div>

        <div class="pt-6 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-green-600 flex-shrink-0"></i>
                <span>Jadwal bisa dicek langsung di menu Ketersediaan.</span>
            </div>
            @if($lapangan->status === 'tersedia')
            <a href="{{ route('reservasi.create', ['lapangan_id' => $lapangan->id]) }}" class="w-full sm:w-auto px-6 py-2.5 rounded-md bg-green-600 hover:bg-green-700 text-white font-semibold text-sm shadow-sm transition-colors text-center">
                Pesan Lapangan Ini
            </a>
            @else
            <span class="px-6 py-2.5 rounded-md bg-gray-100 text-gray-500 text-sm font-semibold border border-gray-200 text-center">
                Mohon Maaf, Lapangan Sedang Tidak Tersedia
            </span>
            @endif
        </div>
    </div>
</div>
@endsection
