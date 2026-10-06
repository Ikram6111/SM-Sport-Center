@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-6" x-data="bookingEditForm()">
    <div class="bg-white p-8 rounded-lg border border-gray-200 shadow-sm relative">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Data Reservasi #{{ $reservasi->id }}</h1>
                <p class="text-xs text-gray-500 mt-1">Ubah jadwal, lapangan, atau status pesanan</p>
            </div>
            <a href="{{ route('reservasi.show', $reservasi) }}" class="p-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-900 transition-colors" title="Kembali">
                <i data-lucide="x" class="w-5 h-5"></i>
            </a>
        </div>

        <form action="{{ route('admin.reservasi.update', $reservasi) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="pelanggan_id" class="block text-sm font-medium text-gray-700 mb-1.5">Pelanggan Pemesan *</label>
                <select id="pelanggan_id" name="pelanggan_id" required
                    class="block w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-md text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @foreach($pelanggans as $pel)
                    <option value="{{ $pel->id }}" {{ old('pelanggan_id', $reservasi->pelanggan_id) == $pel->id ? 'selected' : '' }}>
                        {{ $pel->nama_pelanggan }} ({{ $pel->nomor_telepon }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="lapangan_id" class="block text-sm font-medium text-gray-700 mb-1.5">Lapangan *</label>
                <select id="lapangan_id" name="lapangan_id" required x-model="lapanganId" @change="calculateCost()"
                    class="block w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-md text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @foreach($lapangans as $lap)
                    <option value="{{ $lap->id }}" data-harga="{{ $lap->harga_per_jam }}" {{ old('lapangan_id', $reservasi->lapangan_id) == $lap->id ? 'selected' : '' }}>
                        {{ $lap->nama_lapangan }} (Rp {{ number_format($lap->harga_per_jam, 0, ',', '.') }}/jam)
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="tanggal_reservasi" class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Main *</label>
                <input type="date" id="tanggal_reservasi" name="tanggal_reservasi" required value="{{ old('tanggal_reservasi', $reservasi->tanggal_reservasi->format('Y-m-d')) }}"
                    class="block w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-md text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="jam_mulai" class="block text-sm font-medium text-gray-700 mb-1.5">Jam Mulai *</label>
                    <select id="jam_mulai" name="jam_mulai" required x-model="jamMulai" @change="calculateCost()"
                        class="block w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-md text-gray-900 text-sm font-mono focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @for($h = 8; $h < 22; $h++)
                        @php $val = sprintf('%02d:00', $h); @endphp
                        <option value="{{ $val }}" {{ old('jam_mulai', substr($reservasi->jam_mulai, 0, 5)) == $val ? 'selected' : '' }}>{{ $val }} WIB</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label for="jam_selesai" class="block text-sm font-medium text-gray-700 mb-1.5">Jam Selesai *</label>
                    <select id="jam_selesai" name="jam_selesai" required x-model="jamSelesai" @change="calculateCost()"
                        class="block w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-md text-gray-900 text-sm font-mono focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @for($h = 9; $h <= 22; $h++)
                        @php $val = sprintf('%02d:00', $h); @endphp
                        <option value="{{ $val }}" {{ old('jam_selesai', substr($reservasi->jam_selesai, 0, 5)) == $val ? 'selected' : '' }}>{{ $val }} WIB</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div>
                <label for="status_reservasi" class="block text-sm font-medium text-gray-700 mb-1.5">Status Reservasi *</label>
                <select id="status_reservasi" name="status_reservasi" required
                    class="block w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-md text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="pending" {{ old('status_reservasi', $reservasi->status_reservasi) === 'pending' ? 'selected' : '' }}>Pending (Menunggu Verifikasi)</option>
                    <option value="confirmed" {{ old('status_reservasi', $reservasi->status_reservasi) === 'confirmed' ? 'selected' : '' }}>Confirmed (Dikonfirmasi / Aktif)</option>
                    <option value="completed" {{ old('status_reservasi', $reservasi->status_reservasi) === 'completed' ? 'selected' : '' }}>Completed (Selesai Bermain)</option>
                    <option value="cancelled" {{ old('status_reservasi', $reservasi->status_reservasi) === 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 mb-1.5">Metode Pembayaran *</label>
                    <select id="metode_pembayaran" name="metode_pembayaran" required
                        class="block w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-md text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="cash" {{ old('metode_pembayaran', $reservasi->metode_pembayaran) === 'cash' ? 'selected' : '' }}>Tunai / Cash di Tempat</option>
                        <option value="qris" {{ old('metode_pembayaran', $reservasi->metode_pembayaran) === 'qris' ? 'selected' : '' }}>QRIS (Transfer Instan)</option>
                    </select>
                </div>

                <div>
                    <label for="status_pembayaran" class="block text-sm font-medium text-gray-700 mb-1.5">Status Pembayaran *</label>
                    <select id="status_pembayaran" name="status_pembayaran" required
                        class="block w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-md text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="unpaid" {{ old('status_pembayaran', $reservasi->status_pembayaran) === 'unpaid' ? 'selected' : '' }}>Belum Bayar (Unpaid)</option>
                        <option value="pending" {{ old('status_pembayaran', $reservasi->status_pembayaran) === 'pending' ? 'selected' : '' }}>Verifikasi QRIS (Pending)</option>
                        <option value="paid" {{ old('status_pembayaran', $reservasi->status_pembayaran) === 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                        <option value="failed" {{ old('status_pembayaran', $reservasi->status_pembayaran) === 'failed' ? 'selected' : '' }}>Gagal / Ditolak (Failed)</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="bukti_qris" class="block text-sm font-medium text-gray-700 mb-1.5">Perbarui Bukti QRIS (Opsi)</label>
                <input type="file" id="bukti_qris" name="bukti_qris" accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md bg-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500">
                @if($reservasi->bukti_qris)
                <span class="text-xs text-gray-500 mt-1 block">Saat ini: <a href="{{ asset('storage/' . $reservasi->bukti_qris) }}" target="_blank" class="text-blue-600 underline">Lihat file bukti</a></span>
                @endif
            </div>

            <div x-show="totalHours > 0" x-transition class="p-4 rounded-lg bg-blue-50 border border-blue-200 flex items-center justify-between" style="display: none;">
                <div>
                    <span class="text-xs font-semibold text-blue-800 uppercase block">Durasi &amp; Estimasi Total</span>
                    <span class="text-sm font-medium text-gray-700" x-text="totalHours + ' Jam Pemakaian'"></span>
                </div>
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-gray-200">
                <a href="{{ route('reservasi.show', $reservasi) }}" class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium text-sm transition-colors border border-gray-300">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function bookingEditForm() {
        return {
            lapanganId: '{{ old('lapangan_id', $reservasi->lapangan_id) }}',
            jamMulai: '{{ old('jam_mulai', substr($reservasi->jam_mulai, 0, 5)) }}',
            jamSelesai: '{{ old('jam_selesai', substr($reservasi->jam_selesai, 0, 5)) }}',
            totalHours: 0,
            totalCost: 0,
            totalCostFormatted: '0',

            init() {
                this.calculateCost();
            },

            calculateCost() {
                const select = document.getElementById('lapangan_id');
                if (!select || select.selectedIndex < 0 || !this.jamMulai || !this.jamSelesai) {
                    this.totalHours = 0;
                    return;
                }

                const option = select.options[select.selectedIndex];
                const harga = parseInt(option.getAttribute('data-harga')) || 0;

                const startH = parseInt(this.jamMulai.split(':')[0]);
                const endH = parseInt(this.jamSelesai.split(':')[0]);

                if (endH > startH) {
                    this.totalHours = endH - startH;
                    this.totalCost = this.totalHours * harga;
                    this.totalCostFormatted = new Intl.NumberFormat('id-ID').format(this.totalCost);
                } else {
                    this.totalHours = 0;
                }
            }
        }
    }
</script>
@endpush
@endsection
