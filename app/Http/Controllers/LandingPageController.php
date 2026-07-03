<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Pelanggan;
use App\Models\Mekanik;
use App\Models\Sparepart;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\User;
use App\Notifications\NewBookingNotification;
use App\Notifications\PelangganNotification;
use App\Support\BookingQueueService;
use App\Support\DailyBookingQuotaExceededException;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class LandingPageController extends Controller
{
    public function index(BookingQueueService $queueService)
    {
        $booking = null;
        $pelanggan = null;
        $activeBookingsAhead = 0;
        $spareparts = Sparepart::orderBy('nama_sparepart', 'asc')->get();
        $services = Service::orderBy('nama_service', 'asc')->get();
        
        // Ambil data testimonial yang di-approve, maksimal 10 data terbaru
        $testimonials = Testimonial::with('user')->where('status', 'approved')->latest()->take(10)->get();

        if (auth()->check() && auth()->user()->role === 'pelanggan') {
            $pelanggan = Pelanggan::where('user_id', auth()->id())->first();

            if ($pelanggan) {
                $booking = Booking::with('transaksi')
                    ->where('user_id', auth()->id())
                    ->latest()
                    ->first();

                if ($booking) {
                    $activeBookingsAhead = $queueService->activeBookingsAhead($booking);
                }
            }
        }

        return view('landing', compact('booking', 'spareparts', 'services', 'pelanggan', 'testimonials', 'activeBookingsAhead'));
    }

    public function storeBooking(Request $request, BookingQueueService $queueService)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
            'plat_nomor' => 'required|string',
            'tipe_motor' => 'required|string',
            'keluhan' => 'nullable|string',
            'jadwal_booking' => 'required|date|after:now',
            'kategori_servis' => 'required|array|min:1',
            'layanan_lainnya' => 'nullable|string',
            'sparepart_diminta' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            $queueData = $queueService->makeQueueData($request->jadwal_booking);

            // START: Logic for automatic mechanic assignment (Round Robin)
            $mekanikIdToAssign = null;
            $allMekanikIds = Mekanik::pluck('id')->all();

            if (!empty($allMekanikIds)) {
                // Cari booking terakhir yang punya mekanik
                $lastAssignedBooking = Booking::whereNotNull('mekanik_id')->latest('id')->first();

                if ($lastAssignedBooking && in_array($lastAssignedBooking->mekanik_id, $allMekanikIds)) {
                    $lastMekanikIndex = array_search($lastAssignedBooking->mekanik_id, $allMekanikIds);
                    // Ambil mekanik selanjutnya, jika sudah di akhir, kembali ke awal (modulo)
                    $nextMekanikIndex = ($lastMekanikIndex + 1) % count($allMekanikIds);
                    $mekanikIdToAssign = $allMekanikIds[$nextMekanikIndex];
                } else {
                    // Jika tidak ada booking sebelumnya atau mekanik lama sudah dihapus, ambil mekanik pertama
                    $mekanikIdToAssign = $allMekanikIds[0];
                }
            }
            // END: Logic for automatic mechanic assignment

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

            $kategori_servis = $request->kategori_servis;
            if (in_array('Lainnya', $kategori_servis) && $request->filled('layanan_lainnya')) {
                $kategori_servis = array_map(function($item) use ($request) {
                    return $item === 'Lainnya' ? 'Lainnya: ' . $request->layanan_lainnya : $item;
                }, $kategori_servis);
            }

            $booking = Booking::create(array_merge([
                'user_id' => Auth::id(),
                'pelanggan_id' => $pelanggan->id,
                'mekanik_id' => $mekanikIdToAssign,
                'plat_nomor' => $request->plat_nomor,
                'tipe_motor' => $request->tipe_motor,
                'keluhan' => $request->keluhan,
                'jadwal_booking' => $request->jadwal_booking,
                'kategori_servis' => $kategori_servis,
                'sparepart_diminta' => $request->sparepart_diminta,
                'status' => 'menunggu',
                'status_pembayaran' => 'belum lunas',
            ], $queueData));

            DB::commit();
        } catch (DailyBookingQuotaExceededException $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error_kuota', 'Maaf bro, kuota booking tanggal ini sudah penuh. Silahkan lihat jadwal yang kosong.');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        // Kirim Notifikasi ke semua admin
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new NewBookingNotification($booking));

        // Kirim Notifikasi Sistem (Database) ke Pelanggan agar muncul di riwayat web
        if ($booking->user_id) {
            $user = User::find($booking->user_id);
            if ($user) {
                $waktu = Carbon::parse($booking->jadwal_booking)->format('d M Y, H:i');
                $nomorAntrean = $booking->kode_antrean ?? ('#' . str_pad((string) $booking->nomor_antrean, 3, '0', STR_PAD_LEFT));
                $user->notify(new PelangganNotification('Booking Berhasil', "Booking servis kendaraan Anda ({$booking->plat_nomor}) untuk jadwal {$waktu} telah tersimpan dengan nomor antrean {$nomorAntrean}.", route('booking.mine', ['id' => $booking->id])));
            }
        }

        $this->sendWhatsApp($pelanggan, $booking);

        return redirect()->back()->with('success', 'Booking berhasil! Nomor antrean kamu ' . ($booking->kode_antrean ?? '#'.str_pad((string) $booking->nomor_antrean, 3, '0', STR_PAD_LEFT)) . '. Tiket antrean sudah dikirim ke WhatsApp kamu.');
    }

    private function sendWhatsApp($pelanggan, $booking) {
        $token = env('FONNTE_TOKEN');
        $waktu = Carbon::parse($booking->jadwal_booking)->format('d M Y, H:i');
        $nomorAntrean = $booking->kode_antrean ?? ('#' . str_pad((string) $booking->nomor_antrean, 3, '0', STR_PAD_LEFT));
        
        $pesan = "Halo Kak *{$pelanggan->nama_pelanggan}* 👋\n\n";
        $pesan .= "Booking servis di *Putra Jaya Motor* BERHASIL!\n\n";
        $pesan .= "🎫 *TIKET ANTREAN DIGITAL*\n";
        $pesan .= "Nomor Antrean: *{$nomorAntrean}*\n";
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
