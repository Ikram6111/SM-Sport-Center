<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\KetersediaanController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Rute aplikasi SM Sport Center Reservation System.
| Dikelompokkan menjadi Rute Publik, Guest (Auth), Pelanggan (Auth), dan Admin.
|
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

// Guest / Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes (Semua user login: Admin & Pelanggan)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dasbor Umum (Konten adaptif sesuai role di controller/view)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Cek Ketersediaan Lapangan (Bisa diakses oleh semua user login)
    Route::get('/ketersediaan', [KetersediaanController::class, 'index'])->name('ketersediaan.index');
    Route::get('/ketersediaan/data', [KetersediaanController::class, 'getData'])->name('ketersediaan.data');

    // Daftar Lapangan (Pelanggan bisa melihat daftar lapangan & status)
    Route::get('/lapangan', [LapanganController::class, 'index'])->name('lapangan.index');
    Route::get('/lapangan/{lapangan}', [LapanganController::class, 'show'])->name('lapangan.show');

    // Reservasi (Pelanggan membuat reservasi & melihat jadwal miliknya)
    Route::get('/reservasi', [ReservasiController::class, 'index'])->name('reservasi.index');
    Route::get('/reservasi/create', [ReservasiController::class, 'create'])->name('reservasi.create');
    Route::post('/reservasi', [ReservasiController::class, 'store'])->name('reservasi.store');
    Route::get('/reservasi/{reservasi}', [ReservasiController::class, 'show'])->name('reservasi.show');

    // Admin Only Routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        // Manajemen Lapangan (CRUD)
        Route::get('/lapangan/create', [LapanganController::class, 'create'])->name('lapangan.create');
        Route::post('/lapangan', [LapanganController::class, 'store'])->name('lapangan.store');
        Route::get('/lapangan/{lapangan}/edit', [LapanganController::class, 'edit'])->name('lapangan.edit');
        Route::put('/lapangan/{lapangan}', [LapanganController::class, 'update'])->name('lapangan.update');
        Route::delete('/lapangan/{lapangan}', [LapanganController::class, 'destroy'])->name('lapangan.destroy');

        // Manajemen Pelanggan (CRUD)
        Route::resource('pelanggan', PelangganController::class);

        // Manajemen Reservasi Admin (Edit, Hapus, Update Status)
        Route::get('/reservasi/{reservasi}/edit', [ReservasiController::class, 'edit'])->name('reservasi.edit');
        Route::put('/reservasi/{reservasi}', [ReservasiController::class, 'update'])->name('reservasi.update');
        Route::delete('/reservasi/{reservasi}', [ReservasiController::class, 'destroy'])->name('reservasi.destroy');
        Route::patch('/reservasi/{reservasi}/status', [ReservasiController::class, 'updateStatus'])->name('reservasi.status');

        // Laporan Penggunaan Lapangan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/print', [LaporanController::class, 'print'])->name('laporan.print');
    });
});
