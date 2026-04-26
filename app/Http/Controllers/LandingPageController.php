<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LandingPageController extends Controller
{
    public function index()
    {
        $booking = null;

        if (auth()->check() && auth()->user()->role === 'pelanggan') {
            $pelanggan = Pelanggan::where('user_id', auth()->id())->first();

            if ($pelanggan) {
                $booking = Booking::where('user_id', auth()->id())
                    ->latest()
                    ->first();
            }
        }

        return view('landing', compact('booking'));
    }

    public function storeBooking(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_telp' => 'required|string|max:15',
            'plat_nomor' => 'required|string',
            'tipe_motor' => 'required|string',
            'keluhan' => 'required|string',
            'jadwal_booking' => 'required|date|after_or_equal:today',
        ]);

        if (Auth::check()) {
            $pelanggan = Pelanggan::firstOrCreate(
                ['user_id' => Auth::id()],
                [
                    'nama_pelanggan' => $request->nama,
                    'no_telp' => $request->no_telp,
                ]
            );

            $pelanggan->update([
                'nama_pelanggan' => $request->nama,
                'no_telp' => $request->no_telp,
            ]);
        } else {
            $pelanggan = Pelanggan::firstOrCreate(
                ['no_telp' => $request->no_telp],
                [
                    'nama_pelanggan' => $request->nama,
                    'user_id' => null,
                ]
            );
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'pelanggan_id' => $pelanggan->id,
            'plat_nomor' => $request->plat_nomor,
            'tipe_motor' => $request->tipe_motor,
            'keluhan' => $request->keluhan,
            'jadwal_booking' => $request->jadwal_booking,
            'status' => 'menunggu',
            'status_pembayaran' => 'belum lunas',
        ]);

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
