<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id',
        'sparepart_id',
        'jumlah',
        'harga_satuan',
        'sub_total',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }
}
