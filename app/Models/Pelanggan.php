<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    // kasih tau laravel kolom mana aja yang boleh diisi
    protected $fillable = [
        'user_id',
        'nama_pelanggan',
        'no_telp',
        'alamat',
    ];
}
