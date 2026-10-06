@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Laporan Penggunaan Lapangan</h1>
            <p class="text-sm text-gray-600 mt-1">Analisis riwayat transaksi, durasi pemakaian, dan estimasi pendapatan SM Sport Center</p>
        </div>
        <div>
            <a href="{{ route('admin.laporan.print', request()->all()) }}" target="_blank" class="px-4 py-2.5 rounded-md bg-green-600 hover:bg-green-700 text-white font-medium text-sm shadow-sm transition-colors inline-flex items-center">
                <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Cetak Laporan (PDF)
            </a>
        </div>
    </div>

    <!-- Filter Laporan -->
    <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-5 gap-4 items-end">
            <div>
                <label for="start_date" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Mulai Tanggal</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                    class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="end_date" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                    class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="lapangan_id" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Filter Lapangan</label>
                <select id="lapangan_id" name="lapangan_id"
                    class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Lapangan</option>
                    @foreach($lapangans as $lap)
                    <option value="{{ $lap->id }}" {{ $lapanganId == $lap->id ? 'selected' : '' }}>{{ $lap->nama_lapangan }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Status Reservasi</label>
                <select id="status" name="status"
                    class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Confirmed (Dikonfirmasi)</option>
                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed (Selesai Bermain)</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                    <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                </select>
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="flex-1 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm shadow-sm transition-colors flex items-center justify-center">
                    <i data-lucide="filter" class="w-4 h-4 mr-1.5"></i> Filter
                </button>
                <a href="{{ route('admin.laporan.index') }}" class="px-3.5 py-2 rounded-md bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-900 text-sm transition-colors flex items-center justify-center border border-gray-200">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Ringkasan Keuangan & Analitik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Reservasi Masuk</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalTransaksi }} <span class="text-sm font-normal text-gray-500">transaksi</span></p>
        </div>

        <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Berhasil / Selesai Main</p>
            <p class="text-2xl font-bold text-green-600 mt-1">{{ $totalSelesai }} <span class="text-sm font-normal text-gray-500">transaksi</span></p>
        </div>

        <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Durasi Pemakaian</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $totalJam }} <span class="text-sm font-normal text-gray-500">Jam</span></p>
        </div>

        <div class="bg-green-50 p-5 rounded-lg border border-green-200 shadow-sm">
            <p class="text-xs font-semibold text-green-800 uppercase tracking-wider">Estimasi Total Pendapatan</p>
            <p class="text-2xl sm:text-3xl font-bold text-green-700 font-mono mt-1">Rp {{ number_format($estimasiPendapatan, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Tabel Laporan -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-blue-600 text-white text-xs uppercase font-semibold">
                        <th class="py-3 px-4">Tanggal Main</th>
                        <th class="py-3 px-4">Kode &amp; Pelanggan</th>
                        <th class="py-3 px-4">Lapangan &amp; Waktu</th>
                        <th class="py-3 px-4">Durasi</th>
                        <th class="py-3 px-4">Tarif Sewa</th>
                        <th class="py-3 px-4 text-right">Subtotal</th>
                        <th class="py-3 px-4 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm bg-white">
                    @forelse($reservasis as $res)
                    @php
                        $durasi = \Carbon\Carbon::parse($res->jam_selesai)->diffInHours(\Carbon\Carbon::parse($res->jam_mulai));
                        $subtotal = $durasi * ($res->lapangan->harga_per_jam ?? 0);
                        $isValidRevenue = in_array($res->status_reservasi, ['confirmed', 'completed']);
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 font-medium text-gray-900">{{ \Carbon\Carbon::parse($res->tanggal_reservasi)->translatedFormat('d/m/Y') }}</td>
                        <td class="py-3 px-4">
                            <span class="font-mono text-xs text-blue-600 font-medium block">{{ $res->kode_reservasi }}</span>
                            <span class="font-bold text-gray-900">{{ $res->pelanggan->nama_pelanggan }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="font-medium text-gray-900 block">{{ $res->lapangan->nama_lapangan }}</span>
                            <span class="text-xs font-mono text-gray-500">{{ substr($res->jam_mulai, 0, 5) }} - {{ substr($res->jam_selesai, 0, 5) }} WIB</span>
                        </td>
                        <td class="py-3 px-4 font-mono text-gray-700">{{ $durasi }} Jam</td>
                        <td class="py-3 px-4 font-mono text-gray-500">Rp {{ number_format($res->lapangan->harga_per_jam ?? 0, 0, ',', '.') }}</td>
                        <td class="py-3 px-4 text-right font-mono font-bold {{ $isValidRevenue ? 'text-gray-900' : 'text-gray-400 line-through' }}">
                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-4 text-right">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold border {{ $res->status_badge_class }} inline-block">
                                {{ $res->status_label }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-gray-500">
                            <i data-lucide="file-x" class="w-12 h-12 mx-auto mb-3 opacity-40 text-gray-400"></i>
                            <p class="text-base font-medium text-gray-700">Tidak ada data reservasi pada periode ini</p>
                            <p class="text-xs text-gray-500 mt-1">Coba sesuaikan rentang tanggal atau filter lapangan yang Anda pilih.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($reservasis->count() > 0)
                <tfoot>
                    <tr class="bg-gray-50 border-t-2 border-gray-300 text-sm font-bold text-gray-900">
                        <td colspan="5" class="py-3 px-4 text-right uppercase tracking-wider">Total Pendapatan Terkonfirmasi:</td>
                        <td class="py-3 px-4 text-right font-mono text-green-700 text-base">Rp {{ number_format($estimasiPendapatan, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
