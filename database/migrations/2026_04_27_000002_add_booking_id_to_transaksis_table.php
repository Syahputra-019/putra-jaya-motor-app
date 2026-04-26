<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            if (!Schema::hasColumn('transaksis', 'booking_id')) {
                $table->foreignId('booking_id')->nullable()->after('id')->constrained('bookings')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            if (Schema::hasColumn('transaksis', 'booking_id')) {
                $table->dropConstrainedForeignId('booking_id');
            }
        });
    }
};
