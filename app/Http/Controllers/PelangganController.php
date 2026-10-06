<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelanggan::with('user')->orderBy('nama_pelanggan', 'asc');

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%{$search}%")
                  ->orWhere('nomor_telepon', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        $pelanggans = $query->paginate(10)->withQueryString();

        return view('pelanggan.index', compact('pelanggans', 'search'));
    }

    public function create()
    {
        return view('pelanggan.form', [
            'pelanggan' => new Pelanggan(),
            'isEdit' => false
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan' => ['required', 'string', 'max:255'],
            'nomor_telepon' => ['required', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
        ], [
            'nama_pelanggan.required' => 'Nama pelanggan wajib diisi.',
            'nomor_telepon.required' => 'Nomor telepon kontak wajib diisi.',
        ]);

        Pelanggan::create($validated);

        return redirect()->route('admin.pelanggan.index')->with('success', 'Data pelanggan baru berhasil disimpan.');
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('pelanggan.form', [
            'pelanggan' => $pelanggan,
            'isEdit' => true
        ]);
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $validated = $request->validate([
            'nama_pelanggan' => ['required', 'string', 'max:255'],
            'nomor_telepon' => ['required', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
        ]);

        $pelanggan->update($validated);

        // Jika terhubung ke user akun, update nama & telepon di user juga agar sinkron
        if ($pelanggan->user) {
            $pelanggan->user->update([
                'name' => $validated['nama_pelanggan'],
                'phone' => $validated['nomor_telepon'],
            ]);
        }

        return redirect()->route('admin.pelanggan.index')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        if ($pelanggan->reservasis()->exists()) {
            return redirect()->route('admin.pelanggan.index')->with('error', 'Gagal menghapus pelanggan karena memiliki riwayat reservasi!');
        }

        $pelanggan->delete();

        return redirect()->route('admin.pelanggan.index')->with('success', 'Data pelanggan berhasil dihapus.');
    }
}
