<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Mekanik;
use App\Models\Pelanggan;
use App\Models\Service;
use App\Models\Sparepart;
use App\Models\User;
use App\Notifications\NewBookingNotification;
use App\Notifications\NewJobAssignedNotification;
use App\Notifications\PelangganNotification;
use App\Notifications\RekomendasiDijawabNotification;
use App\Support\BookingQueueService;
use App\Support\DailyBookingQuotaExceededException;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['pelanggan', 'mekanik'])->orderBy('jadwal_booking', 'desc');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        } else {
            $query->where(function ($q) {
                // Tampilkan hanya booking yang masih aktif di antrean
                $q->whereIn('status', ['menunggu', 'diproses'])
                    // atau yang sudah selesai tapi belum dibuatkan transaksi kasir
                    ->orWhere(function ($sub) {
                        $sub->where('status', 'selesai')
                            ->whereDoesntHave('transaksi');
                    });
            });
        }

        if ($request->has('tanggal') && $request->tanggal != '') {
            $query->whereDate('jadwal_booking', $request->tanggal);
        }

        if (auth()->user()->role === 'pelanggan') {
            $query->where('user_id', auth()->id());
        }

        $bookings = $query->paginate(10)->withQueryString();

        return view('booking.index', compact('bookings'));
    }

    public function create()
    {
        $pelanggans = auth()->user()->role === 'pelanggan'
            ? Pelanggan::where('user_id', auth()->id())->get()
            : Pelanggan::all();

        $mekaniks = Mekanik::all();
        $spareparts = Sparepart::orderBy('nama_sparepart', 'asc')->get();
        $services = Service::orderBy('nama_service', 'asc')->get();

        return view('booking.create', compact('pelanggans', 'mekaniks', 'spareparts', 'services'));
    }

    public function store(Request $request, BookingQueueService $queueService)
    {
        $isPelanggan = auth()->user()->role === 'pelanggan';

        $request->validate([
            'pelanggan_id' => $isPelanggan ? 'nullable' : 'required|exists:pelanggans,id',
            'mekanik_id' => 'nullable|exists:mekaniks,id',
            'plat_nomor' => 'required|string|max:50',
            'tipe_motor' => 'required|string|max:100',
            'keluhan' => 'required|string',
            'jadwal_booking' => 'required|date',
            'status' => 'nullable|in:menunggu,diproses,selesai,dibatalkan',
            'kategori_servis' => 'required|array|min:1',
            'sparepart_diminta' => 'nullable|array',
        ]);

        $data = $request->all();

        try {
            DB::beginTransaction();

            $data = array_merge($data, $queueService->makeQueueData($request->jadwal_booking));

            // START: Logic for automatic mechanic assignment (Round Robin)
            $mekanikIdToAssign = null;
            // Hanya jalankan jika mekanik tidak dipilih manual di form
            if (empty($request->mekanik_id)) {
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
            }
            // END: Logic for automatic mechanic assignment

            if (auth()->user()->role === 'pelanggan') {
                $pelanggan = Pelanggan::where('user_id', auth()->id())->first();
                if (!$pelanggan) {
                    DB::rollBack();
                    return redirect()->back()->withInput()->with('error', 'Silakan lengkapi profil/nomor telepon Anda terlebih dahulu di menu Profile.');
                }
                $data['pelanggan_id'] = $pelanggan->id;
                $data['user_id'] = auth()->id();
            } else {
                $pelanggan = Pelanggan::findOrFail($data['pelanggan_id']);
                $data['user_id'] = $pelanggan->user_id;
            }

            // Logika gabung Layanan Lainnya
            if (isset($data['kategori_servis']) && is_array($data['kategori_servis']) && in_array('Lainnya', $data['kategori_servis']) && $request->filled('layanan_lainnya')) {
                $data['kategori_servis'] = array_map(function($item) use ($request) {
                    return $item === 'Lainnya' ? 'Lainnya: ' . $request->layanan_lainnya : $item;
                }, $data['kategori_servis']);
            }

            $data['status'] = $data['status'] ?? 'menunggu';
            $data['status_pembayaran'] = $data['status_pembayaran'] ?? 'belum lunas';

            // Prioritaskan mekanik dari form, jika kosong, pakai mekanik otomatis
            $data['mekanik_id'] = $request->mekanik_id ?? $mekanikIdToAssign;

            $booking = Booking::create($data);

            DB::commit();
        } catch (DailyBookingQuotaExceededException $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error_kuota', 'Maaf bro, kuota booking tanggal ini sudah penuh. Silahkan lihat jadwal yang kosong.');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        // 1. Beritahu Admin
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new NewBookingNotification($booking));

        // 2. Beritahu Mekanik jika saat awal booking mekanik sudah di-set
        if ($booking->mekanik_id) {
            $mekanik = Mekanik::find($booking->mekanik_id);
            if ($mekanik && $mekanik->user_id) {
                User::find($mekanik->user_id)?->notify(new NewJobAssignedNotification($booking));
            }
        }

        // 3. Beritahu Pelanggan via Notifikasi Database
        if ($booking->user_id) {
            $user = User::find($booking->user_id);
            if ($user) {
                $waktu = Carbon::parse($booking->jadwal_booking)->format('d M Y, H:i');
                $nomorAntrean = $booking->kode_antrean ?? ('#' . str_pad((string) $booking->nomor_antrean, 3, '0', STR_PAD_LEFT));
                $user->notify(new PelangganNotification('Booking Berhasil Dibuat', "Booking servis kendaraan Anda ({$booking->plat_nomor}) pada {$waktu} telah terdaftar dengan nomor antrean {$nomorAntrean}.", route('booking.mine', ['id' => $booking->id])));
            }
        }

        return redirect()->route('booking.index')->with('success', 'Booking berhasil dibuat! Nomor antrean: ' . ($booking->kode_antrean ?? '#'.str_pad((string) $booking->nomor_antrean, 3, '0', STR_PAD_LEFT)));
    }

    public function show(Booking $booking)
    {
        if (auth()->user()->role === 'pelanggan' && $booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->load(['pelanggan', 'mekanik', 'transaksi']);

        if (auth()->user()->role === 'pelanggan') {
            return view('user.booking.show', compact('booking'));
        }

        return view('booking.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        if (auth()->user()->role === 'pelanggan' && $booking->user_id !== auth()->id()) {
            abort(403);
        }

        $pelanggans = auth()->user()->role === 'pelanggan'
            ? Pelanggan::where('user_id', auth()->id())->get()
            : Pelanggan::all();

        $mekaniks = Mekanik::all();

        return view('booking.edit', compact('booking', 'pelanggans', 'mekaniks'));
    }

    public function update(Request $request, Booking $booking, BookingQueueService $queueService)
    {
        if (auth()->user()->role === 'pelanggan' && $booking->user_id !== auth()->id()) {
            abort(403);
        }

        $isPelanggan = auth()->user()->role === 'pelanggan';

        $request->validate([
            'pelanggan_id' => $isPelanggan ? 'nullable' : 'required|exists:pelanggans,id',
            'mekanik_id' => 'nullable|exists:mekaniks,id',
            'plat_nomor' => 'required|string|max:50',
            'tipe_motor' => 'required|string|max:100',
            'keluhan' => 'required|string',
            'jadwal_booking' => 'required|date',
            'status' => 'nullable|in:menunggu,diproses,selesai,dibatalkan',
            'sparepart_terpakai' => 'nullable|string',
            'catatan_mekanik' => 'nullable|string',
        ]);

        $data = $request->all();

        try {
            DB::beginTransaction();

            $data = array_merge($data, $queueService->makeQueueData($request->jadwal_booking, $booking));

            if (auth()->user()->role === 'pelanggan') {
                $pelanggan = Pelanggan::where('user_id', auth()->id())->first();
                if (!$pelanggan) {
                    DB::rollBack();
                    return redirect()->back()->withInput()->with('error', 'Silakan lengkapi profil/nomor telepon Anda terlebih dahulu di menu Profile.');
                }
                $data['pelanggan_id'] = $pelanggan->id;
                $data['user_id'] = auth()->id();
            } else {
                $pelanggan = Pelanggan::findOrFail($data['pelanggan_id']);
                $data['user_id'] = $pelanggan->user_id;
            }

            $data['status'] = $data['status'] ?? $booking->status;

            $oldMekanikId = $booking->mekanik_id;

            $booking->update($data);

            DB::commit();
        } catch (DailyBookingQuotaExceededException $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error_kuota', 'Maaf bro, kuota booking tanggal ini sudah penuh. Silahkan lihat jadwal yang kosong.');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        // Jika admin nge-assign / mengubah mekanik baru untuk booking ini
        if ($booking->mekanik_id && $booking->mekanik_id != $oldMekanikId) { // Gunakan != agar string '1' dan integer 1 dianggap sama
            $mekanik = Mekanik::find($booking->mekanik_id);
            if ($mekanik && $mekanik->user_id) {
                $userMekanik = User::find($mekanik->user_id);
                if ($userMekanik) {
                    $userMekanik->notify(new NewJobAssignedNotification($booking));
                }
            }
        }

        return redirect()->route('booking.index')->with('success', 'Status dan catatan servis berhasil diupdate!');
    }

    public function destroy(Booking $booking)
    {
        if (auth()->user()->role === 'pelanggan' && $booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->delete();
        return redirect()->route('booking.index')->with('success', 'Booking berhasil dihapus!');
    }

    public function myBooking(Request $request, BookingQueueService $queueService)
    {
        $pelanggan = Pelanggan::where('user_id', auth()->id())->first();

        $bookings = collect();
        $booking = null;
        $activeBookingsAhead = 0;

        if ($pelanggan) {
            $bookings = Booking::with('transaksi')
                ->where('user_id', auth()->id())
                ->latest()
                ->take(10) // Ambil 10 booking terbaru agar tidak terlalu banyak tombol nantinya
                ->get();

            if ($request->has('id')) {
                $booking = $bookings->firstWhere('id', $request->id);
            }
            
            if (!$booking) {
                $booking = $bookings->first();
            }

            if ($booking) {
                $activeBookingsAhead = $queueService->activeBookingsAhead($booking);
            }
        }

        return view('user.booking.my_booking', compact('booking', 'bookings', 'activeBookingsAhead'));
    }

    public function konfirmasiRekomendasi(Request $request, Booking $booking)
    {
        // Pastikan booking ini milik user yang sedang login
        if (auth()->user()->role === 'pelanggan' && $booking->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'status_konfirmasi' => 'required|in:approved,rejected'
        ]);

        $booking->update([
            'status_konfirmasi' => $request->status_konfirmasi
        ]);

        // Beritahu Admin dan Mekanik bahwa pelanggan sudah merespon rekomendasi
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new RekomendasiDijawabNotification($booking, $request->status_konfirmasi));

        if ($booking->mekanik_id) {
            $mekanik = Mekanik::find($booking->mekanik_id);
            if ($mekanik && $mekanik->user_id) {
                User::find($mekanik->user_id)?->notify(new RekomendasiDijawabNotification($booking, $request->status_konfirmasi));
            }
        }

        $pesan = $request->status_konfirmasi === 'approved' ? 'Mantap! Anda telah menyetujui rekomendasi perbaikan.' : 'Anda telah menolak rekomendasi perbaikan.';
        return redirect()->back()->with('success', $pesan);
    }

    public function cekJadwal(BookingQueueService $queueService)
    {
        $jadwal = [];
        for ($i = 0; $i < 10; $i++) {
            $tanggal = Carbon::now()->addDays($i)->toDateString();
            $sisaKuota = $queueService->remainingQuotaForDate($tanggal);
            $jumlahBooking = BookingQueueService::MAX_DAILY_BOOKINGS - $sisaKuota;
            
            $jadwal[] = [
                'tanggal' => $tanggal,
                'jumlah_booking' => $jumlahBooking,
                'sisa_kuota' => $sisaKuota < 0 ? 0 : $sisaKuota,
            ];
        }

        return view('user.booking.jadwal', compact('jadwal'));
    }
}
