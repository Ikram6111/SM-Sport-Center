<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lapangan;

class LapanganController extends Controller
{
    public function index()
    {
        $lapangans = Lapangan::orderBy('jenis_lapangan', 'asc')->orderBy('nama_lapangan', 'asc')->get();
        return view('lapangan.index', compact('lapangans'));
    }

    public function show(Lapangan $lapangan)
    {
        return view('lapangan.show', compact('lapangan'));
    }

    public function create()
    {
        return view('lapangan.form', [
            'lapangan' => new Lapangan(),
            'isEdit' => false
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lapangan' => ['required', 'string', 'max:255'],
            'jenis_lapangan' => ['required', 'in:Futsal,Badminton'],
            'status' => ['required', 'in:tersedia,pemeliharaan,tidak_tersedia'],
            'harga_per_jam' => ['required', 'numeric', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
        ], [
            'nama_lapangan.required' => 'Nama lapangan wajib diisi.',
            'jenis_lapangan.required' => 'Pilih jenis lapangan.',
            'status.required' => 'Pilih status ketersediaan lapangan.',
            'harga_per_jam.required' => 'Harga sewa per jam wajib diisi.',
        ]);

        Lapangan::create($validated);

        return redirect()->route('lapangan.index')->with('success', 'Data lapangan baru berhasil ditambahkan.');
    }

    public function edit(Lapangan $lapangan)
    {
        return view('lapangan.form', [
            'lapangan' => $lapangan,
            'isEdit' => true
        ]);
    }

    public function update(Request $request, Lapangan $lapangan)
    {
        $validated = $request->validate([
            'nama_lapangan' => ['required', 'string', 'max:255'],
            'jenis_lapangan' => ['required', 'in:Futsal,Badminton'],
            'status' => ['required', 'in:tersedia,pemeliharaan,tidak_tersedia'],
            'harga_per_jam' => ['required', 'numeric', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $lapangan->update($validated);

        return redirect()->route('lapangan.index')->with('success', 'Data lapangan berhasil diperbarui.');
    }

    public function destroy(Lapangan $lapangan)
    {
        if ($lapangan->reservasis()->exists()) {
            return redirect()->route('lapangan.index')->with('error', 'Gagal menghapus lapangan karena memiliki riwayat reservasi. Ubah status menjadi Tidak Tersedia atau Pemeliharaan jika ingin menonaktifkannya.');
        }

        $lapangan->delete();

        return redirect()->route('lapangan.index')->with('success', 'Data lapangan berhasil dihapus.');
    }
}
