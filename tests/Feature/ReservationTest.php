<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Lapangan;
use App\Models\Pelanggan;
use App\Models\Reservasi;
use Carbon\Carbon;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Buat admin dan pelanggan awal
        $this->admin = User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'role' => 'admin',
        ]);

        $this->user = User::factory()->create([
            'name' => 'Pelanggan Test',
            'email' => 'user@test.com',
            'role' => 'pelanggan',
        ]);

        $this->pelanggan = Pelanggan::create([
            'user_id' => $this->user->id,
            'nama_pelanggan' => $this->user->name,
            'nomor_telepon' => '081234567890',
            'alamat' => 'Jakarta',
        ]);

        $this->lapangan = Lapangan::create([
            'nama_lapangan' => 'Lapangan Futsal Test',
            'jenis_lapangan' => 'Futsal',
            'status' => 'tersedia',
            'harga_per_jam' => 150000,
        ]);
    }

    public function test_user_can_view_dashboard()
    {
        $response = $this->actingAs($this->user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Pelanggan Test');
    }

    public function test_customer_can_create_reservation()
    {
        $tanggal = Carbon::tomorrow()->format('Y-m-d');

        $response = $this->actingAs($this->user)->post('/reservasi', [
            'lapangan_id' => $this->lapangan->id,
            'tanggal_reservasi' => $tanggal,
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
            'catatan' => 'Main futsal santai',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reservasis', [
            'lapangan_id' => $this->lapangan->id,
            'tanggal_reservasi' => $tanggal,
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
            'status_reservasi' => 'pending',
        ]);
    }

    public function test_anti_double_booking_prevents_overlapping_reservation()
    {
        $tanggal = Carbon::tomorrow()->format('Y-m-d');

        // Booking pertama oleh user 1 (10:00 - 12:00)
        Reservasi::create([
            'kode_reservasi' => 'RES-TEST-001',
            'pelanggan_id' => $this->pelanggan->id,
            'lapangan_id' => $this->lapangan->id,
            'tanggal_reservasi' => $tanggal,
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
            'status_reservasi' => 'confirmed',
        ]);

        // Buat user ke-2
        $user2 = User::factory()->create(['role' => 'pelanggan']);
        $pelanggan2 = Pelanggan::create([
            'user_id' => $user2->id,
            'nama_pelanggan' => 'User 2',
            'nomor_telepon' => '089999999999',
        ]);

        // Percobaan booking kedua (overlap: 11:00 - 13:00)
        $response = $this->actingAs($user2)->post('/reservasi', [
            'lapangan_id' => $this->lapangan->id,
            'tanggal_reservasi' => $tanggal,
            'jam_mulai' => '11:00',
            'jam_selesai' => '13:00',
        ]);

        // Harus gagal dengan session error pada jam_mulai
        $response->assertSessionHasErrors('jam_mulai');
        $this->assertDatabaseCount('reservasis', 1); // Tidak boleh bertambah
    }

    public function test_admin_can_update_reservation_status()
    {
        $res = Reservasi::create([
            'kode_reservasi' => 'RES-TEST-002',
            'pelanggan_id' => $this->pelanggan->id,
            'lapangan_id' => $this->lapangan->id,
            'tanggal_reservasi' => Carbon::tomorrow()->format('Y-m-d'),
            'jam_mulai' => '14:00:00',
            'jam_selesai' => '16:00:00',
            'status_reservasi' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->patch('/admin/reservasi/' . $res->id . '/status', [
            'status_reservasi' => 'confirmed',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reservasis', [
            'id' => $res->id,
            'status_reservasi' => 'confirmed',
        ]);
    }
}
