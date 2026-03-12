<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'price',
    ];

    // RELASI : jasa ini pernah di booking detail mana aja?
    public function bookingDetails()
    {
        return $this->hasMany(BookingDetail::class);
    }
}
