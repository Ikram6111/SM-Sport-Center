<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreReservasiRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Reservasi;
use App\Models\Lapangan;
use App\Models\Pelanggan;
use Carbon\Carbon;

class ReservasiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Reservasi::with(['pelanggan', 'lapangan'])->orderBy('tanggal_reservasi', 'desc')->orderBy('jam_mulai', 'desc');

        // Jika login sebagai pelanggan, hanya tampilkan reservasi miliknya
        if (!$user->isAdmin()) {
            if ($user->pelanggan) {
                $query->where('pelanggan_id', $user->pelanggan->id);
            } else {
                $query->whereRaw('1 = 0'); // Jika profil pelanggan belum ada, kosongkan
            }
        } else {
            // Filter khusus admin
            if ($status = $request->input('status')) {
                $query->where('status_reservasi', $status);
            }
            if ($lapanganId = $request->input('lapangan_id')) {
                $query->where('lapangan_id', $lapanganId);
            }
            if ($tanggal = $request->input('tanggal')) {
                $query->where('tanggal_reservasi', $tanggal);
            }
        }

        $reservasis = $query->paginate(15)->withQueryString();
        $lapangans = Lapangan::orderBy('nama_lapangan')->get();

        return view('reservasi.index', compact('reservasis', 'lapangans'));
    }

    public function create(Request $request)
    {
        $lapangans = Lapangan::where('status', 'tersedia')->orderBy('jenis_lapangan')->orderBy('nama_lapangan')->get();
        $selectedLapanganId = $request->input('lapangan_id');
        $selectedTanggal = $request->input('tanggal', Carbon::today()->format('Y-m-d'));

        // Admin bisa memilih pelanggan walk-in/terdaftar, Pelanggan otomatis dirinya sendiri
        $pelanggans = auth()->user()->isAdmin() ? Pelanggan::orderBy('nama_pelanggan')->get() : collect([auth()->user()->pelanggan]);

        return view('reservasi.create', compact('lapangans', 'selectedLapanganId', 'selectedTanggal', 'pelanggans'));
    }

    public function store(StoreReservasiRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        $pelangganId = $user->isAdmin() ? $validated['pelanggan_id'] : ($user->pelanggan->id ?? null);
        if (!$pelangganId) {
            return back()->withInput()->with('error', 'Profil pelanggan Anda tidak ditemukan. Silakan hubungi admin.');
        }

        // Format waktu untuk pengecekan SQL
        $jamMulai = $validated['jam_mulai'] . ':00';
        $jamSelesai = $validated['jam_selesai'] . ':00';

        // --- VALIDASI BISNIS INTI: CEK BENTROK JADWAL ---
        $isBentrok = Reservasi::cekBentrok($validated['lapangan_id'], $validated['tanggal_reservasi'], $jamMulai, $jamSelesai);

        if ($isBentrok) {
            return back()->withInput()->withErrors([
                'jam_mulai' => 'Jadwal bentrok! Lapangan ini sudah dipesan pada tanggal dan jam tersebut. Silakan pilih waktu lain.',
            ])->with('error', 'Gagal memesan! Jadwal bertabrakan dengan reservasi yang sudah ada.');
        }

        // Proses penyimpanan bukti QRIS ke storage publik Laravel jika memilih metode QRIS
        $buktiQrisPath = null;
        $statusPembayaran = 'unpaid';

        if ($validated['metode_pembayaran'] === 'qris' && $request->hasFile('bukti_qris')) {
            // Simpan file bukti PNG ke storage publik (storage/app/public/bukti_qris)
            $buktiQrisPath = $request->file('bukti_qris')->store('bukti_qris', 'public');
            $statusPembayaran = 'pending'; // Menunggu verifikasi pembayaran oleh admin
        } else {
            // Skenario Cash: status reservasi menjadi pending/confirmed dan status pembayaran unpaid
            $statusPembayaran = 'unpaid';
        }

        // Generate Kode Reservasi Unik
        $kode = 'RES-' . Carbon::parse($validated['tanggal_reservasi'])->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        $reservasi = Reservasi::create([
            'kode_reservasi' => $kode,
            'pelanggan_id' => $pelangganId,
            'lapangan_id' => $validated['lapangan_id'],
            'tanggal_reservasi' => $validated['tanggal_reservasi'],
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'status_reservasi' => $user->isAdmin() ? 'confirmed' : 'pending', // Admin otomatis confirmed, pelanggan pending
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'bukti_qris' => $buktiQrisPath,
            'status_pembayaran' => $user->isAdmin() ? 'paid' : $statusPembayaran,
            'catatan' => $validated['catatan'] ?? '-',
        ]);

        return redirect()->route('reservasi.show', $reservasi)->with('success', 'Reservasi berhasil dibuat dengan kode: ' . $kode);
    }

    public function show(Reservasi $reservasi)
    {
        // Pastikan pelanggan hanya bisa melihat reservasi miliknya
        if (!auth()->user()->isAdmin() && $reservasi->pelanggan_id !== auth()->user()->pelanggan?->id) {
            abort(403, 'Anda tidak berhak mengakses detail reservasi ini.');
        }

        return view('reservasi.show', compact('reservasi'));
    }

    public function edit(Reservasi $reservasi)
    {
        $lapangans = Lapangan::where('status', 'tersedia')->orderBy('nama_lapangan')->get();
        $pelanggans = Pelanggan::orderBy('nama_pelanggan')->get();

        return view('reservasi.edit', compact('reservasi', 'lapangans', 'pelanggans'));
    }

    public function update(Request $request, Reservasi $reservasi)
    {
        $validated = $request->validate([
            'pelanggan_id' => ['required', 'exists:pelanggans,id'],
            'lapangan_id' => ['required', 'exists:lapangans,id'],
            'tanggal_reservasi' => ['required', 'date'],
            'jam_mulai' => ['required', 'date_format:H:i,H:i:s'],
            'jam_selesai' => ['required', 'date_format:H:i,H:i:s', 'after:jam_mulai'],
            'status_reservasi' => ['required', 'in:pending,confirmed,cancelled,completed'],
            'metode_pembayaran' => ['required', 'in:cash,qris'],
            'status_pembayaran' => ['required', 'in:unpaid,pending,paid,failed'],
            'bukti_qris' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $jamMulai = strlen($validated['jam_mulai']) === 5 ? $validated['jam_mulai'] . ':00' : $validated['jam_mulai'];
        $jamSelesai = strlen($validated['jam_selesai']) === 5 ? $validated['jam_selesai'] . ':00' : $validated['jam_selesai'];

        // --- VALIDASI BISNIS INTI: CEK BENTROK JADWAL SAAT EDIT (abaikan ID sendiri) ---
        if ($validated['status_reservasi'] !== 'cancelled') {
            $isBentrok = Reservasi::cekBentrok($validated['lapangan_id'], $validated['tanggal_reservasi'], $jamMulai, $jamSelesai, $reservasi->id);

            if ($isBentrok) {
                return back()->withInput()->withErrors([
                    'jam_mulai' => 'Jadwal bentrok! Perubahan waktu ini bertabrakan dengan reservasi lain.',
                ])->with('error', 'Gagal memperbarui! Jadwal yang dipilih sudah terisi oleh pelanggan lain.');
            }
        }

        $updateData = [
            'pelanggan_id' => $validated['pelanggan_id'],
            'lapangan_id' => $validated['lapangan_id'],
            'tanggal_reservasi' => $validated['tanggal_reservasi'],
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'status_reservasi' => $validated['status_reservasi'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'status_pembayaran' => $validated['status_pembayaran'],
            'catatan' => $validated['catatan'] ?? '-',
        ];

        if ($request->hasFile('bukti_qris')) {
            if ($reservasi->bukti_qris && Storage::disk('public')->exists($reservasi->bukti_qris)) {
                Storage::disk('public')->delete($reservasi->bukti_qris);
            }
            $updateData['bukti_qris'] = $request->file('bukti_qris')->store('bukti_qris', 'public');
        }

        $reservasi->update($updateData);

        return redirect()->route('reservasi.show', $reservasi)->with('success', 'Data reservasi berhasil diperbarui.');
    }

    public function destroy(Reservasi $reservasi)
    {
        if ($reservasi->bukti_qris && Storage::disk('public')->exists($reservasi->bukti_qris)) {
            Storage::disk('public')->delete($reservasi->bukti_qris);
        }
        $reservasi->delete();
        return redirect()->route('reservasi.index')->with('success', 'Data reservasi berhasil dihapus.');
    }

    public function updateStatus(Request $request, Reservasi $reservasi)
    {
        $request->validate([
            'status_reservasi' => ['nullable', 'in:pending,confirmed,cancelled,completed'],
            'status_pembayaran' => ['nullable', 'in:unpaid,pending,paid,failed'],
        ]);

        // Jika mengubah dari cancelled ke confirmed, cek bentrok dulu
        if ($request->filled('status_reservasi') && $reservasi->status_reservasi === 'cancelled' && $request->status_reservasi !== 'cancelled') {
            $isBentrok = Reservasi::cekBentrok($reservasi->lapangan_id, $reservasi->tanggal_reservasi->format('Y-m-d'), $reservasi->jam_mulai, $reservasi->jam_selesai, $reservasi->id);
            if ($isBentrok) {
                return back()->with('error', 'Gagal mengaktifkan kembali reservasi! Slot waktu sudah terisi oleh pesanan lain.');
            }
        }

        $updateData = [];
        if ($request->filled('status_reservasi')) {
            $updateData['status_reservasi'] = $request->status_reservasi;
        }
        if ($request->filled('status_pembayaran')) {
            $updateData['status_pembayaran'] = $request->status_pembayaran;
        }

        if (!empty($updateData)) {
            $reservasi->update($updateData);
        }

        return back()->with('success', 'Status reservasi berhasil diperbarui.');
    }
}
