<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_reservasi',
        'pelanggan_id',
        'lapangan_id',
        'tanggal_reservasi',
        'jam_mulai',
        'jam_selesai',
        'status_reservasi',
        'metode_pembayaran',
        'bukti_qris',
        'status_pembayaran',
        'catatan',
    ];

    protected $casts = [
        'tanggal_reservasi' => 'date',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }

    /**
     * Logika inti validasi bentrok jadwal (Double Booking Prevention).
     * Memeriksa apakah terdapat reservasi lain pada lapangan dan tanggal yang sama
     * dengan rentang jam yang beririsan (overlap).
     *
     * Formula overlap interval: (startA < endB) AND (endA > startB)
     *
     * @param int $lapanganId
     * @param string $tanggal
     * @param string $jamMulai
     * @param string $jamSelesai
     * @param int|null $ignoreId ID reservasi yang diabaikan (saat edit reservasi)
     * @return bool True jika bentrok (sudah ada yang memesan), False jika aman.
     */
    public static function cekBentrok($lapanganId, $tanggal, $jamMulai, $jamSelesai, $ignoreId = null): bool
    {
        $query = self::where('lapangan_id', $lapanganId)
            ->where('tanggal_reservasi', $tanggal)
            ->where('status_reservasi', '!=', 'cancelled')
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->where('jam_mulai', '<', $jamSelesai)
                  ->where('jam_selesai', '>', $jamMulai);
            });

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }

    /**
     * Helper untuk mendapatkan warna badge status reservasi di tampilan.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status_reservasi) {
            'confirmed' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
            'completed' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
            'cancelled' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
            default => 'bg-amber-500/10 text-amber-400 border-amber-500/20', // pending
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status_reservasi) {
            'confirmed' => 'Dikonfirmasi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Menunggu Verifikasi', // pending
        };
    }

    /**
     * Helper untuk mendapatkan warna badge status pembayaran di tampilan.
     */
    public function getStatusPembayaranBadgeClassAttribute(): string
    {
        return match ($this->status_pembayaran) {
            'paid' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
            'failed' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
            'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
            default => 'bg-slate-500/10 text-slate-400 border-slate-500/20', // unpaid
        };
    }

    public function getStatusPembayaranLabelAttribute(): string
    {
        return match ($this->status_pembayaran) {
            'paid' => 'Lunas',
            'failed' => 'Gagal / Ditolak',
            'pending' => 'Verifikasi QRIS',
            default => 'Belum Bayar (Cash)', // unpaid
        };
    }

    public function getMetodePembayaranLabelAttribute(): string
    {
        return match ($this->metode_pembayaran) {
            'qris' => 'QRIS (Transfer)',
            default => 'Tunai / Cash di Tempat',
        };
    }
}
