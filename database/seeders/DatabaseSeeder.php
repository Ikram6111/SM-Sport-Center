<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Lapangan;
use App\Models\Reservasi;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin
        User::create([
            'name' => 'Administrator SM Sport',
            'email' => 'admin@smsport.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        // 2. Akun Pelanggan 1
        $userBudi = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
            'phone' => '081122334455',
        ]);

        $pelangganBudi = Pelanggan::create([
            'user_id' => $userBudi->id,
            'nama_pelanggan' => 'Budi Santoso',
            'nomor_telepon' => '081122334455',
            'alamat' => 'Jl. Sudirman No. 10, Jakarta Selatan',
        ]);

        //s 3. Akun Pelanggan 2
        $userSiti = User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
            'phone' => '081988776655',
        ]);

        $pelangganSiti = Pelanggan::create([
            'user_id' => $userSiti->id,
            'nama_pelanggan' => 'Siti Aminah',
            'nomor_telepon' => '081988776655',
            'alamat' => 'Jl. Kebon Jeruk No. 25, Jakarta Barat',
        ]);

        // 4. Data Pelanggan Walk-In (tanpa akun user online)
        $pelangganWalkIn = Pelanggan::create([
            'user_id' => null,
            'nama_pelanggan' => 'Klub Badminton Mandiri',
            'nomor_telepon' => '081345678901',
            'alamat' => 'Gedung Serbaguna Kemayoran, Jakarta Pusat',
        ]);

        // 5. Data Lapangan (2 Futsal & 3 Badminton sesuai PRD)
        $futsalA = Lapangan::create([
            'nama_lapangan' => 'Lapangan Futsal A (Rumput Sintetis)',
            'jenis_lapangan' => 'Futsal',
            'status' => 'tersedia',
            'deskripsi' => 'Lapangan futsal berstandar nasional dengan rumput sintetis impor premium, pencahayaan LED terang, dan sirkulasi udara optimal.',
            'harga_per_jam' => 150000,
        ]);

        $futsalB = Lapangan::create([
            'nama_lapangan' => 'Lapangan Futsal B (Vinyl Interlock)',
            'jenis_lapangan' => 'Futsal',
            'status' => 'tersedia',
            'deskripsi' => 'Lapangan futsal dengan lantai vinyl interlock anti-slip berstandar turnamen, cocok untuk pertandingan profesional maupun kasual.',
            'harga_per_jam' => 130000,
        ]);

        $badminton1 = Lapangan::create([
            'nama_lapangan' => 'Lapangan Badminton 1 (Karpet Karbon)',
            'jenis_lapangan' => 'Badminton',
            'status' => 'tersedia',
            'deskripsi' => 'Lapangan bulu tangkis bertaraf BWF dengan karpet karbon berdaya cengkeram tinggi untuk kenyamanan maksimal pergerakan kaki.',
            'harga_per_jam' => 60000,
        ]);

        $badminton2 = Lapangan::create([
            'nama_lapangan' => 'Lapangan Badminton 2 (Karpet Karbon)',
            'jenis_lapangan' => 'Badminton',
            'status' => 'tersedia',
            'deskripsi' => 'Lapangan badminton karpet karbon berkualitas dengan pencahayaan samping anti-silau dan jarak pinggir yang aman.',
            'harga_per_jam' => 60000,
        ]);

        $badminton3 = Lapangan::create([
            'nama_lapangan' => 'Lapangan Badminton 3 (Kayu/Parquet)',
            'jenis_lapangan' => 'Badminton',
            'status' => 'tersedia',
            'deskripsi' => 'Lapangan badminton dengan lantai kayu parquet klasik yang menyerap benturan, cocok untuk latihan rutin dan turnamen klub.',
            'harga_per_jam' => 50000,
        ]);

        // 6. Simulasi Data Reservasi
        $today = Carbon::today()->format('Y-m-d');
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');

        // Reservasi kemarin (selesai)
        Reservasi::create([
            'kode_reservasi' => 'RES-' . Carbon::yesterday()->format('Ymd') . '-001',
            'pelanggan_id' => $pelangganBudi->id,
            'lapangan_id' => $futsalA->id,
            'tanggal_reservasi' => $yesterday,
            'jam_mulai' => '16:00:00',
            'jam_selesai' => '18:00:00',
            'status_reservasi' => 'completed',
            'catatan' => 'Latihan rutin sore Futsal Budi CS',
        ]);

        // Reservasi hari ini (berbagai status)
        Reservasi::create([
            'kode_reservasi' => 'RES-' . Carbon::today()->format('Ymd') . '-001',
            'pelanggan_id' => $pelangganSiti->id,
            'lapangan_id' => $badminton1->id,
            'tanggal_reservasi' => $today,
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
            'status_reservasi' => 'confirmed',
            'catatan' => 'Main pagi bersama teman kantor',
        ]);

        Reservasi::create([
            'kode_reservasi' => 'RES-' . Carbon::today()->format('Ymd') . '-002',
            'pelanggan_id' => $pelangganWalkIn->id,
            'lapangan_id' => $badminton2->id,
            'tanggal_reservasi' => $today,
            'jam_mulai' => '14:00:00',
            'jam_selesai' => '17:00:00',
            'status_reservasi' => 'confirmed',
            'catatan' => 'Turnamen persahabatan antar klub',
        ]);

        Reservasi::create([
            'kode_reservasi' => 'RES-' . Carbon::today()->format('Ymd') . '-003',
            'pelanggan_id' => $pelangganBudi->id,
            'lapangan_id' => $futsalB->id,
            'tanggal_reservasi' => $today,
            'jam_mulai' => '19:00:00',
            'jam_selesai' => '21:00:00',
            'status_reservasi' => 'pending',
            'catatan' => 'Minta siapkan bola 2 buah',
        ]);

        // Reservasi besok
        Reservasi::create([
            'kode_reservasi' => 'RES-' . Carbon::tomorrow()->format('Ymd') . '-001',
            'pelanggan_id' => $pelangganSiti->id,
            'lapangan_id' => $badminton1->id,
            'tanggal_reservasi' => $tomorrow,
            'jam_mulai' => '15:00:00',
            'jam_selesai' => '17:00:00',
            'status_reservasi' => 'pending',
            'catatan' => 'Booking reguler mingguan',
        ]);
    }
}
