<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Menyiapkan tanggal default (Awal bulan sampai hari ini)
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Mengambil data transaksi berdasarkan rentang tanggal
        $transaksis = Transaksi::with(['pelanggan', 'service'])
                        ->whereBetween('tanggal', [$startDate, $endDate])
                        ->orderBy('tanggal', 'desc')
                        ->get();

        // Menghitung total pendapatan dan jumlah transaksi
        $totalPendapatan = $transaksis->sum('total_biaya');
        $totalTransaksi = $transaksis->count();

        // Mengirim semua data (termasuk $startDate) ke halaman view
        return view('laporan.index', compact('transaksis', 'startDate', 'endDate', 'totalPendapatan', 'totalTransaksi'));
    }
}