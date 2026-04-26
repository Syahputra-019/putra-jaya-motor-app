<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LandingPageController extends Controller
{
    public function index() {

$booking = null;

        // 1. CEK DULU: Apakah ada user yang login DAN apakah rolenya 'pelanggan'?
        if (auth()->check() && auth()->user()->role === 'pelanggan') {
            
            // 2. Kalau ada yang login, baru boleh ambil ID-nya
            $pelanggan = \App\Models\Pelanggan::where('user_id', auth()->user()->id)->first();

            // 3. Kalau data pelanggannya ketemu di database, cari booking-nya
            if ($pelanggan) {
                $booking = \App\Models\Booking::where('pelanggan_id', $pelanggan->id)
                    ->latest() // Ambil yang paling baru
                    ->first();
            }
        }

        return view('landing', compact('booking'));
    }

    public function storeBooking(Request $request) {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_telp' => 'required|string|max:15',
            'plat_nomor' => 'required|string',
            'tipe_motor' => 'required|string',
            'keluhan' => 'required|string',
            'jadwal_booking' => 'required|date|after_or_equal:today',
        ]);

        // CEK PELANGGAN: Kalau nomor HP sudah ada, pakai yang lama. Kalau belum, buat baru.
        $pelanggan = Pelanggan::firstOrCreate(
            ['no_telp' => $request->no_telp],
            ['nama_pelanggan' => $request->nama] // Sesuaikan nama kolom di tabel pelanggan lu
        );

        // SIMPAN BOOKING
        $booking = Booking::create([
            'pelanggan_id' => $pelanggan->id,
            'plat_nomor' => $request->plat_nomor,
            'tipe_motor' => $request->tipe_motor,
            'keluhan' => $request->keluhan,
            'jadwal_booking' => $request->jadwal_booking,
            'status' => 'menunggu', // Status default
        ]);

        // KIRIM WA OTOMATIS (Fonnte)
        $this->sendWhatsApp($pelanggan, $booking);

        return redirect()->back()->with('success', 'Booking berhasil! Tiket antrean sudah dikirim ke WhatsApp kamu.');
    }

    private function sendWhatsApp($pelanggan, $booking) {
        $token = env('FONNTE_TOKEN');
        $waktu = Carbon::parse($booking->jadwal_booking)->format('d M Y, H:i');
        
        $pesan = "Halo Kak *{$pelanggan->nama_pelanggan}* 👋\n\n";
        $pesan .= "Booking servis di *Putra Jaya Motor* BERHASIL!\n\n";
        $pesan .= "🎫 *TIKET ANTREAN DIGITAL*\n";
        $pesan .= "Plat: {$booking->plat_nomor}\n";
        $pesan .= "Motor: {$booking->tipe_motor}\n";
        $pesan .= "Jadwal: *{$waktu}*\n\n";
        $pesan .= "Silakan datang tepat waktu dan tunjukkan pesan ini ya Kak. Terima kasih!";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('target' => $pelanggan->no_telp, 'message' => $pesan),
            CURLOPT_HTTPHEADER => array("Authorization: $token"),
        ));
        curl_exec($curl);
        curl_close($curl);
    }
}