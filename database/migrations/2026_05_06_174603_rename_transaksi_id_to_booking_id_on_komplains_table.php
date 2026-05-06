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
            // Ini buat ganti nama dari transaksi_id jadi booking_id
            $table->renameColumn('transaksi_id', 'booking_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('komplains', function (Blueprint $table) {
            // Ini buat balikin lagi kalau sewaktu-waktu kita rollback
            $table->renameColumn('booking_id', 'transaksi_id');
        });
    }
};