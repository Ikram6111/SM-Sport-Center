<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use App\Models\Lapangan;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::today()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::today()->endOfMonth()->format('Y-m-d'));
        $lapanganId = $request->input('lapangan_id');
        $status = $request->input('status');

        $query = Reservasi::with(['pelanggan', 'lapangan'])
            ->whereBetween('tanggal_reservasi', [$startDate, $endDate])
            ->orderBy('tanggal_reservasi', 'desc')
            ->orderBy('jam_mulai', 'desc');

        if ($lapanganId) {
            $query->where('lapangan_id', $lapanganId);
        }
        if ($status) {
            $query->where('status_reservasi', $status);
        }

        $reservasis = $query->get();
        $lapangans = Lapangan::orderBy('nama_lapangan')->get();

        // Ringkasan Statistik Laporan
        $totalTransaksi = $reservasis->count();
        $totalSelesai = $reservasis->where('status_reservasi', 'completed')->count();
        
        $totalJam = 0;
        $estimasiPendapatan = 0;

        foreach ($reservasis as $res) {
            if (in_array($res->status_reservasi, ['confirmed', 'completed'])) {
                $start = Carbon::parse($res->jam_mulai);
                $end = Carbon::parse($res->jam_selesai);
                $durasi = $end->diffInHours($start);
                
                $totalJam += $durasi;
                $estimasiPendapatan += ($durasi * ($res->lapangan->harga_per_jam ?? 0));
            }
        }

        return view('laporan.index', compact(
            'reservasis',
            'lapangans',
            'startDate',
            'endDate',
            'lapanganId',
            'status',
            'totalTransaksi',
            'totalSelesai',
            'totalJam',
            'estimasiPendapatan'
        ));
    }

    public function print(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::today()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::today()->endOfMonth()->format('Y-m-d'));
        $lapanganId = $request->input('lapangan_id');
        $status = $request->input('status');

        $query = Reservasi::with(['pelanggan', 'lapangan'])
            ->whereBetween('tanggal_reservasi', [$startDate, $endDate])
            ->orderBy('tanggal_reservasi', 'asc')
            ->orderBy('jam_mulai', 'asc');

        if ($lapanganId) {
            $query->where('lapangan_id', $lapanganId);
        }
        if ($status) {
            $query->where('status_reservasi', $status);
        }

        $reservasis = $query->get();
        $lapanganFilter = $lapanganId ? Lapangan::find($lapanganId)?->nama_lapangan : 'Semua Lapangan';

        // Hitung total jam dan pendapatan
        $totalJam = 0;
        $totalPendapatan = 0;

        foreach ($reservasis as $res) {
            if (in_array($res->status_reservasi, ['confirmed', 'completed'])) {
                $durasi = Carbon::parse($res->jam_selesai)->diffInHours(Carbon::parse($res->jam_mulai));
                $totalJam += $durasi;
                $totalPendapatan += ($durasi * ($res->lapangan->harga_per_jam ?? 0));
            }
        }

        return view('laporan.print', compact(
            'reservasis',
            'startDate',
            'endDate',
            'lapanganFilter',
            'status',
            'totalJam',
            'totalPendapatan'
        ));
    }
}
