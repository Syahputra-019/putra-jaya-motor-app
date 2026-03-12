<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingDetail extends Model
{
    protected $fillable = [
        'booking_id',
        'service_id',
        'sparepart_id',
        'quantity',
        'sub_total',
    ];

    // RELASI : detail ini masuk ke nota/booking yang mana?
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // RELASI : jasa apa yang dii pilih?
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    //  RELASI : sparepart apa yang di pakai?
    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }
}
