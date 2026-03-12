<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    protected $fillable = [
        'name',
        'price',
        'stock',
    ];

    // RELASI : Sparepart ini pernah dipakai di booking detail mana aja?
    public function bookingDetails()
    {
        return $this->hasMany(BookingDetail::class);
    }
}
