<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Mekanik;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        // ambil data booking, sekalian bawa data pelanggan dan mekanik
        $bookings = Booking::with(['pelanggan', 'mekanik'])->orderBy('jadwal_booking', 'desc')->paginate(10);
        return view('booking.index', compact('bookings'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::all();
        $mekaniks = Mekanik::all();
        return view('booking.create', compact('pelanggans', 'mekaniks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'mekanik_id' => 'nullable|exists:mekaniks,id',
            'plat_nomor' => 'required|string|max:50',
            'tipe_motor' => 'required|string|max:100',
            'keluhan' => 'required|string',
            'jadwal_booking' => 'required|date',
            'status' => 'required|in:menunggu,diproses,selesai,dibatalkan',
        ]);

        Booking::create($request->all());

        return redirect()->route('booking.index')->with('success', 'Booking berhasil dibuat!');
    }

    public function edit(Booking $booking)
    {
        $pelanggans = Pelanggan::all();
        $mekaniks = Mekanik::all();
        return view('booking.edit', compact('booking', 'pelanggans', 'mekaniks'));
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'mekanik_id' => 'nullable|exists:mekaniks,id',
            'plat_nomor' => 'required|string|max:50',
            'tipe_motor' => 'required|string|max:100',
            'keluhan' => 'required|string',
            'jadwal_booking' => 'required|date',
            'status' => 'required|in:menunggu,diproses,selesai,dibatalkan',
            'sparepart_terpakai' => 'nullable|string',
            'catatan_mekanik' => 'nullable|string',
        ]);

        $booking->update($request->all());

        return redirect()->back()->with('success', 'Status dan catatan servis berhasil diupdate!');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('booking.index')->with('success', 'Booking berhasil dihapus!');
    }

    public function myBooking()
    {
// 1. Cari data pelanggan yang terhubung dengan akun (user) yang sedang login
    $pelanggan = \App\Models\Pelanggan::where('user_id', auth()->user()->id)->first();

    $booking = null;

    // 2. Jika akun ini benar-benar punya data pelanggan, baru cari booking-nya
    if ($pelanggan) {
        $booking = \App\Models\Booking::where('pelanggan_id', $pelanggan->id)
            ->latest() // Ambil yang paling baru
            ->first();
    }

    return view('booking.my_booking', compact('booking'));
    }

}
