<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaksi;
use Carbon\Carbon;

class ReminderServis extends Command
{
    // Nama perintah di terminal
    protected $signature = 'bengkel:reminder';

    // Deskripsi
    protected $description = 'Kirim WA reminder servis ke pelanggan setelah 60 hari';

    public function handle()
    {
        $token = env('FONNTE_TOKEN');
        
        if (!$token) {
            $this->error('Token Fonnte belum disetting di .env!');
            return;
        }

        // Target waktu (Untuk testing, kita pakai hari ini dulu)
        $tanggalTarget = Carbon::now()->subDays(60)->format('Y-m-d'); 

        // Narik data transaksi sekaligus data pelanggan (relasi)
        $transaksis = Transaksi::with('pelanggan')
            ->whereDate('created_at', $tanggalTarget)
            ->whereHas('pelanggan', function($query) {
                // Mencari di tabel pelanggan yang no_telp-nya nggak kosong
                $query->whereNotNull('no_telp'); 
            })
            ->get();

        if ($transaksis->isEmpty()) {
            $this->info("Nggak ada jadwal reminder untuk hari ini.");
            return;
        }

        foreach ($transaksis as $trx) {
            // Ambil data dari tabel pelanggan
            $namaPelanggan = $trx->pelanggan->nama; // Catatan: kalau di tabel lu namanya 'nama_pelanggan', tinggal ubah jadi $trx->pelanggan->nama_pelanggan
            $noTelp = $trx->pelanggan->no_telp;

            // Template pesan WA
            $pesan = "Halo Kak *{$namaPelanggan}* 👋\n\n";
            $pesan .= "Nggak kerasa nih udah 2 bulan sejak servis terakhir motor Kakak di *Putra Jaya Motor*. Yuk cek rutin oli dan CVT-nya biar tarikan motor tetap ngacir!\n\n";
            $pesan .= "Ditunggu kedatangannya ya Kak! 🏍️💨";

            // Eksekusi API Fonnte
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'target' => $noTelp,
                    'message' => $pesan,
                    'delay' => '2',
                ),
                CURLOPT_HTTPHEADER => array(
                    "Authorization: $token"
                ),
            ));

            curl_exec($curl);
            curl_close($curl);

            // Laporan sukses di terminal
            $this->info("Berhasil kirim WA ke: {$namaPelanggan} ({$noTelp})");
        }

        $this->info('Bot Reminder selesai! 🚀');
    }
}