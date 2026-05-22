<?php

namespace App\Exports;

use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class DashboardExport implements FromView, ShouldAutoSize, WithTitle
{
    protected $filter;

    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function title(): string
    {
        return 'Lap Pendapatan';
    }

    public function view(): View
    {
        $queryTransaksi = Transaksi::with(['pelanggan', 'mekanik'])->where('status_pembayaran', 'lunas');

        if ($this->filter == 'hari_ini') {
            $queryTransaksi->whereDate('tanggal', Carbon::today());
            $judulFilter = 'Hari Ini';
        } elseif ($this->filter == 'bulan_ini') {
            $queryTransaksi->whereMonth('tanggal', Carbon::now()->month)
                           ->whereYear('tanggal', Carbon::now()->year);
            $judulFilter = 'Bulan Ini';
        } elseif ($this->filter == 'tahun_ini') {
            $queryTransaksi->whereYear('tanggal', Carbon::now()->year);
            $judulFilter = 'Tahun Ini';
        }

        $transaksi = $queryTransaksi->get();
        $pendapatanTotal = $transaksi->sum('total_biaya');
        $isExcel = true;

        return view('dashboard.cetak_laporan', compact('transaksi', 'pendapatanTotal', 'judulFilter', 'isExcel'));
    }
}
