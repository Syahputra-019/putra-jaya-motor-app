<?php

namespace App\Support;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class BookingQueueService
{
    public const MAX_DAILY_BOOKINGS = 5;

    public function makeQueueData($jadwalBooking, ?Booking $existingBooking = null): array
    {
        $tanggal = Carbon::parse($jadwalBooking)->toDateString();

        if ($this->canKeepExistingQueue($existingBooking, $tanggal)) {
            return [
                'tanggal_antrean' => $existingBooking->tanggal_antrean->toDateString(),
                'nomor_antrean' => $existingBooking->nomor_antrean,
                'kode_antrean' => $existingBooking->kode_antrean,
            ];
        }

        $bookings = $this->queryForQueueDate($tanggal)
            ->when($existingBooking?->exists, fn (Builder $query) => $query->where('id', '!=', $existingBooking->id))
            ->lockForUpdate()
            ->get(['id', 'nomor_antrean']);

        if ($bookings->count() >= self::MAX_DAILY_BOOKINGS) {
            throw new DailyBookingQuotaExceededException($tanggal);
        }

        $usedNumbers = $bookings
            ->pluck('nomor_antrean')
            ->filter()
            ->map(fn ($nomor) => (int) $nomor)
            ->all();

        $nomor = $this->firstAvailableNumber($usedNumbers);

        return [
            'tanggal_antrean' => $tanggal,
            'nomor_antrean' => $nomor,
            'kode_antrean' => $this->queueCode($tanggal, $nomor),
        ];
    }

    public function queueCode(string $tanggal, int $nomor): string
    {
        return 'PJM-' . Carbon::parse($tanggal)->format('Ymd') . '-' . str_pad((string) $nomor, 3, '0', STR_PAD_LEFT);
    }

    public function activeBookingsAhead(Booking $booking): int
    {
        if (!$booking->tanggal_antrean || !$booking->nomor_antrean) {
            return 0;
        }

        return Booking::query()
            ->where('tanggal_antrean', $booking->tanggal_antrean->toDateString())
            ->where('nomor_antrean', '<', $booking->nomor_antrean)
            ->whereIn('status', ['menunggu', 'diproses'])
            ->count();
    }

    public function remainingQuotaForDate(string $tanggal): int
    {
        $used = $this->queryForQueueDate($tanggal)->count();

        return max(0, self::MAX_DAILY_BOOKINGS - $used);
    }

    private function canKeepExistingQueue(?Booking $booking, string $tanggal): bool
    {
        return $booking?->exists
            && $booking->tanggal_antrean
            && $booking->nomor_antrean
            && $booking->kode_antrean
            && $booking->tanggal_antrean->toDateString() === $tanggal;
    }

    private function queryForQueueDate(string $tanggal): Builder
    {
        return Booking::query()
            ->where(function (Builder $query) use ($tanggal) {
                $query->where('tanggal_antrean', $tanggal)
                    ->orWhere(function (Builder $legacyQuery) use ($tanggal) {
                        $legacyQuery
                            ->whereNull('tanggal_antrean')
                            ->whereDate('jadwal_booking', $tanggal);
                    });
            });
    }

    private function firstAvailableNumber(array $usedNumbers): int
    {
        for ($nomor = 1; $nomor <= self::MAX_DAILY_BOOKINGS; $nomor++) {
            if (!in_array($nomor, $usedNumbers, true)) {
                return $nomor;
            }
        }

        return max($usedNumbers ?: [0]) + 1;
    }
}
