<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Mekanik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MekanikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ambil data mekanik, urutkan dari yang terbari
        $mekaniks = Mekanik::latest()->paginate(10);
        return view('mekanik.index', compact('mekaniks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mekanik.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. validasi data yang diinput user
        $request->validate([
            'nama_mekanik' => 'required|string|max:255',
            'no_telp' => 'required|string|max:15',
            'spesialisasi' => 'required|string',
        ], [
            // pesan error
            'nama_mekanik.required' => 'Nama mekanik wajib diisi.',
            'no_telp.required' => 'Nomor telepon wajib diisi.',
            'spesialisasi.required' => 'Spesialisasi wajib dipilih.',
        ]);

        // 2. simpan ke database
        Mekanik::create([
            'nama_mekanik' => $request->nama_mekanik,
            'no_telp' => $request->no_telp,
            'spesialisasi' => $request->spesialisasi,
        ]);
        // kembali ke halaman index dengan pesan sukses
        return redirect()->route('mekanik.index')->with('success', 'Mekanik berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Mekanik $mekanik)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mekanik $mekanik)
    {
        return view('mekanik.edit', compact('mekanik'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mekanik $mekanik)
    {
        // 1. validasi data
        $request->validate([
            'nama_mekanik' => 'required|string|max:255',
            'no_telp'        => 'required|string|max:15',
            'spesialisasi' => 'required|string',
        ]);

        // 2. Update datanya
        $mekanik->update([
            'nama_mekanik' => $request->nama_mekanik,
            'no_telp'        => $request->no_telp,
            'spesialisasi' => $request->spesialisasi,
        ]);

        // 3. Balikin ke tabel dengan pesan sukses
        return redirect()->route('mekanik.index')->with('success', 'Wih mantap! Data mekanik berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mekanik $mekanik)
    {
        $mekanik->delete();
        return redirect()->route('mekanik.index')->with('success', 'Mekanik berhasil dihapus.');
    }

public function jadwalKerja()
{
    // Ambil data user yang lagi login
    $user = auth()->user();

    // 1. CEK JIKA YANG LOGIN ADALAH ADMIN
    if ($user->role === 'admin') {
        // Admin bisa lihat SEMUA motor yang lagi antre & diproses oleh SEMUA mekanik
        $bookings = Booking::with(['pelanggan', 'mekanik'])
                    ->whereIn('status', ['menunggu', 'diproses'])
                    ->orderBy('jadwal_booking', 'asc')
                    ->get();
                    
        return view('mekanik.jadwal', compact('bookings'));
    }

    // 2. JIKA YANG LOGIN ADALAH MEKANIK
    $mekanik = Mekanik::where('user_id', $user->id)->first();

    if (!$mekanik) {
        return view('mekanik.jadwal', ['bookings' => collect([])])
               ->with('error', 'Akun login lu belum disambungkan ke data mekanik.');
    }

    // 2. Ambil booking yang ditugaskan ke ID mekanik tersebut
    $bookings = Booking::with('pelanggan')
                ->where('mekanik_id', $mekanik->id)
                ->whereIn('status', ['menunggu', 'diproses'])
                ->orderBy('jadwal_booking', 'asc')
                ->get();

    return view('mekanik.jadwal', compact('bookings'));
}

public function updateStatus(Request $request, Booking $booking)
    {
        // Validasi khusus mekanik (cuma boleh update status dan laporan)
        $request->validate([
            'status' => 'required|in:diproses,selesai',
            'sparepart_terpakai' => 'nullable|string',
            'catatan_mekanik' => 'nullable|string',
        ]);

        // Update data booking-nya
        $booking->update([
            'status' => $request->status,
            'sparepart_terpakai' => $request->sparepart_terpakai,
            'catatan_mekanik' => $request->catatan_mekanik,
        ]);

        return redirect()->back()->with('success', 'Mantap! Status motor berhasil diupdate.');
    }

}
