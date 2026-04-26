<?php

namespace App\Http\Controllers;

use App\Exports\PendapatanExport;
use App\Models\DetailTransaksi;
use App\Models\Sparepart;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Tangkap pilihan filter dari dropdown (Default: hari_ini)
        $filter = $request->input('filter', 'hari_ini'); 

        // 2. Siapin Query Dasar (Cuma ambil yang LUNAS)
        $queryTransaksi = Transaksi::where('status_pembayaran', 'lunas');

        // 3. Atur tanggal berdasarkan pilihan filter
        if ($filter == 'hari_ini') {
            $queryTransaksi->whereDate('tanggal', Carbon::today());
            $judulFilter = 'Hari Ini';
        } elseif ($filter == 'bulan_ini') {
            $queryTransaksi->whereMonth('tanggal', Carbon::now()->month)
                           ->whereYear('tanggal', Carbon::now()->year);
            $judulFilter = 'Bulan Ini';
        } elseif ($filter == 'tahun_ini') {
            $queryTransaksi->whereYear('tanggal', Carbon::now()->year);
            $judulFilter = 'Tahun Ini';
        }

        // 4. Hitung duit dan jumlah transaksi berdasarkan filter di atas
        $pendapatanTotal = $queryTransaksi->sum('total_biaya');
        $transaksiHariIni = $queryTransaksi->count();

        // Trik jitu: Ambil semua ID transaksi hasil filter, buat ngitung sparepart
        $transaksiIds = $queryTransaksi->pluck('id');
        $pendapatanSparepart = DetailTransaksi::whereIn('transaksi_id', $transaksiIds)->sum('sub_total');
        $pendapatanJasa = $pendapatanTotal - $pendapatanSparepart;

        // 5. Cek Stok Menipis & Menunggu ACC (Ini ga terpengaruh filter tanggal)
        $stokMenipis = Sparepart::where('stok', '<=', 5)->get();
        $jumlahStokMenipis = $stokMenipis->count();
        $menungguAcc = Transaksi::where('status_pembayaran', 'menunggu_konfirmasi')->count();

        // 1. Data Pendapatan per Bulan (6 Bulan Terakhir)
        $pendapatanBulanan = Transaksi::select(
            DB::raw('MONTHNAME(created_at) as bulan'),
            DB::raw('SUM(total_biaya) as total')
        )
        ->where('status_pembayaran', 'lunas')
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('bulan')
        ->orderBy('created_at', 'ASC')
        ->get();

        // 2. Data Sparepart Terlaris (Top 5)
        $sparepartTerlaris = DetailTransaksi::select(
            'spareparts.nama_sparepart',
            DB::raw('SUM(detail_transaksis.jumlah) as total_terjual')
        )
        ->join('spareparts', 'detail_transaksis.sparepart_id', '=', 'spareparts.id')
        ->groupBy('spareparts.nama_sparepart')
        ->orderBy('total_terjual', 'DESC')
        ->limit(5)
        ->get();
        
        return view('dashboard.index', compact(
            'pendapatanTotal', 
            'pendapatanJasa', 
            'pendapatanSparepart', 
            'transaksiHariIni', 
            'jumlahStokMenipis', 
            'stokMenipis',
            'menungguAcc',
            'filter',
            'judulFilter',
            'pendapatanBulanan',
            'sparepartTerlaris'
        ));
    }

    public function cetak(Request $request)
    {
        $filter = $request->input('filter', 'bulan_ini'); 

        // Tarik data transaksi lengkap sama detailnya (Cuma yang LUNAS)
        $queryTransaksi = Transaksi::with(['pelanggan', 'detailTransaksis.sparepart', 'mekanik'])
                                    ->where('status_pembayaran', 'lunas');

        if ($filter == 'hari_ini') {
            $queryTransaksi->whereDate('tanggal', Carbon::today());
            $judulFilter = date('d F Y'); // Contoh: 18 April 2026
        } elseif ($filter == 'bulan_ini') {
            $queryTransaksi->whereMonth('tanggal', Carbon::now()->month)
                           ->whereYear('tanggal', Carbon::now()->year);
            $judulFilter = 'Bulan ' . date('F Y'); // Contoh: Bulan April 2026
        } elseif ($filter == 'tahun_ini') {
            $queryTransaksi->whereYear('tanggal', Carbon::now()->year);
            $judulFilter = 'Tahun ' . date('Y'); // Contoh: Tahun 2026
        }

        $transaksi = $queryTransaksi->get();
        $pendapatanTotal = $transaksi->sum('total_biaya');

        return view('dashboard.cetak_laporan', compact('transaksi', 'pendapatanTotal', 'judulFilter'));
    }

    public function exportExcel(Request $request)
    {
        $filter = $request->input('filter', 'bulan_ini');
        $filename = "Laporan_Pendapatan_" . date('d-m-Y') . ".xlsx";

        return Excel::download(new PendapatanExport($filter), $filename);
    }
}