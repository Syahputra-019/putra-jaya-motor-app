<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'tanggal_antrean')) {
                $table->date('tanggal_antrean')->nullable()->after('jadwal_booking');
            }

            if (!Schema::hasColumn('bookings', 'nomor_antrean')) {
                $table->unsignedSmallInteger('nomor_antrean')->nullable()->after('tanggal_antrean');
            }

            if (!Schema::hasColumn('bookings', 'kode_antrean')) {
                $table->string('kode_antrean', 32)->nullable()->after('nomor_antrean');
            }
        });

        $this->backfillQueueNumbers();

        Schema::table('bookings', function (Blueprint $table) {
            $table->unique(['tanggal_antrean', 'nomor_antrean'], 'bookings_daily_queue_unique');
            $table->unique('kode_antrean', 'bookings_kode_antrean_unique');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropUnique('bookings_daily_queue_unique');
            $table->dropUnique('bookings_kode_antrean_unique');
            $table->dropColumn(['tanggal_antrean', 'nomor_antrean', 'kode_antrean']);
        });
    }

    private function backfillQueueNumbers(): void
    {
        $counters = [];

        DB::table('bookings')
            ->select(['id', 'jadwal_booking'])
            ->orderBy('jadwal_booking')
            ->orderBy('id')
            ->get()
            ->each(function ($booking) use (&$counters) {
                $tanggal = Carbon::parse($booking->jadwal_booking)->toDateString();
                $counters[$tanggal] = ($counters[$tanggal] ?? 0) + 1;

                DB::table('bookings')
                    ->where('id', $booking->id)
                    ->update([
                        'tanggal_antrean' => $tanggal,
                        'nomor_antrean' => $counters[$tanggal],
                        'kode_antrean' => $this->queueCode($tanggal, $counters[$tanggal]),
                    ]);
            });
    }

    private function queueCode(string $tanggal, int $nomor): string
    {
        return 'PJM-' . Carbon::parse($tanggal)->format('Ymd') . '-' . str_pad((string) $nomor, 3, '0', STR_PAD_LEFT);
    }
};
