@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Welcome Header -->
    <div class="bg-white p-6 sm:p-8 rounded-lg shadow-sm border border-gray-200 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center space-x-2 text-blue-600 font-semibold text-sm mb-1">
                <span class="inline-block w-2 h-2 rounded-full bg-blue-600"></span>
                <span>{{ auth()->user()->isAdmin() ? 'Panel Administrator' : 'Portal Pelanggan' }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">
                Halo, {{ auth()->user()->name }}! 👋
            </h1>
            <p class="text-gray-600 text-sm mt-1 max-w-2xl">
                {{ auth()->user()->isAdmin() ? 'Pantau ketersediaan lapangan, verifikasi pemesanan terbaru, dan unduh laporan operasional dengan mudah dari dasbor ini.' : 'Selamat datang di SM Sport Center. Cek jadwal kosong lapangan favoritmu dan lakukan reservasi instan tanpa perlu menunggu balasan chat.' }}
            </p>
        </div>

        <div class="flex items-center space-x-3 w-full md:w-auto">
            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.lapangan.create') }}" class="flex-1 md:flex-initial px-4 py-2 rounded-md bg-white hover:bg-gray-50 text-gray-700 font-medium text-sm border border-gray-300 shadow-sm transition-colors flex items-center justify-center">
                <i data-lucide="plus" class="w-4 h-4 mr-2 text-blue-600"></i> Lapangan
            </a>
            <a href="{{ route('admin.laporan.index') }}" class="flex-1 md:flex-initial px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm shadow-sm transition-colors flex items-center justify-center">
                <i data-lucide="bar-chart-2" class="w-4 h-4 mr-2"></i> Laporan
            </a>
            @else
            <a href="{{ route('ketersediaan.index') }}" class="flex-1 md:flex-initial px-4 py-2.5 rounded-md bg-white hover:bg-gray-50 text-gray-700 font-medium text-sm border border-gray-300 shadow-sm transition-colors flex items-center justify-center">
                <i data-lucide="calendar" class="w-4 h-4 mr-2 text-blue-600"></i> Cek Jadwal
            </a>
            <a href="{{ route('reservasi.create') }}" class="flex-1 md:flex-initial px-5 py-2.5 rounded-md bg-green-600 hover:bg-green-700 text-white font-medium text-sm shadow-sm transition-colors flex items-center justify-center">
                <i data-lucide="plus-circle" class="w-4 h-4 mr-2"></i> Booking Baru
            </a>
            @endif
        </div>
    </div>

    @if(auth()->user()->isAdmin())
    <!-- ADMIN STATS GRID -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Lapangan</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalLapangan }}</p>
                <span class="text-xs text-green-600 font-medium flex items-center mt-2">
                    <i data-lucide="check-circle" class="w-3.5 h-3.5 mr-1"></i> {{ $lapanganTersedia }} Siap Pakai
                </span>
            </div>
            <div class="w-12 h-12 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600">
                <i data-lucide="layout-grid" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Pelanggan</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalPelanggan }}</p>
                <span class="text-xs text-gray-500 font-medium block mt-2">Terdaftar dalam sistem</span>
            </div>
            <div class="w-12 h-12 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Reservasi Hari Ini</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $reservasiHariIni }}</p>
                <span class="text-xs text-gray-500 font-medium block mt-2">{{ date('d M Y') }}</span>
            </div>
            <div class="w-12 h-12 rounded-lg bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600">
                <i data-lucide="calendar-check" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Menunggu Verifikasi</p>
                <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $pendingApproval }}</p>
                <a href="{{ route('reservasi.index', ['status' => 'pending']) }}" class="text-xs text-yellow-600 hover:underline font-medium flex items-center mt-2">
                    Verifikasi sekarang <i data-lucide="arrow-right" class="w-3 h-3 ml-1"></i>
                </a>
            </div>
            <div class="w-12 h-12 rounded-lg bg-yellow-50 border border-yellow-100 flex items-center justify-center text-yellow-600">
                <i data-lucide="clock" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <!-- ADMIN TABLES AREA -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Jadwal Hari Ini -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Jadwal Main Hari Ini</h3>
                    <p class="text-xs text-gray-500">Daftar reservasi yang terjadwal untuk hari ini ({{ date('d M Y') }})</p>
                </div>
                <a href="{{ route('reservasi.index', ['tanggal' => date('Y-m-d')]) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">Lihat Semua</a>
            </div>

            <div class="overflow-x-auto border border-gray-200 rounded-md">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-blue-600 text-white text-xs uppercase font-semibold">
                            <th class="py-3 px-4">Jam</th>
                            <th class="py-3 px-4">Lapangan</th>
                            <th class="py-3 px-4">Pelanggan</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm bg-white">
                        @forelse($jadwalHariIni as $res)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4 font-mono text-blue-600 font-semibold">{{ substr($res->jam_mulai, 0, 5) }} - {{ substr($res->jam_selesai, 0, 5) }}</td>
                            <td class="py-3 px-4 font-medium text-gray-900">{{ $res->lapangan->nama_lapangan }}</td>
                            <td class="py-3 px-4 text-gray-700">{{ $res->pelanggan->nama_pelanggan }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $res->status_badge_class }}">
                                    {{ $res->status_label }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('reservasi.show', $res) }}" class="p-1.5 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 inline-block transition-colors" title="Lihat Detail">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500">
                                <i data-lucide="calendar-x" class="w-8 h-8 mx-auto mb-2 opacity-40 text-gray-400"></i>
                                Belum ada jadwal pemesanan untuk hari ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Reservasi Terbaru -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-4">
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-4">Reservasi Masuk Terbaru</h3>
            <div class="space-y-3">
                @forelse($reservasiTerbaru as $res)
                <div class="p-3.5 rounded-md bg-gray-50 border border-gray-200 flex items-center justify-between hover:border-blue-300 hover:bg-blue-50/40 transition-colors">
                    <div class="space-y-1 overflow-hidden pr-2">
                        <div class="flex items-center space-x-2">
                            <span class="text-xs font-mono text-gray-500">{{ $res->kode_reservasi }}</span>
                            <span class="w-1 h-1 rounded-full bg-gray-400"></span>
                            <span class="text-xs text-blue-600 font-medium">{{ date('d/m/Y', strtotime($res->tanggal_reservasi)) }}</span>
                        </div>
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $res->pelanggan->nama_pelanggan }}</p>
                        <p class="text-xs text-gray-600 truncate">{{ $res->lapangan->nama_lapangan }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold border {{ $res->status_badge_class }} block mb-1.5">
                            {{ $res->status_label }}
                        </span>
                        <a href="{{ route('reservasi.show', $res) }}" class="text-xs text-blue-600 hover:underline font-medium">Detail</a>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-6">Belum ada data reservasi.</p>
                @endforelse
            </div>
        </div>
    </div>

    @else
    <!-- CUSTOMER DASHBOARD AREA -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Jadwal Terdekat Card -->
        <div class="md:col-span-2 bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">Jadwal Kamu Selanjutnya</span>
                    <i data-lucide="clock" class="w-5 h-5 text-blue-600"></i>
                </div>

                @if($reservasiTerdekat)
                <h3 class="text-2xl font-bold text-gray-900">{{ $reservasiTerdekat->lapangan->nama_lapangan }}</h3>
                <div class="mt-4 flex flex-wrap items-center gap-4 text-sm text-gray-700">
                    <div class="flex items-center bg-gray-50 px-3.5 py-2 rounded-md border border-gray-200">
                        <i data-lucide="calendar" class="w-4 h-4 mr-2 text-blue-600"></i>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($reservasiTerdekat->tanggal_reservasi)->translatedFormat('l, d F Y') }}</span>
                    </div>
                    <div class="flex items-center bg-gray-50 px-3.5 py-2 rounded-md border border-gray-200">
                        <i data-lucide="time" class="w-4 h-4 mr-2 text-blue-600"></i>
                        <span class="font-mono font-medium">{{ substr($reservasiTerdekat->jam_mulai, 0, 5) }} - {{ substr($reservasiTerdekat->jam_selesai, 0, 5) }} WIB</span>
                    </div>
                </div>
                <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="px-3 py-1 rounded-md text-xs font-semibold border {{ $reservasiTerdekat->status_badge_class }}">
                        Status: {{ $reservasiTerdekat->status_label }}
                    </span>
                    <a href="{{ route('reservasi.show', $reservasiTerdekat) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center">
                        Lihat Tiket &amp; Detail <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                    </a>
                </div>
                @else
                <div class="py-8 text-center text-gray-500">
                    <i data-lucide="calendar-plus" class="w-10 h-10 mx-auto mb-2 text-blue-600 opacity-60"></i>
                    <p class="font-medium text-gray-700">Belum Ada Jadwal Main Terdekat</p>
                    <p class="text-xs text-gray-500 mt-1">Kamu belum memiliki pemesanan lapangan yang aktif atau mendatang.</p>
                </div>
                @endif
            </div>

            @if(!$reservasiTerdekat)
            <div class="mt-6 pt-4 border-t border-gray-200">
                <a href="{{ route('reservasi.create') }}" class="w-full py-2.5 rounded-md bg-green-600 hover:bg-green-700 text-white font-semibold text-sm text-center block shadow-sm transition-colors">
                    + Buat Pesanan Sekarang
                </a>
            </div>
            @endif
        </div>

        <!-- Total Reservasi Saya -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex flex-col justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Statistik Booking Kamu</p>
                <p class="text-4xl font-bold text-gray-900 mt-2">{{ $totalReservasiSaya }}</p>
                <span class="text-xs text-gray-500 block mt-1">Total seluruh riwayat pemesanan</span>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-200 space-y-2">
                <a href="{{ route('reservasi.index') }}" class="flex items-center justify-between p-3 rounded-md bg-gray-50 hover:bg-gray-100 text-sm text-gray-700 font-medium transition-colors border border-gray-200">
                    <span>Lihat Riwayat Booking</span>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                </a>
                <a href="{{ route('ketersediaan.index') }}" class="flex items-center justify-between p-3 rounded-md bg-gray-50 hover:bg-gray-100 text-sm text-gray-700 font-medium transition-colors border border-gray-200">
                    <span>Cek Ketersediaan Lapangan</span>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Rekomendasi Lapangan -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-900">Lapangan Olahraga SM Sport</h3>
            <a href="{{ route('lapangan.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Lihat Semua Lapangan &rarr;</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($lapanganTersedia as $lap)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 flex flex-col justify-between hover:shadow-md hover:border-blue-200 transition-all group">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2.5 py-1 rounded-md text-xs font-semibold {{ $lap->jenis_lapangan === 'Futsal' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-green-50 text-green-700 border border-green-200' }}">
                            {{ $lap->jenis_lapangan }}
                        </span>
                        <span class="text-xs font-semibold text-green-600 flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5 animate-pulse"></span> Tersedia
                        </span>
                    </div>
                    <h4 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $lap->nama_lapangan }}</h4>
                    <p class="text-xs text-gray-600 mt-2 line-clamp-2">{{ $lap->deskripsi ?? 'Lapangan berstandar tinggi untuk permainan terbaikmu.' }}</p>
                </div>

                <div class="mt-5 pt-4 border-t border-gray-200 flex items-center justify-between">
                    <div>
                        <span class="text-xs text-gray-500 block">Tarif Sewa</span>
                        <span class="text-sm font-bold text-gray-900">Rp {{ number_format($lap->harga_per_jam, 0, ',', '.') }}<span class="text-xs font-normal text-gray-500">/jam</span></span>
                    </div>
                    <a href="{{ route('reservasi.create', ['lapangan_id' => $lap->id]) }}" class="px-4 py-2 rounded-md bg-green-600 hover:bg-green-700 text-white text-xs font-semibold transition-all shadow-sm">
                        Pesan Slot
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
