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
        Schema::create('lapangans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lapangan');
            $table->string('jenis_lapangan'); // 'Futsal', 'Badminton'
            $table->string('status')->default('tersedia'); // 'tersedia', 'pemeliharaan', 'tidak_tersedia'
            $table->text('deskripsi')->nullable();
            $table->integer('harga_per_jam')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lapangans');
    }
};
