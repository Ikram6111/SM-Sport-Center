@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Cek Ketersediaan Lapangan</h1>
            <p class="text-sm text-gray-600 mt-1">Pantau slot jam kosong secara real-time dan langsung booking jam yang kamu inginkan</p>
        </div>
        <div>
            <a href="{{ route('reservasi.create', ['tanggal' => $tanggal]) }}" class="px-4 py-2.5 rounded-md bg-green-600 hover:bg-green-700 text-white font-medium text-sm shadow-sm transition-colors inline-flex items-center">
                <i data-lucide="plus-circle" class="w-4 h-4 mr-2"></i> Buat Booking Baru
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
        <form method="GET" action="{{ route('ketersediaan.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
            <div>
                <label for="tanggal" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Pilih Tanggal</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </span>
                    <input type="date" id="tanggal" name="tanggal" value="{{ $tanggal }}" min="{{ date('Y-m-d') }}"
                        class="block w-full pl-10 pr-3 py-2 bg-white border border-gray-300 rounded-md text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
            </div>

            <div>
                <label for="jenis" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Kategori Lapangan</label>
                <select id="jenis" name="jenis"
                    class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="Semua" {{ $jenis === 'Semua' ? 'selected' : '' }}>Semua Kategori (Futsal &amp; Badminton)</option>
                    <option value="Futsal" {{ $jenis === 'Futsal' ? 'selected' : '' }}>Khusus Futsal</option>
                    <option value="Badminton" {{ $jenis === 'Badminton' ? 'selected' : '' }}>Khusus Badminton</option>
                </select>
            </div>

            <div>
                <button type="submit" class="w-full py-2 px-4 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm shadow-sm transition-colors flex items-center justify-center">
                    <i data-lucide="filter" class="w-4 h-4 mr-2"></i> Tampilkan Jadwal
                </button>
            </div>
        </form>
    </div>

    <!-- Timeline Ketersediaan Lapangan -->
    <div class="space-y-6">
        @forelse($lapangans as $lap)
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 mb-4 border-b border-gray-200 gap-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-md {{ $lap->jenis_lapangan === 'Futsal' ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'bg-green-100 text-green-700 border border-green-200' }} flex items-center justify-center flex-shrink-0 font-bold">
                        {{ substr($lap->jenis_lapangan, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $lap->nama_lapangan }}</h3>
                        <p class="text-xs text-gray-500">{{ $lap->jenis_lapangan }} &bull; Rp {{ number_format($lap->harga_per_jam, 0, ',', '.') }}/jam</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4 text-xs font-medium">
                    <span class="flex items-center text-green-700"><span class="w-2.5 h-2.5 rounded-full bg-green-500 mr-1.5"></span> Kosong (Bisa Dipesan)</span>
                    <span class="flex items-center text-red-700"><span class="w-2.5 h-2.5 rounded-full bg-red-500 mr-1.5"></span> Terisi / Booked</span>
                </div>
            </div>

            <!-- Slots Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-3">
                @php
                    $lapReservasis = $reservasis->get($lap->id, collect());
                @endphp

                @foreach($operasionalHours as $slot)
                    @php
                        // Cek apakah slot waktu ini beririsan (overlap) dengan reservasi yang ada di lapangan ini
                        $bookedRes = $lapReservasis->first(function($res) use ($slot) {
                            return ($res->jam_mulai < $slot['end']) && ($res->jam_selesai > $slot['start']);
                        });
                    @endphp

                    @if($bookedRes)
                    <div class="p-3 rounded-md bg-red-50 border border-red-200 text-center flex flex-col justify-between select-none">
                        <span class="font-mono text-xs font-bold text-red-700">{{ substr($slot['start'], 0, 5) }} - {{ substr($slot['end'], 0, 5) }}</span>
                        <div class="mt-2 py-1 px-1 rounded bg-red-100 text-[11px] font-semibold text-red-800 truncate" title="Dipesan oleh {{ $bookedRes->pelanggan->nama_pelanggan }}">
                            <i data-lucide="lock" class="w-3 h-3 inline mr-1 -mt-0.5"></i> {{ substr($bookedRes->pelanggan->nama_pelanggan, 0, 10) }}...
                        </div>
                    </div>
                    @else
                    <a href="{{ route('reservasi.create', ['lapangan_id' => $lap->id, 'tanggal' => $tanggal, 'jam_mulai' => substr($slot['start'], 0, 5), 'jam_selesai' => substr($slot['end'], 0, 5)]) }}" 
                        class="p-3 rounded-md bg-green-50 border border-green-200 text-center flex flex-col justify-between hover:bg-green-100 hover:border-green-300 transition-all group cursor-pointer"
                        title="Klik untuk langsung memesan jam ini!">
                        <span class="font-mono text-xs font-bold text-green-700 group-hover:text-green-900">{{ substr($slot['start'], 0, 5) }} - {{ substr($slot['end'], 0, 5) }}</span>
                        <div class="mt-2 py-1 px-1 rounded bg-green-100 text-[11px] font-semibold text-green-800 group-hover:bg-green-600 group-hover:text-white transition-colors flex items-center justify-center">
                            <i data-lucide="plus" class="w-3 h-3 mr-1"></i> Kosong
                        </div>
                    </a>
                    @endif
                @endforeach
            </div>
        </div>
        @empty
        <div class="bg-white p-12 rounded-lg border border-gray-200 text-center text-gray-500 shadow-sm">
            <i data-lucide="calendar-x" class="w-12 h-12 mx-auto mb-3 opacity-40 text-gray-400"></i>
            <p class="text-base font-semibold text-gray-700">Tidak ada lapangan yang tersedia pada kategori ini.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
