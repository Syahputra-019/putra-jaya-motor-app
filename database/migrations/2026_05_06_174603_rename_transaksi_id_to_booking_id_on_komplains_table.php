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
        Schema::table('komplains', function (Blueprint $table) {
            // Hapus constraint foreign key lama terlebih dahulu
            $table->dropForeign(['transaksi_id']);
            
            // Ini buat ganti nama dari transaksi_id jadi booking_id
            $table->renameColumn('transaksi_id', 'booking_id');

            // Buatkan constraint foreign key baru yang mengarah ke tabel bookings
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('komplains', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);
            
            // Ini buat balikin lagi kalau sewaktu-waktu kita rollback
            $table->renameColumn('booking_id', 'transaksi_id');
            
            $table->foreign('transaksi_id')->references('id')->on('transaksis')->onDelete('cascade');
        });
    }
};