@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 py-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('reservasi.index') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1.5"></i> Kembali ke Daftar Reservasi
        </a>
        <button onclick="window.print()" class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold inline-flex items-center border border-gray-300">
            <i data-lucide="printer" class="w-3.5 h-3.5 mr-1.5"></i> Cetak E-Ticket
        </button>
    </div>

    <!-- Ticket Card -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden relative" id="ticket-print">
        <!-- Ticket Header -->
        <div class="p-6 sm:p-8 bg-blue-600 border-b border-blue-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-white">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 rounded-lg bg-white/10 flex items-center justify-center font-bold text-white">
                    <i data-lucide="ticket" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-blue-100 uppercase tracking-wider">E-Ticket Reservasi #{{ $reservasi->id }}</span>
                    <h1 class="text-2xl font-bold text-white font-mono mt-0.5">{{ $reservasi->kode_reservasi }}</h1>
                </div>
            </div>

            <div class="text-left sm:text-right">
                <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $reservasi->status_badge_class }} inline-block mb-1 bg-white">
                    {{ $reservasi->status_label }}
                </span>
                <span class="block text-xs text-blue-100">Dipesan pd: {{ $reservasi->created_at->format('d/m/Y H:i') }} WIB</span>
            </div>
        </div>

        <!-- Ticket Body -->
        <div class="p-6 sm:p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-5 rounded-lg bg-gray-50 border border-gray-200">
                <div class="space-y-1">
                    <span class="text-xs text-gray-500 uppercase font-semibold">Informasi Pelanggan</span>
                    <p class="text-lg font-bold text-gray-900">{{ $reservasi->pelanggan->nama_pelanggan }}</p>
                    <p class="text-sm font-mono text-blue-600 font-medium"><i data-lucide="phone" class="w-3.5 h-3.5 inline mr-1"></i> {{ $reservasi->pelanggan->nomor_telepon }}</p>
                    <p class="text-xs text-gray-600">{{ $reservasi->pelanggan->alamat ?? '-' }}</p>
                </div>

                <div class="space-y-1 md:border-l md:border-gray-200 md:pl-6">
                    <span class="text-xs text-gray-500 uppercase font-semibold">Spesifikasi Lapangan</span>
                    <p class="text-lg font-bold text-gray-900">{{ $reservasi->lapangan->nama_lapangan }}</p>
                    <span class="px-2.5 py-0.5 rounded text-xs bg-blue-100 text-blue-800 font-semibold inline-block">
                        {{ $reservasi->lapangan->jenis_lapangan }}
                    </span>
                    <p class="text-xs text-gray-600 mt-1">Tarif: Rp {{ number_format($reservasi->lapangan->harga_per_jam, 0, ',', '.') }}/jam</p>
                </div>
            </div>

            <!-- Waktu & Biaya -->
            @php
                $start = \Carbon\Carbon::parse($reservasi->jam_mulai);
                $end = \Carbon\Carbon::parse($reservasi->jam_selesai);
                $durasi = $end->diffInHours($start);
                $totalHarga = $durasi * ($reservasi->lapangan->harga_per_jam ?? 0);
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                <div class="p-4 rounded-lg bg-gray-50 border border-gray-200">
                    <span class="text-xs text-gray-500 block mb-1">Tanggal Pertandingan</span>
                    <span class="text-base font-bold text-gray-900">{{ \Carbon\Carbon::parse($reservasi->tanggal_reservasi)->translatedFormat('l, d F Y') }}</span>
                </div>
                <div class="p-4 rounded-lg bg-gray-50 border border-gray-200">
                    <span class="text-xs text-gray-500 block mb-1">Jam Main (WIB)</span>
                    <span class="text-base font-bold text-green-700 font-mono">{{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }}</span>
                </div>
                <div class="p-4 rounded-lg bg-green-50 border border-green-200">
                    <span class="text-xs text-green-800 block mb-1">Total Biaya ({{ $durasi }} Jam)</span>
                    <span class="text-lg font-bold text-green-700 font-mono">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Informasi Pembayaran Card -->
            <div class="p-5 rounded-lg bg-slate-50 border border-slate-200 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center space-x-2">
                        <span class="text-xs text-gray-500 uppercase font-semibold">Metode &amp; Status Pembayaran</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $reservasi->status_pembayaran_badge_class }}">
                            {{ $reservasi->status_pembayaran_label }}
                        </span>
                    </div>
                    <p class="text-sm font-bold text-gray-900 flex items-center">
                        @if($reservasi->metode_pembayaran === 'qris')
                        <i data-lucide="qr-code" class="w-4 h-4 text-blue-600 mr-1.5"></i> QRIS (Transfer Instan)
                        @else
                        <i data-lucide="banknote" class="w-4 h-4 text-green-600 mr-1.5"></i> Tunai / Cash di Tempat
                        @endif
                    </p>
                    @if($reservasi->metode_pembayaran === 'cash')
                    <p class="text-xs text-gray-600">Silakan lakukan pembayaran langsung di meja kasir sebelum pertandingan dimulai.</p>
                    @elseif($reservasi->metode_pembayaran === 'qris' && $reservasi->status_pembayaran === 'pending')
                    <p class="text-xs text-amber-700 font-medium">Bukti pembayaran QRIS telah diunggah dan sedang menunggu verifikasi kasir/admin.</p>
                    @endif
                </div>

                @if($reservasi->bukti_qris)
                @php
                    $ext = strtolower(pathinfo($reservasi->bukti_qris, PATHINFO_EXTENSION));
                    $isPdf = $ext === 'pdf';
                    $fileUrl = asset('storage/' . $reservasi->bukti_qris);
                @endphp
                <div class="shrink-0 flex flex-col sm:flex-row items-center gap-2">
                    @if($isPdf)
                    <a href="{{ $fileUrl }}" target="_blank" 
                       class="inline-flex items-center px-4 py-2 rounded-md bg-white hover:bg-red-50 text-red-600 font-semibold text-xs border border-red-200 shadow-sm transition-colors">
                        <i data-lucide="file-text" class="w-4 h-4 mr-1.5 text-red-500"></i> Buka PDF di Tab Baru
                    </a>
                    @else
                    <a href="{{ $fileUrl }}" target="_blank" 
                       class="inline-flex items-center px-4 py-2 rounded-md bg-white hover:bg-blue-50 text-blue-600 font-semibold text-xs border border-blue-200 shadow-sm transition-colors">
                        <i data-lucide="image" class="w-4 h-4 mr-1.5 text-blue-500"></i> Lihat Gambar ({{ strtoupper($ext) }})
                    </a>
                    @endif
                </div>
                @endif
            </div>

            <!-- Preview Bukti Pembayaran (Khusus Admin / Kasir & Pelanggan) -->
            @if($reservasi->bukti_qris)
            @php
                $ext = strtolower(pathinfo($reservasi->bukti_qris, PATHINFO_EXTENSION));
                $isPdf = $ext === 'pdf';
                $fileUrl = asset('storage/' . $reservasi->bukti_qris);
            @endphp
            <div class="p-5 rounded-lg bg-white border border-gray-200 shadow-sm space-y-3">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <div class="flex items-center space-x-2">
                        <i data-lucide="file-check" class="w-4 h-4 text-blue-600"></i>
                        <span class="text-xs font-bold text-gray-800 uppercase tracking-wider">Preview Bukti Transfer ({{ strtoupper($ext) }})</span>
                    </div>
                    <a href="{{ $fileUrl }}" download class="text-xs font-semibold text-blue-600 hover:underline flex items-center">
                        <i data-lucide="download" class="w-3.5 h-3.5 mr-1"></i> Download File
                    </a>
                </div>

                <div class="bg-slate-50 p-3 rounded-md border border-slate-200 flex items-center justify-center overflow-hidden">
                    @if($isPdf)
                    <div class="w-full space-y-3">
                        <div class="p-4 bg-white rounded border border-gray-200 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded bg-red-100 flex items-center justify-center text-red-600 shrink-0">
                                    <i data-lucide="file-text" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <span class="text-sm font-bold text-gray-900 block">Bukti_Pembayaran_#{{ $reservasi->id }}.pdf</span>
                                    <span class="text-xs text-gray-500">Dokumen PDF bukti transaksi transfer QRIS</span>
                                </div>
                            </div>
                            <a href="{{ $fileUrl }}" target="_blank" class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs shadow-sm transition-colors">
                                Buka di Tab Baru
                            </a>
                        </div>
                        <!-- Tag Embed untuk preview langsung PDF -->
                        <div class="w-full h-[500px] bg-white rounded border border-gray-300 overflow-hidden shadow-inner">
                            <embed src="{{ $fileUrl }}" type="application/pdf" width="100%" height="100%" class="w-full h-full">
                        </div>
                    </div>
                    @else
                    <!-- Tag Img untuk preview langsung Gambar (JPG, JPEG, PNG) -->
                    <div class="text-center w-full">
                        <a href="{{ $fileUrl }}" target="_blank" title="Klik untuk perbesar">
                            <img src="{{ $fileUrl }}" alt="Bukti Transfer Reservasi #{{ $reservasi->id }}" 
                                 class="max-h-96 w-auto mx-auto rounded border border-gray-300 shadow-sm hover:opacity-95 transition-opacity object-contain">
                        </a>
                        <span class="text-[11px] text-gray-500 mt-2 block">Klik pada gambar untuk melihat ukuran penuh di tab baru</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Admin Action Bar -->
        @if(auth()->user()->isAdmin())
        <div class="p-6 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="text-xs text-gray-600 font-medium">Verifikasi atau ubah status reservasi ini:</span>
            <div class="flex flex-wrap items-center gap-2">
                @if($reservasi->status_pembayaran !== 'paid')
                <form method="POST" action="{{ route('admin.reservasi.status', $reservasi) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status_pembayaran" value="paid">
                    <input type="hidden" name="status_reservasi" value="confirmed">
                    <button type="submit" class="px-4 py-2 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-sm transition-all" title="Verifikasi bukti bayar dan aktifkan pesanan">
                        <i data-lucide="check-circle" class="w-3.5 h-3.5 inline mr-1 -mt-0.5"></i> Verifikasi Lunas &amp; Konfirmasi
                    </button>
                </form>
                @endif

                @if($reservasi->status_reservasi !== 'confirmed')
                <form method="POST" action="{{ route('admin.reservasi.status', $reservasi) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status_reservasi" value="confirmed">
                    <button type="submit" class="px-4 py-2 rounded-md bg-green-600 hover:bg-green-700 text-white text-xs font-semibold shadow-sm transition-all">
                        <i data-lucide="check" class="w-3.5 h-3.5 inline mr-1 -mt-0.5"></i> Konfirmasi Pesanan
                    </button>
                </form>
                @endif

                @if($reservasi->status_reservasi !== 'completed')
                <form method="POST" action="{{ route('admin.reservasi.status', $reservasi) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status_reservasi" value="completed">
                    <button type="submit" class="px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-sm transition-all">
                        <i data-lucide="flag" class="w-3.5 h-3.5 inline mr-1 -mt-0.5"></i> Tandai Selesai
                    </button>
                </form>
                @endif

                @if($reservasi->status_reservasi !== 'cancelled')
                <form method="POST" action="{{ route('admin.reservasi.status', $reservasi) }}" onsubmit="return confirm('Batalkan pesanan ini? Slot jam akan terbuka kembali untuk pelanggan lain.');">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status_reservasi" value="cancelled">
                    <button type="submit" class="px-4 py-2 rounded-md bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 text-xs font-semibold transition-all">
                        <i data-lucide="x" class="w-3.5 h-3.5 inline mr-1 -mt-0.5"></i> Batalkan
                    </button>
                </form>
                @endif

                <a href="{{ route('admin.reservasi.edit', $reservasi) }}" class="px-4 py-2 rounded-md bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 text-xs font-semibold transition-colors">
                    Edit Waktu / Data
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
