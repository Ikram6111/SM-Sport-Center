<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservasis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_reservasi')->unique();
            $table->foreignId('pelanggan_id')->constrained('pelanggans')->cascadeOnDelete();
            $table->foreignId('lapangan_id')->constrained('lapangans')->cascadeOnDelete();
            $table->date('tanggal_reservasi');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('status_reservasi')->default('pending'); // 'pending', 'confirmed', 'cancelled', 'completed'
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Indexing for search and scalability optimization as required by PRD 15
            $table->index(['tanggal_reservasi', 'lapangan_id', 'status_reservasi'], 'idx_reservasi_jadwal');
            $table->index('status_reservasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasis');
    }
};
