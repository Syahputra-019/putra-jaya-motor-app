<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Carbon\Carbon;
use App\Exports\PendapatanExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Menyiapkan tanggal default (Awal bulan sampai hari ini)
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Mengambil data transaksi berdasarkan rentang tanggal
        $transaksis = Transaksi::with(['pelanggan', 'service'])
                        ->where('status_pembayaran', 'lunas')
                        ->whereBetween('tanggal', [$startDate, $endDate])
                        ->orderBy('tanggal', 'desc')
                        ->latest()
                        ->paginate(10);

        // Menghitung total pendapatan dan jumlah transaksi
        $totalPendapatan = $transaksis->sum('total_biaya');
        $totalTransaksi = $transaksis->count();

        // Mengirim semua data (termasuk $startDate) ke halaman view
        return view('laporan.index', compact('transaksis', 'startDate', 'endDate', 'totalPendapatan', 'totalTransaksi'));
    }

    public function cetak(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Tarik data transaksi lengkap beserta relasinya (Khusus Lunas)
        $transaksi = Transaksi::with(['pelanggan', 'detailTransaksis.sparepart', 'mekanik', 'service'])
            ->where('status_pembayaran', 'lunas')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'desc')
            ->get();

        $pendapatanTotal = $transaksi->sum('total_biaya');
        $judulFilter = Carbon::parse($startDate)->format('d F Y') . ' - ' . Carbon::parse($endDate)->format('d F Y');

        $isExcel = false;
        return view('laporan.cetak_laporan', compact('transaksi', 'pendapatanTotal', 'judulFilter', 'isExcel'));
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $filename = "Laporan_Pendapatan_" . Carbon::parse($startDate)->format('dmY') . "_sd_" . Carbon::parse($endDate)->format('dmY') . ".xlsx";

        return Excel::download(new PendapatanExport($startDate, $endDate), $filename);
    }
}