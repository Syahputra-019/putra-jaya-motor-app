<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    // Daftarkan semua field yang bisa diisi lewat Mass Assignment (create/update)
    protected $fillable = [
        'user_id',
        'pelanggan_id',
        'mekanik_id',
        'plat_nomor',
        'tipe_motor',
        'kategori_servis',
        'sparepart_diminta',
        'keluhan',
        'jadwal_booking',
        'status',
        'status_pembayaran',
        'sparepart_terpakai',
        'catatan_mekanik',
        'rekomendasi_sparepart',
        'status_konfirmasi',
    ];

    // Casting array ke JSON dan sebaliknya secara otomatis
    protected $casts = [
        'kategori_servis' => 'array',
        'sparepart_diminta' => 'array',
        'rekomendasi_sparepart' => 'array',
        'jadwal_booking' => 'datetime',
    ];

    // Relasi ke tabel pelanggans
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    // Relasi ke tabel mekaniks
    public function mekanik()
    {
        return $this->belongsTo(Mekanik::class);
    }

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaksi()
    {
        return $this->hasOne(Transaksi::class);
    }
}
