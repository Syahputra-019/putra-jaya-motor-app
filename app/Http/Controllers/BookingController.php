<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Mekanik;
use App\Models\Pelanggan;
use App\Models\Sparepart;
use Illuminate\Http\Request;

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

        Booking::create($data);

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
        $booking->update($data);

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

    public function myBooking()
    {
        $pelanggan = Pelanggan::where('user_id', auth()->id())->first();

        $booking = null;

        if ($pelanggan) {
            $booking = Booking::where('user_id', auth()->id())
                ->latest()
                ->first();
        }

        return view('booking.my_booking', compact('booking'));
    }

}
