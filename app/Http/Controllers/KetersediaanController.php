<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lapangan;
use App\Models\Reservasi;
use Carbon\Carbon;

class KetersediaanController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal', Carbon::today()->format('Y-m-d'));
        $jenis = $request->input('jenis', 'Semua'); // 'Semua', 'Futsal', 'Badminton'

        $query = Lapangan::where('status', 'tersedia')->orderBy('jenis_lapangan')->orderBy('nama_lapangan');
        if ($jenis !== 'Semua') {
            $query->where('jenis_lapangan', $jenis);
        }
        $lapangans = $query->get();

        // Ambil semua reservasi aktif (confirmed, pending) pada tanggal tersebut
        $reservasis = Reservasi::with('pelanggan')
            ->where('tanggal_reservasi', $tanggal)
            ->whereIn('status_reservasi', ['confirmed', 'pending'])
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('lapangan_id');

        // Susun struktur slot waktu standar operasional dari jam 08:00 sampai 22:00
        $operasionalHours = [];
        for ($h = 8; $h < 22; $h++) {
            $start = sprintf('%02d:00', $h);
            $end = sprintf('%02d:00', $h + 1);
            $operasionalHours[] = [
                'label' => "{$start} - {$end}",
                'start' => "{$start}:00",
                'end' => "{$end}:00",
            ];
        }

        return view('ketersediaan.index', compact('tanggal', 'jenis', 'lapangans', 'reservasis', 'operasionalHours'));
    }

    public function getData(Request $request)
    {
        $tanggal = $request->input('tanggal', Carbon::today()->format('Y-m-d'));
        $lapanganId = $request->input('lapangan_id');

        $reservasis = Reservasi::where('tanggal_reservasi', $tanggal)
            ->whereIn('status_reservasi', ['confirmed', 'pending'])
            ->when($lapanganId, fn($q) => $q->where('lapangan_id', $lapanganId))
            ->orderBy('jam_mulai')
            ->get(['id', 'lapangan_id', 'jam_mulai', 'jam_selesai', 'status_reservasi']);

        return response()->json([
            'tanggal' => $tanggal,
            'reservasis' => $reservasis
        ]);
    }
}
