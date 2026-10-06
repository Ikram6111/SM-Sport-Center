@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-6" x-data="bookingForm()">
    <div class="bg-white p-8 rounded-lg border border-gray-200 shadow-sm relative">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Form Reservasi Lapangan</h1>
                <p class="text-xs text-gray-500 mt-1">Pilih jadwal main dan sistem akan memeriksa ketersediaan secara otomatis</p>
            </div>
            <a href="{{ route('reservasi.index') }}" class="p-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-900 transition-colors" title="Kembali">
                <i data-lucide="x" class="w-5 h-5"></i>
            </a>
        </div>

        <form action="{{ route('reservasi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            
            @if(auth()->user()->isAdmin())
            <div>
                <label for="pelanggan_id" class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Pelanggan Pemesan *</label>
                <select id="pelanggan_id" name="pelanggan_id" required
                    class="block w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-md text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach($pelanggans as $pel)
                    <option value="{{ $pel->id }}" {{ old('pelanggan_id') == $pel->id ? 'selected' : '' }}>
                        {{ $pel->nama_pelanggan }} ({{ $pel->nomor_telepon }})
                    </option>
                    @endforeach
                </select>
                <span class="text-xs text-gray-500 mt-1 block">Pelanggan belum ada? <a href="{{ route('admin.pelanggan.create') }}" class="text-blue-600 hover:underline font-medium">Tambah di sini</a>.</span>
            </div>
            @endif

            <div>
                <label for="lapangan_id" class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Lapangan *</label>
                <select id="lapangan_id" name="lapangan_id" required x-model="lapanganId" @change="calculateCost()"
                    class="block w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-md text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Lapangan --</option>
                    @foreach($lapangans as $lap)
                    <option value="{{ $lap->id }}" data-harga="{{ $lap->harga_per_jam }}" {{ old('lapangan_id', $selectedLapanganId) == $lap->id ? 'selected' : '' }}>
                        {{ $lap->nama_lapangan }} (Rp {{ number_format($lap->harga_per_jam, 0, ',', '.') }}/jam)
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="tanggal_reservasi" class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Main *</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </span>
                    <input type="date" id="tanggal_reservasi" name="tanggal_reservasi" required value="{{ old('tanggal_reservasi', $selectedTanggal) }}" min="{{ date('Y-m-d') }}"
                        class="block w-full pl-10 pr-3.5 py-2.5 bg-white border border-gray-300 rounded-md text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="jam_mulai" class="block text-sm font-medium text-gray-700 mb-1.5">Jam Mulai *</label>
                    <select id="jam_mulai" name="jam_mulai" required x-model="jamMulai" @change="calculateCost()"
                        class="block w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-md text-gray-900 text-sm font-mono focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Jam Mulai --</option>
                        @for($h = 8; $h < 22; $h++)
                        @php $val = sprintf('%02d:00', $h); @endphp
                        <option value="{{ $val }}" {{ old('jam_mulai', request('jam_mulai')) == $val ? 'selected' : '' }}>{{ $val }} WIB</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label for="jam_selesai" class="block text-sm font-medium text-gray-700 mb-1.5">Jam Selesai *</label>
                    <select id="jam_selesai" name="jam_selesai" required x-model="jamSelesai" @change="calculateCost()"
                        class="block w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-md text-gray-900 text-sm font-mono focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Jam Selesai --</option>
                        @for($h = 9; $h <= 22; $h++)
                        @php $val = sprintf('%02d:00', $h); @endphp
                        <option value="{{ $val }}" {{ old('jam_selesai', request('jam_selesai')) == $val ? 'selected' : '' }}>{{ $val }} WIB</option>
                        @endfor
                    </select>
                </div>
            </div>

            <!-- Estimasi Harga Card -->
            <div x-show="totalHours > 0" x-transition class="p-4 rounded-lg bg-blue-50 border border-blue-200 flex items-center justify-between" style="display: none;">
                <div>
                    <span class="text-xs font-semibold text-blue-800 uppercase block">Durasi &amp; Estimasi Total</span>
                    <span class="text-sm font-medium text-gray-700" x-text="totalHours + ' Jam Pemakaian'"></span>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-bold text-blue-700 font-mono" x-text="'Rp ' + totalCostFormatted"></span>
                </div>
            </div>

            <!-- Metode Pembayaran Section -->
            <div class="pt-4 border-t border-gray-200">
                <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Metode Pembayaran *</label>
                <select id="metode_pembayaran" name="metode_pembayaran" required x-model="metodePembayaran" @change="onMetodeChange($event)"
                    class="block w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-md text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="cash" {{ old('metode_pembayaran', 'cash') === 'cash' ? 'selected' : '' }}>Tunai (Cash di Tempat)</option>
                    <option value="qris" {{ old('metode_pembayaran') === 'qris' ? 'selected' : '' }}>QRIS (Transfer Instan)</option>
                </select>

                <!-- Instruksi Pembayaran Cash -->
                <div x-show="metodePembayaran === 'cash'" x-transition class="mt-4 p-4 rounded-lg bg-amber-50 border border-amber-200 flex items-start space-x-3" style="display: none;">
                    <i data-lucide="info" class="w-5 h-5 text-amber-600 shrink-0 mt-0.5"></i>
                    <div class="text-xs text-amber-900 space-y-1">
                        <span class="font-bold block text-sm">Instruksi Pembayaran Tunai / Di Tempat:</span>
                        <ul class="list-disc pl-4 space-y-1 text-amber-800">
                            <li>Silakan datang minimal <strong>15 menit</strong> sebelum jadwal bermain untuk melunasi pembayaran di kasir.</li>
                            <li>Status reservasi Anda saat ini akan tercatat sebagai <span class="font-semibold underline">Pending / Belum Bayar</span> hingga dikonfirmasi oleh petugas.</li>
                            <li>Jika tidak melakukan daftar ulang, pesanan dapat dibatalkan secara otomatis oleh sistem.</li>
                        </ul>
                    </div>
                </div>

                <!-- QRIS Barcode & Form Upload -->
                <div x-show="metodePembayaran === 'qris'" x-transition class="mt-4 p-5 rounded-lg bg-slate-50 border border-slate-200 space-y-4" style="display: none;">
                    <div class="p-4 bg-white rounded-lg border border-slate-200 flex flex-col items-center justify-center text-center">
                        <div class="w-44 h-44 bg-white p-2.5 rounded-lg border-2 border-dashed border-blue-300 flex items-center justify-center shadow-inner relative overflow-hidden">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=SM-SPORT-CENTER-PAYMENT-QRIS-STATIC&color=0f172a" 
                                 alt="Barcode QRIS Statis SM Sport Center" class="w-full h-full object-contain">
                        </div>
                        <span class="text-xs font-bold text-slate-800 uppercase tracking-wider block mt-2.5">QRIS SM SPORT CENTER</span>
                        <span class="text-[11px] text-slate-500 block font-mono">NMID: ID1029384756291</span>
                    </div>

                    <!-- Form Upload Bukti QRIS -->
                    <div>
                        <label for="bukti_qris" class="block text-sm font-semibold text-gray-800 mb-1.5">
                            Unggah Bukti Pembayaran QRIS <span class="text-rose-600">*</span>
                        </label>
                        <input type="file" id="bukti_qris" name="bukti_qris" accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf"
                               :required="metodePembayaran === 'qris'"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md bg-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('bukti_qris')
                        <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-gray-200">
                <a href="{{ route('ketersediaan.index') }}" class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium text-sm transition-colors border border-gray-300">
                    Lihat Timeline Jadwal
                </a>
                <button type="submit" class="px-6 py-2 rounded-md bg-green-600 hover:bg-green-700 text-white font-semibold text-sm shadow-sm transition-colors">
                    Konfirmasi Reservasi
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function bookingForm() {
        return {
            lapanganId: '{{ old('lapangan_id', $selectedLapanganId ?? '') }}',
            jamMulai: '{{ old('jam_mulai', request('jam_mulai') ?? '') }}',
            jamSelesai: '{{ old('jam_selesai', request('jam_selesai') ?? '') }}',
            metodePembayaran: '{{ old('metode_pembayaran', 'cash') }}',
            totalHours: 0,
            totalCost: 0,
            totalCostFormatted: '0',

            init() {
                this.calculateCost();
                const metodeSelect = document.getElementById('metode_pembayaran');
                if (metodeSelect) {
                    this.metodePembayaran = metodeSelect.value || this.metodePembayaran;
                    metodeSelect.addEventListener('change', (e) => {
                        this.metodePembayaran = e.target.value;
                    });
                }
            },

            onMetodeChange(event) {
                this.metodePembayaran = event.target.value;
            },

            calculateCost() {
                const select = document.getElementById('lapangan_id');
                if (!select || select.selectedIndex <= 0 || !this.jamMulai || !this.jamSelesai) {
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
