<?php

namespace App\Exports;

use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PendapatanExport implements FromView, ShouldAutoSize
{
    protected $filter;

    // Nangkep filter dari controller
    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function view(): View
    {
        $queryTransaksi = Transaksi::with(['pelanggan', 'mekanik'])->where('status_pembayaran', 'lunas');

        // Atur filter tanggal
        if ($this->filter == 'hari_ini') {
            $queryTransaksi->whereDate('tanggal', Carbon::today());
            $judulFilter = "Hari Ini (" . date('d M Y') . ")";
        } elseif ($this->filter == 'bulan_ini') {
            $queryTransaksi->whereMonth('tanggal', Carbon::now()->month)
                           ->whereYear('tanggal', Carbon::now()->year);
            $judulFilter = "Bulan Ini (" . date('M Y') . ")";
        } else {
            $queryTransaksi->whereYear('tanggal', Carbon::now()->year);
            $judulFilter = "Tahun Ini (" . date('Y') . ")";
        }

        $transaksi = $queryTransaksi->get();
        $pendapatanTotal = $transaksi->sum('total_biaya');

        // Ngelempar data ke file Blade Excel
        return view('dashboard.excel_laporan', compact('transaksi', 'pendapatanTotal', 'judulFilter'));
    }
}