<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use Illuminate\Http\Request;

class SparepartController extends Controller
{
    // Fungsi untuk menampilkan halaman utama sparepart
    public function index()
    {        
        // ambil semua data sparepart dari database, urukan dari yang terbaru
        $spareparts = Sparepart::orderBy('created_at', 'desc')->paginate(10);

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
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
        ]);

        // Simpan data ke database
        Sparepart::create([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
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
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
        ]);

        // Cari data lama, lalau timpa dengan data baru
        $sparepart = Sparepart::findOrFail($id);
        $sparepart->update([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
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