<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lapangan;
use App\Models\Pelanggan;
use App\Models\Reservasi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = Carbon::today()->format('Y-m-d');

        if ($user->isAdmin()) {
            // Analitik Dasbor untuk Admin
            $totalLapangan = Lapangan::count();
            $lapanganTersedia = Lapangan::where('status', 'tersedia')->count();
            $totalPelanggan = Pelanggan::count();
            $reservasiHariIni = Reservasi::where('tanggal_reservasi', $today)->count();
            $pendingApproval = Reservasi::where('status_reservasi', 'pending')->count();

            // Jadwal hari ini
            $jadwalHariIni = Reservasi::with(['pelanggan', 'lapangan'])
                ->where('tanggal_reservasi', $today)
                ->orderBy('jam_mulai', 'asc')
                ->get();

            // Reservasi terbaru
            $reservasiTerbaru = Reservasi::with(['pelanggan', 'lapangan'])
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();

            return view('dashboard', compact(
                'totalLapangan',
                'lapanganTersedia',
                'totalPelanggan',
                'reservasiHariIni',
                'pendingApproval',
                'jadwalHariIni',
                'reservasiTerbaru'
            ));
        } else {
            // Analitik dan Jadwal untuk Pelanggan
            $pelanggan = $user->pelanggan;
            
            $reservasiSaya = collect();
            $reservasiTerdekat = null;
            $totalReservasiSaya = 0;

            if ($pelanggan) {
                $totalReservasiSaya = Reservasi::where('pelanggan_id', $pelanggan->id)->count();

                $reservasiSaya = Reservasi::with(['lapangan'])
                    ->where('pelanggan_id', $pelanggan->id)
                    ->orderBy('tanggal_reservasi', 'desc')
                    ->orderBy('jam_mulai', 'desc')
                    ->take(5)
                    ->get();

                // Cari reservasi mendatang terdekat yang sudah dikonfirmasi atau pending
                $reservasiTerdekat = Reservasi::with(['lapangan'])
                    ->where('pelanggan_id', $pelanggan->id)
                    ->where('tanggal_reservasi', '>=', $today)
                    ->whereIn('status_reservasi', ['confirmed', 'pending'])
                    ->orderBy('tanggal_reservasi', 'asc')
                    ->orderBy('jam_mulai', 'asc')
                    ->first();
            }

            // Lapangan rekomendasi
            $lapanganTersedia = Lapangan::where('status', 'tersedia')->take(3)->get();

            return view('dashboard', compact(
                'totalReservasiSaya',
                'reservasiSaya',
                'reservasiTerdekat',
                'lapanganTersedia'
            ));
        }
    }
}
