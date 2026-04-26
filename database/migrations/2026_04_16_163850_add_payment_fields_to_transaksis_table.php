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
        Schema::table('transaksis', function (Blueprint $table) {
            $table->enum('metode_pembayaran', ['cash', 'transfer_manual', 'midtrans'])->default('cash')->after('total_biaya');
            $table->enum('status_pembayaran', ['belum_bayar', 'menunggu_konfirmasi', 'lunas', 'gagal'])->default('belum_bayar')->after('metode_pembayaran');
            $table->string('bukti_struk')->nullable()->after('status_pembayaran');
            $table->string('snap_token')->nullable()->after('bukti_struk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['metode_pembayaran', 'status_pembayaran', 'bukti_struk', 'snap_token']);
        });
    }
};
