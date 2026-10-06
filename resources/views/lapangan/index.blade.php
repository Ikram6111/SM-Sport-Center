@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Daftar Lapangan Olahraga</h1>
            <p class="text-sm text-gray-600 mt-1">SM Sport Center memiliki 2 Lapangan Futsal dan 3 Lapangan Badminton berstandar tinggi</p>
        </div>
        @if(auth()->user()->isAdmin())
        <div>
            <a href="{{ route('admin.lapangan.create') }}" class="px-4 py-2.5 rounded-md bg-green-600 hover:bg-green-700 text-white font-medium text-sm shadow-sm transition-colors inline-flex items-center">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Lapangan
            </a>
        </div>
        @endif
    </div>

    <!-- Lapangan Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($lapangans as $lap)
        <div class="bg-white rounded-lg border border-gray-200 p-6 flex flex-col justify-between shadow-sm hover:shadow-md hover:border-blue-300 transition-all group">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-1 rounded-md text-xs font-semibold {{ $lap->jenis_lapangan === 'Futsal' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-green-50 text-green-700 border border-green-200' }}">
                        {{ $lap->jenis_lapangan }}
                    </span>
                    @if($lap->status === 'tersedia')
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200 flex items-center">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-600 mr-1.5 animate-pulse"></span> Tersedia
                    </span>
                    @elseif($lap->status === 'pemeliharaan')
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200">
                        Pemeliharaan
                    </span>
                    @else
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                        Tidak Tersedia
                    </span>
                    @endif
                </div>

                <div>
                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $lap->nama_lapangan }}</h3>
                    <p class="text-sm text-gray-600 mt-2 line-clamp-3 leading-relaxed">{{ $lap->deskripsi ?? 'Lapangan dengan spesifikasi standar nasional yang terawat dengan baik.' }}</p>
                </div>
            </div>

            <div class="mt-6 pt-5 border-t border-gray-200 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">Harga Sewa</span>
                    <span class="text-lg font-bold text-gray-900">Rp {{ number_format($lap->harga_per_jam, 0, ',', '.') }} <span class="text-xs font-normal text-gray-500">/jam</span></span>
                </div>

                <div class="flex items-center space-x-2">
                    @if($lap->status === 'tersedia')
                    <a href="{{ route('reservasi.create', ['lapangan_id' => $lap->id]) }}" class="flex-1 py-2 px-3 rounded-md bg-green-600 hover:bg-green-700 text-white text-xs font-semibold text-center shadow-sm transition-colors">
                        Booking Sekarang
                    </a>
                    @else
                    <button disabled class="flex-1 py-2 px-3 rounded-md bg-gray-100 text-gray-400 text-xs font-semibold text-center cursor-not-allowed border border-gray-200">
                        Slot Tidak Tersedia
                    </button>
                    @endif

                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.lapangan.edit', $lap) }}" class="p-2 rounded-md bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 transition-colors" title="Edit Lapangan">
                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.lapangan.destroy', $lap) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data lapangan ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 rounded-md bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 transition-colors" title="Hapus Lapangan">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
