<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Reservasi - SM Sport Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                background: white !important;
                color: black !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .print-border {
                border: 1px solid #000 !important;
            }
            th, td {
                border: 1px solid #ddd !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900 p-8 font-sans antialiased">

    <!-- Action Buttons (No Print) -->
    <div class="max-w-5xl mx-auto mb-6 flex justify-between items-center bg-white p-4 rounded-lg shadow-sm border border-gray-200 no-print">
        <a href="{{ route('admin.laporan.index', request()->all()) }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 flex items-center">
            &larr; Kembali ke halaman Laporan
        </a>
        <button onclick="window.print()" class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm rounded-md shadow-sm transition-colors">
            Cetak Sekarang / Simpan PDF
        </button>
    </div>

    <!-- Printable Paper Area -->
    <div class="max-w-5xl mx-auto bg-white p-10 rounded-lg shadow-sm border border-gray-200">
        <!-- Header Laporan -->
        <div class="flex items-center justify-between pb-6 border-b-2 border-gray-800">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">SM SPORT CENTER</h1>
                <p class="text-sm text-gray-600 mt-1">Jl. Olahraga Sehat No. 88, Kota Pusat &bull; Telp/WA: 0812-3456-7890</p>
                <p class="text-xs text-gray-500">Sistem Reservasi Lapangan Futsal &amp; Badminton</p>
            </div>
            <div class="text-right">
                <span class="text-xl font-bold uppercase tracking-wider text-gray-800 block">Laporan Operasional</span>
                <span class="text-xs text-gray-500">Dicetak pada: {{ date('d/m/Y H:i') }} WIB</span>
            </div>
        </div>

        <!-- Filter Info Summary -->
        <div class="grid grid-cols-3 gap-4 py-5 bg-gray-50 my-6 p-4 rounded-md border border-gray-200 text-sm">
            <div>
                <span class="text-xs text-gray-500 font-semibold uppercase block">Periode Tanggal</span>
                <span class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}</span>
            </div>
            <div>
                <span class="text-xs text-gray-500 font-semibold uppercase block">Filter Lapangan</span>
                <span class="font-bold text-gray-800">{{ $lapanganFilter }}</span>
            </div>
            <div>
                <span class="text-xs text-gray-500 font-semibold uppercase block">Filter Status</span>
                <span class="font-bold text-gray-800">{{ $status ? ucfirst($status) : 'Semua Status' }}</span>
            </div>
        </div>

        <!-- Tabel Laporan -->
        <table class="w-full text-left border-collapse text-sm mb-8">
            <thead>
                <tr class="bg-gray-800 text-white text-xs uppercase font-semibold">
                    <th class="py-3 px-4 border border-gray-700">No</th>
                    <th class="py-3 px-4 border border-gray-700">Tanggal Main</th>
                    <th class="py-3 px-4 border border-gray-700">Kode &amp; Pelanggan</th>
                    <th class="py-3 px-4 border border-gray-700">Lapangan &amp; Jam</th>
                    <th class="py-3 px-4 border border-gray-700 text-center">Durasi</th>
                    <th class="py-3 px-4 border border-gray-700 text-right">Tarif</th>
                    <th class="py-3 px-4 border border-gray-700 text-right">Subtotal</th>
                    <th class="py-3 px-4 border border-gray-700 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($reservasis as $key => $res)
                @php
                    $durasi = \Carbon\Carbon::parse($res->jam_selesai)->diffInHours(\Carbon\Carbon::parse($res->jam_mulai));
                    $subtotal = $durasi * ($res->lapangan->harga_per_jam ?? 0);
                    $isValidRevenue = in_array($res->status_reservasi, ['confirmed', 'completed']);
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 border border-gray-300 text-center">{{ $key + 1 }}</td>
                    <td class="py-3 px-4 border border-gray-300 font-medium">{{ \Carbon\Carbon::parse($res->tanggal_reservasi)->format('d/m/Y') }}</td>
                    <td class="py-3 px-4 border border-gray-300">
                        <span class="font-mono text-xs text-gray-500 block">{{ $res->kode_reservasi }}</span>
                        <span class="font-bold text-gray-800">{{ $res->pelanggan->nama_pelanggan }}</span>
                    </td>
                    <td class="py-3 px-4 border border-gray-300">
                        <span class="font-semibold text-gray-800 block">{{ $res->lapangan->nama_lapangan }}</span>
                        <span class="text-xs text-gray-600 font-mono">{{ substr($res->jam_mulai, 0, 5) }} - {{ substr($res->jam_selesai, 0, 5) }} WIB</span>
                    </td>
                    <td class="py-3 px-4 border border-gray-300 text-center font-mono">{{ $durasi }} Jam</td>
                    <td class="py-3 px-4 border border-gray-300 text-right font-mono">Rp {{ number_format($res->lapangan->harga_per_jam ?? 0, 0, ',', '.') }}</td>
                    <td class="py-3 px-4 border border-gray-300 text-right font-mono font-bold {{ $isValidRevenue ? 'text-gray-900' : 'text-gray-400 line-through' }}">
                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                    </td>
                    <td class="py-3 px-4 border border-gray-300 text-center">
                        <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $res->status_reservasi === 'confirmed' || $res->status_reservasi === 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                            {{ $res->status_label }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-8 text-center text-gray-500 font-medium border border-gray-300">
                        Tidak ada data transaksi pada periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($reservasis->count() > 0)
            <tfoot>
                <tr class="bg-gray-100 font-bold text-gray-900 border-t-2 border-gray-800">
                    <td colspan="4" class="py-3 px-4 border border-gray-300 text-right uppercase">Total Pemakaian &amp; Pendapatan:</td>
                    <td class="py-3 px-4 border border-gray-300 text-center font-mono">{{ $totalJam }} Jam</td>
                    <td class="py-3 px-4 border border-gray-300"></td>
                    <td class="py-3 px-4 border border-gray-300 text-right font-mono text-base">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                    <td class="py-3 px-4 border border-gray-300"></td>
                </tr>
            </tfoot>
            @endif
        </table>

        <!-- Sign / Approval Section -->
        <div class="mt-12 flex justify-end">
            <div class="text-center w-64">
                <p class="text-sm text-gray-600 mb-16">Kota Pusat, {{ date('d F Y') }}<br>Penanggung Jawab / Admin,</p>
                <p class="font-bold text-gray-900 underline">{{ auth()->user()->name ?? 'Administrator' }}</p>
                <p class="text-xs text-gray-500">SM Sport Center Management</p>
            </div>
        </div>
    </div>
</body>
</html>
