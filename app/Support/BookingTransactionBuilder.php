<?php

namespace App\Support;

use App\Models\Booking;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BookingTransactionBuilder
{
    public function build(Booking $booking, Collection $services, Collection $spareparts): array
    {
        $serviceRows = [];
        $sparepartRows = [];
        $customRows = [];
        $approvedRecommendationNames = collect();

        $serviceLookup = $services->keyBy(fn ($service) => $this->normalizeName($service->nama_service));
        $sparepartById = $spareparts->keyBy('id');
        $sparepartByName = $spareparts->keyBy(fn ($sparepart) => $this->normalizeName($sparepart->nama_sparepart));

        foreach ($booking->kategori_servis ?? [] as $layanan) {
            $namaLayanan = trim((string) $layanan);

            if ($namaLayanan === '') {
                continue;
            }

            $service = $serviceLookup->get($this->normalizeName($namaLayanan));

            if ($service) {
                $this->appendServiceRow($serviceRows, (int) $service->id, (string) $service->nama_service, 1);
                continue;
            }

            $this->appendCustomRow($customRows, 'jasa', $namaLayanan, 0, 1);
        }

        if ($booking->status_konfirmasi === 'approved') {
            $approvedRecommendationNames = collect($booking->rekomendasi_sparepart ?? [])
                ->pluck('nama')
                ->filter()
                ->map(fn ($nama) => $this->normalizeName((string) $nama))
                ->values();
        }

        foreach ($booking->sparepart_diminta ?? [] as $namaSparepart) {
            if ($approvedRecommendationNames->contains($this->normalizeName((string) $namaSparepart))) {
                continue;
            }

            $this->appendSparepartName($sparepartRows, $customRows, $sparepartByName, (string) $namaSparepart, 1);
        }

        if ($booking->status_konfirmasi === 'approved') {
            foreach ($booking->rekomendasi_sparepart ?? [] as $rekomendasi) {
                $jumlah = max(1, (int) ($rekomendasi['jumlah'] ?? 1));
                $harga = max(0, (int) ($rekomendasi['harga'] ?? 0));
                $nama = trim((string) ($rekomendasi['nama'] ?? ''));
                $sparepartId = $rekomendasi['id'] ?? null;

                if ($sparepartId && $sparepartById->has($sparepartId)) {
                    $sparepart = $sparepartById->get($sparepartId);
                    $this->appendSparepartRow(
                        $sparepartRows,
                        (int) $sparepart->id,
                        (string) $sparepart->nama_sparepart,
                        $jumlah,
                    );
                    continue;
                }

                if ($nama !== '') {
                    $this->appendSparepartName($sparepartRows, $customRows, $sparepartByName, $nama, $jumlah, $harga);
                }
            }
        }

        if (empty($sparepartRows) && empty($customRows)) {
            foreach ($this->parsePartText((string) ($booking->sparepart_terpakai ?? '')) as $part) {
                $this->appendSparepartName(
                    $sparepartRows,
                    $customRows,
                    $sparepartByName,
                    $part['nama'],
                    $part['jumlah'],
                );
            }
        }

        return [
            'service_rows' => array_values($serviceRows),
            'sparepart_rows' => array_values($sparepartRows),
            'custom_rows' => array_values($customRows),
        ];
    }

    private function appendServiceRow(array &$rows, int $serviceId, string $nama, int $qty): void
    {
        if (!isset($rows[$serviceId])) {
            $rows[$serviceId] = [
                'service_id' => $serviceId,
                'nama' => $nama,
                'qty' => 0,
            ];
        }

        $rows[$serviceId]['qty'] += $qty;
    }

    private function appendSparepartRow(array &$rows, int $sparepartId, string $nama, int $qty): void
    {
        if (!isset($rows[$sparepartId])) {
            $rows[$sparepartId] = [
                'sparepart_id' => $sparepartId,
                'nama' => $nama,
                'qty' => 0,
            ];
        }

        $rows[$sparepartId]['qty'] += $qty;
    }

    private function appendCustomRow(array &$rows, string $jenis, string $nama, int $harga, int $qty): void
    {
        $key = $jenis . ':' . $this->normalizeName($nama) . ':' . $harga;

        if (!isset($rows[$key])) {
            $rows[$key] = [
                'jenis' => $jenis,
                'nama' => $nama,
                'harga' => $harga,
                'qty' => 0,
            ];
        }

        $rows[$key]['qty'] += $qty;
    }

    private function appendSparepartName(
        array &$sparepartRows,
        array &$customRows,
        Collection $sparepartByName,
        string $nama,
        int $qty,
        int $fallbackHarga = 0,
    ): void {
        $nama = trim($nama);

        if ($nama === '') {
            return;
        }

        $sparepart = $sparepartByName->get($this->normalizeName($nama));

        if ($sparepart) {
            $this->appendSparepartRow(
                $sparepartRows,
                (int) $sparepart->id,
                (string) $sparepart->nama_sparepart,
                $qty,
            );
            return;
        }

        $this->appendCustomRow($customRows, 'part', $nama, $fallbackHarga, $qty);
    }

    private function parsePartText(string $text): array
    {
        if (trim($text) === '') {
            return [];
        }

        $hasil = [];
        $parts = preg_split('/[\r\n,;]+/', $text) ?: [];

        foreach ($parts as $part) {
            $part = trim((string) $part);

            if ($part === '') {
                continue;
            }

            $qty = 1;
            $nama = $part;

            if (preg_match('/^(?<qty>\d+)\s*(x|pcs?|unit)?\s+(?<nama>.+)$/i', $part, $matches)) {
                $qty = max(1, (int) ($matches['qty'] ?? 1));
                $nama = trim((string) ($matches['nama'] ?? $part));
            }

            if ($nama === '') {
                continue;
            }

            $hasil[] = [
                'nama' => $nama,
                'jumlah' => $qty,
            ];
        }

        return $hasil;
    }

    private function normalizeName(string $value): string
    {
        return Str::of($value)
            ->lower()
            ->squish()
            ->value();
    }
}
