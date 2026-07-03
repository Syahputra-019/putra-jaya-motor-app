<?php

namespace App\Support;

use RuntimeException;

class DailyBookingQuotaExceededException extends RuntimeException
{
    public function __construct(public readonly string $tanggal)
    {
        parent::__construct("Kuota booking tanggal {$tanggal} sudah penuh.");
    }
}
