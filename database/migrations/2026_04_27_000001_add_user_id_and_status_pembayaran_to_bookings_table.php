<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('bookings', 'status_pembayaran')) {
                $table->string('status_pembayaran')->default('belum lunas')->after('status');
            }
        });

        DB::table('bookings')
            ->select('bookings.id', 'pelanggans.user_id')
            ->join('pelanggans', 'bookings.pelanggan_id', '=', 'pelanggans.id')
            ->whereNull('bookings.user_id')
            ->orderBy('bookings.id')
            ->get()
            ->each(function ($booking) {
                DB::table('bookings')
                    ->where('id', $booking->id)
                    ->update(['user_id' => $booking->user_id]);
            });

        DB::table('bookings')
            ->whereNull('status_pembayaran')
            ->update(['status_pembayaran' => 'belum lunas']);
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }

            if (Schema::hasColumn('bookings', 'status_pembayaran')) {
                $table->dropColumn('status_pembayaran');
            }
        });
    }
};
