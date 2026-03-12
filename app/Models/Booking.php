<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'mechanic_id',
        'vehicle_plate',
        'vehicle_model',
        'complaint',
        'booking_date',
        'status',
        'total_price'
    ];

    // RELASI : boking ini milik pelanggan siapa?
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // RELASI : boking ini ditangani oleh mekanik siapa?
    public function mechanic()
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    // RELASI : booking ini punya banyak keranjang apa aja?
    public function details()
    {
        return $this->hasMany(BookingDetail::class);
    }
}