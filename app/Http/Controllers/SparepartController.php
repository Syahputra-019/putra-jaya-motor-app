<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use Illuminate\Http\Request;

class SparepartController extends Controller
{
    // Fungsi untuk menampilkan halaman utama sparepart
    public function index(Request $request)
{        
    // Ambil data sparepart, kalau ada pencarian difilter, kalau tidak tampilkan semua
    $spareparts = Sparepart::query()
        ->when($request->search, function ($query, $search) {
            return $query->where('nama_sparepart', 'like', '%' . $search . '%');
        })
        ->orderBy('created_at', 'desc') // Tetap pakai cara kamu juga nggak masalah
        ->paginate(10)
        ->withQueryString(); // Jaga agar kata kunci tidak hilang saat pindah halaman

    // Kirim datanya ke file view
    return view('sparepart.index', compact('spareparts'));
}

    // Fungsi untuk menampilkan form tambah data
    public function create()
    {
        return view('sparepart.create');
    }

    // Fungsi untuk memproses dan menyimpan data dari form
    public function store(Request $request)
    {
        // Validasi data (biar user nggak masukin huruf di kolom harga/stok)
        $request->validate([
            'nama_sparepart' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
        ]);

        // Simpan data ke database
        Sparepart::create([
            'nama_sparepart' => $request->nama_sparepart,
            'harga' => $request->harga,
            'stok' => $request->stok,
        ]);

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('sparepart.index')->with('success', 'Sparepart berhasil ditambahkan!');
    }

    // Fungsi untuk manampilkan form edit dengan data yang sudah ada
    public function edit($id)
    {
        // cari data sparepart berdasarkan ID
        $sparepart = Sparepart::findOrFail($id);

        return view('sparepart.edit', compact('sparepart'));
    }

    // Fungsi untuk menyimpan perubahan data ke database
    public function update(Request $request, $id)
    {
        // Valikasi inputan baru
        $request->validate([
            'nama_sparepart' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
        ]);

        // Cari data lama, lalau timpa dengan data baru
        $sparepart = Sparepart::findOrFail($id);
        $sparepart->update([
            'nama_sparepart' => $request->nama_sparepart,
            'harga' => $request->harga,
            'stok' => $request->stok,
        ]);

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('sparepart.index')->with('success', 'Sparepart berhasil diperbarui!');
    }

    // Fungsi untuk menghapus data dari database
    public function destroy($id)
    {
        // cari datanya dulu
        $sparepart = Sparepart::findOrFail($id);

        // eksekusi hapus
        $sparepart->delete();

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('sparepart.index')->with('success', 'Sparepart berhasil dihapus!');
    }
}