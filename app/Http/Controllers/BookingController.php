<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Mekanik;
use App\Models\Pelanggan;
use App\Models\Sparepart;
use App\Models\User;
use App\Notifications\NewBookingNotification;
use App\Notifications\NewJobAssignedNotification;
use App\Notifications\PelangganNotification;
use App\Notifications\RekomendasiDijawabNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class BookingController extends Controller
{
    public function index()
    {
        $query = Booking::with(['pelanggan', 'mekanik'])->orderBy('jadwal_booking', 'desc');

        if (auth()->user()->role === 'pelanggan') {
            $query->where('user_id', auth()->id());
        }

        $bookings = $query->paginate(10);

        return view('booking.index', compact('bookings'));
    }

    public function create()
    {
        $pelanggans = auth()->user()->role === 'pelanggan'
            ? Pelanggan::where('user_id', auth()->id())->get()
            : Pelanggan::all();

        $mekaniks = Mekanik::all();
        $spareparts = Sparepart::orderBy('nama_sparepart', 'asc')->get();

        return view('booking.create', compact('pelanggans', 'mekaniks', 'spareparts'));
    }

    public function store(Request $request)
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

        if (auth()->user()->role === 'pelanggan') {
            $pelanggan = Pelanggan::where('user_id', auth()->id())->first();
            if (!$pelanggan) {
                return redirect()->back()->withInput()->with('error', 'Silakan lengkapi profil/nomor telepon Anda terlebih dahulu di menu Profile.');
            }
            $data['pelanggan_id'] = $pelanggan->id;
            $data['user_id'] = auth()->id();
        } else {
            $pelanggan = Pelanggan::findOrFail($data['pelanggan_id']);
            $data['user_id'] = $pelanggan->user_id;
        }

        $data['status'] = $data['status'] ?? 'menunggu';
        $data['status_pembayaran'] = $data['status_pembayaran'] ?? 'belum lunas';

        $booking = Booking::create($data);

        // 1. Beritahu Admin
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new NewBookingNotification($booking));

        // 2. Beritahu Mekanik jika saat awal booking mekanik sudah di-set
        if ($booking->mekanik_id) {
            $mekanik = Mekanik::with('user')->find($booking->mekanik_id);
            if ($mekanik && $mekanik->user) $mekanik->user->notify(new NewJobAssignedNotification($booking));
        }

        // 3. Beritahu Pelanggan via Notifikasi Database
        if ($booking->user_id) {
            $user = User::find($booking->user_id);
            if ($user) {
                $waktu = Carbon::parse($booking->jadwal_booking)->format('d M Y, H:i');
                $user->notify(new PelangganNotification('Booking Berhasil Dibuat', "Booking servis kendaraan Anda ({$booking->plat_nomor}) pada {$waktu} telah terdaftar di sistem.", route('pelanggan.riwayat')));
            }
        }

        return redirect()->route('booking.index')->with('success', 'Booking berhasil dibuat!');
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

    public function update(Request $request, Booking $booking)
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

        if (auth()->user()->role === 'pelanggan') {
            $pelanggan = Pelanggan::where('user_id', auth()->id())->first();
            if (!$pelanggan) {
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

        // Jika admin nge-assign / mengubah mekanik baru untuk booking ini
        if ($booking->mekanik_id && $booking->mekanik_id !== $oldMekanikId) {
            $mekanik = Mekanik::with('user')->find($booking->mekanik_id);
            if ($mekanik && $mekanik->user) $mekanik->user->notify(new NewJobAssignedNotification($booking));
        }

        return redirect()->back()->with('success', 'Status dan catatan servis berhasil diupdate!');
    }

    public function destroy(Booking $booking)
    {
        if (auth()->user()->role === 'pelanggan' && $booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->delete();
        return redirect()->route('booking.index')->with('success', 'Booking berhasil dihapus!');
    }

    public function myBooking(Request $request)
    {
        $pelanggan = Pelanggan::where('user_id', auth()->id())->first();

        $bookings = collect();
        $booking = null;

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
        }

        return view('user.booking.my_booking', compact('booking', 'bookings'));
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
            $mekanik = Mekanik::with('user')->find($booking->mekanik_id);
            if ($mekanik && $mekanik->user) $mekanik->user->notify(new RekomendasiDijawabNotification($booking, $request->status_konfirmasi));
        }

        $pesan = $request->status_konfirmasi === 'approved' ? 'Mantap! Anda telah menyetujui rekomendasi perbaikan.' : 'Anda telah menolak rekomendasi perbaikan.';
        return redirect()->back()->with('success', $pesan);
    }
}
