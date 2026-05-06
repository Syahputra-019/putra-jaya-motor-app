<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komplain extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi ke User (Pelanggan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Booking (Service mana yang dikomplain)
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}