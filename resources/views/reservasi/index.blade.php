@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">
                {{ auth()->user()->isAdmin() ? 'Semua Data Reservasi' : 'Riwayat Reservasi Saya' }}
            </h1>
            <p class="text-sm text-gray-600 mt-1">
                {{ auth()->user()->isAdmin() ? 'Pantau, verifikasi, dan kelola seluruh jadwal reservasi lapangan di SM Sport Center' : 'Daftar tiket pemesanan lapangan dan status verifikasimu' }}
            </p>
        </div>
        <div>
            <a href="{{ route('reservasi.create') }}" class="px-4 py-2.5 rounded-md bg-green-600 hover:bg-green-700 text-white font-medium text-sm shadow-sm transition-colors inline-flex items-center">
                <i data-lucide="plus-circle" class="w-4 h-4 mr-2"></i> Booking Baru
            </a>
        </div>
    </div>

    @if(auth()->user()->isAdmin())
    <!-- Filter Bar Admin -->
    <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
        <form method="GET" action="{{ route('reservasi.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <div>
                <label for="status" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Filter Status</label>
                <select id="status" name="status"
                    class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Menunggu Verifikasi)</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed (Dikonfirmasi)</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                </select>
            </div>

            <div>
                <label for="lapangan_id" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Filter Lapangan</label>
                <select id="lapangan_id" name="lapangan_id"
                    class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Lapangan</option>
                    @foreach($lapangans as $lap)
                    <option value="{{ $lap->id }}" {{ request('lapangan_id') == $lap->id ? 'selected' : '' }}>{{ $lap->nama_lapangan }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="tanggal" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Filter Tanggal Main</label>
                <input type="date" id="tanggal" name="tanggal" value="{{ request('tanggal') }}"
                    class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="flex-1 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm transition-colors flex items-center justify-center">
                    Filter
                </button>
                @if(request()->hasAny(['status', 'lapangan_id', 'tanggal']))
                <a href="{{ route('reservasi.index') }}" class="px-3.5 py-2 rounded-md bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-900 text-sm transition-colors flex items-center justify-center border border-gray-200">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>
    @endif

    <!-- Reservasi Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-blue-600 text-white text-xs uppercase font-semibold">
                        <th class="py-3 px-4">Kode &amp; Tanggal</th>
                        <th class="py-3 px-4">Pelanggan</th>
                        <th class="py-3 px-4">Lapangan &amp; Waktu</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm bg-white">
                    @forelse($reservasis as $res)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4">
                            <span class="font-mono font-bold text-blue-600 block">{{ $res->kode_reservasi }}</span>
                            <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($res->tanggal_reservasi)->translatedFormat('d M Y') }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="font-bold text-gray-900 block">{{ $res->pelanggan->nama_pelanggan }}</span>
                            <span class="text-xs font-mono text-gray-500">{{ $res->pelanggan->nomor_telepon }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="font-medium text-gray-900 block">{{ $res->lapangan->nama_lapangan }}</span>
                            <span class="text-xs font-mono text-green-700 font-semibold">{{ substr($res->jam_mulai, 0, 5) }} - {{ substr($res->jam_selesai, 0, 5) }} WIB</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold border {{ $res->status_badge_class }} inline-block">
                                {{ $res->status_label }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right space-x-1">
                            <a href="{{ route('reservasi.show', $res) }}" class="p-1.5 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-300 inline-block transition-colors" title="Lihat Detail &amp; Tiket">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>

                            @if(auth()->user()->isAdmin())
                                @if($res->status_reservasi === 'pending')
                                <form method="POST" action="{{ route('admin.reservasi.status', $res) }}" class="inline-block" onsubmit="return confirm('Konfirmasi pesanan ini?');">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status_reservasi" value="confirmed">
                                    <button type="submit" class="p-1.5 rounded-md bg-green-50 hover:bg-green-100 text-green-700 border border-green-200 transition-colors" title="Konfirmasi Reservasi">
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                @endif

                                @if($res->status_reservasi === 'confirmed')
                                <form method="POST" action="{{ route('admin.reservasi.status', $res) }}" class="inline-block" onsubmit="return confirm('Tandai pesanan selesai?');">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status_reservasi" value="completed">
                                    <button type="submit" class="p-1.5 rounded-md bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 transition-colors" title="Tandai Selesai">
                                        <i data-lucide="flag" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                @endif

                                <a href="{{ route('admin.reservasi.edit', $res) }}" class="p-1.5 rounded-md bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 inline-block transition-colors" title="Edit Jadwal">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>

                                <form method="POST" action="{{ route('admin.reservasi.destroy', $res) }}" class="inline-block" onsubmit="return confirm('Hapus permanen reservasi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-md bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 transition-colors" title="Hapus Permanen">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-gray-500">
                            <i data-lucide="calendar-x" class="w-12 h-12 mx-auto mb-3 opacity-40 text-gray-400"></i>
                            <p class="text-base font-medium text-gray-700">Belum ada data reservasi</p>
                            <p class="text-xs text-gray-500 mt-1">Silakan lakukan pemesanan lapangan melalui tombol Booking Baru di atas.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reservasis->hasPages())
        <div class="p-4 border-t border-gray-200 bg-gray-50">
            {{ $reservasis->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
