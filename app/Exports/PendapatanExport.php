<?php

namespace App\Exports;

use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class PendapatanExport implements FromView, ShouldAutoSize, WithTitle
{
    protected $startDate;
    protected $endDate;

    // Nangkep filter dari controller
    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function title(): string
    {
        return 'Laporan Pendapatan';
    }

    public function view(): View
    {
        $transaksi = Transaksi::with(['pelanggan', 'detailTransaksis.sparepart', 'mekanik', 'service'])
            ->where('status_pembayaran', 'lunas')
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->orderBy('tanggal', 'desc')
            ->get();

        $pendapatanTotal = $transaksi->sum('total_biaya');
        $judulFilter = Carbon::parse($this->startDate)->format('d M Y') . ' - ' . Carbon::parse($this->endDate)->format('d M Y');

        $isExcel = true;

        // Ngelempar data ke file Blade Excel
        return view('laporan.cetak_laporan', compact('transaksi', 'pendapatanTotal', 'judulFilter', 'isExcel'));
    }
}