<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pelanggan_id',
        'mekanik_id',
        'plat_nomor',
        'tipe_motor',
        'keluhan',
        'jadwal_booking',
        'status',
        'biaya_jasa',
        'biaya_sparepart',
        'total_biaya',
        'status_pembayaran',
        'catatan_mekanik',
        'sparepart_terpakai',
    ];

    // RELASI : booking ini milik pelanggan siapa?
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    // RELASI : booking ini ditangani oleh mekanik siapa?
    public function mekanik()
    {
        return $this->belongsTo(Mekanik::class);
    }
}
