<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Komplain;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomplainController extends Controller
{
    // Halaman list komplain untuk Pelanggan
    public function index()
    {
        $komplains = Komplain::where('user_id', Auth::id())->latest()->get();
        return view('user.komplain.index', compact('komplains'));
    }

public function create()
{
    // Ambil data dari tabel bookings yang miliknya user ini
    // Dan biasanya yang statusnya sudah 'selesai' atau 'lunas'
    $bookings = Booking::where('user_id', Auth::id())
                    ->where('status', 'selesai') // Sesuaikan dengan nama status di tabel lu
                    ->get();

    return view('user.komplain.create', compact('bookings'));
}
    // Simpan Komplain
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required',
            'deskripsi_komplain' => 'required|min:5',
            'foto_bukti' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $namaFoto = null;
        if($request->hasFile('foto_bukti')){
            $namaFoto = time().'.'.$request->foto_bukti->extension();  
            $request->foto_bukti->move(public_path('uploads/komplain'), $namaFoto);
        }

        Komplain::create([
            'user_id' => Auth::id(),
            'booking_id' => $request->booking_id,
            'deskripsi_komplain' => $request->deskripsi_komplain,
            'foto_bukti' => $namaFoto,
            'status' => 'menunggu'
        ]);

        return redirect()->route('komplain.index')->with('success', 'Komplain berhasil dikirim, tunggu tanggapan admin ya!');
    }

    // --- KHUSUS ADMIN ---
    public function adminIndex()
    {
        $komplains = Komplain::with(['user', 'booking'])->latest()->get();
        return view('pelanggan.komplain.index', compact('komplains'));
    }

    public function tanggapi(Request $request, $id)
    {
        $komplain = Komplain::findOrFail($id);
        $komplain->update([
            'tanggapan_bengkel' => $request->tanggapan,
            'status' => 'diproses' // atau 'selesai'
        ]);

        return back()->with('success', 'Berhasil memberikan tanggapan!');
    }
}