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
        Schema::table('reservasis', function (Blueprint $table) {
            $table->string('metode_pembayaran')->default('cash')->after('status_reservasi'); // 'cash', 'qris'
            $table->string('bukti_qris')->nullable()->after('metode_pembayaran'); // Path penyimpanan bukti PNG di storage publik
            $table->string('status_pembayaran')->default('unpaid')->after('bukti_qris'); // 'unpaid', 'pending', 'paid', 'failed'

            $table->index('status_pembayaran');
            $table->index('metode_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservasis', function (Blueprint $table) {
            $table->dropIndex(['status_pembayaran']);
            $table->dropIndex(['metode_pembayaran']);
            $table->dropColumn(['metode_pembayaran', 'bukti_qris', 'status_pembayaran']);
        });
    }
};
