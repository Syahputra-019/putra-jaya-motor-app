<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'kode_transaksi',
        'tanggal',
        'pelanggan_id',
        'mekanik_id',
        'service_id',
        'detail_items',
        'keluhan',
        'status',
        'total_biaya',
        'metode_pembayaran',
        'status_pembayaran',
        'bukti_struk',
        'snap_token',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'detail_items' => 'array',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function mekanik()
    {
        return $this->belongsTo(Mekanik::class);
    }

    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    public function getLineItemsAttribute(): Collection
    {
        $detailItems = collect($this->detail_items ?? [])
            ->map(function (array $item) {
                $jumlah = max(1, (int) ($item['jumlah'] ?? 1));
                $harga = max(0, (int) ($item['harga'] ?? 0));

                return [
                    'jenis' => $item['jenis'] ?? 'custom',
                    'nama' => $item['nama'] ?? '-',
                    'jumlah' => $jumlah,
                    'harga' => $harga,
                    'subtotal' => (int) ($item['subtotal'] ?? ($harga * $jumlah)),
                ];
            });

        if ($detailItems->isEmpty() && $this->service) {
            $detailItems->push([
                'jenis' => 'service',
                'nama' => $this->service->nama_service,
                'jumlah' => 1,
                'harga' => (int) $this->service->harga,
                'subtotal' => (int) $this->service->harga,
            ]);
        }

        $sparepartItems = $this->detailTransaksis->map(function ($detail) {
            return [
                'jenis' => 'sparepart',
                'nama' => $detail->sparepart->nama_sparepart ?? 'Sparepart',
                'jumlah' => (int) $detail->jumlah,
                'harga' => (int) $detail->harga_satuan,
                'subtotal' => (int) $detail->sub_total,
            ];
        });

        return $detailItems->concat($sparepartItems);
    }
}
