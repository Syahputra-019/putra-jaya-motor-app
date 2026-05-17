<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimonialController extends Controller
{
    // ADMIN: Menampilkan daftar testimonial
    public function index()
    {
        $testimonials = Testimonial::with('user')->latest()->paginate(10);
        return view('testimonial.index', compact('testimonials'));
    }

    // PELANGGAN: Menampilkan form tambah testimonial
    public function create()
    {
        return view('user.testimonial.create');
    }

    // PELANGGAN: Menyimpan testimonial
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'isi_testimonial' => 'required|string|min:5',
        ]);

        Testimonial::create([
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'isi_testimonial' => $request->isi_testimonial,
            'status' => 'pending',
        ]);

        return redirect()->route('testimonial.create')->with('success', 'Testimonial berhasil dikirim dan menunggu persetujuan admin.');
    }

    // ADMIN: Update status testimonial
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);
        Testimonial::findOrFail($id)->update(['status' => $request->status]);
        return back()->with('success', 'Status testimonial berhasil diperbarui.');
    }

    // ADMIN: Hapus testimonial
    public function destroy($id)
    {
        Testimonial::findOrFail($id)->delete();
        return back()->with('success', 'Testimonial berhasil dihapus.');
    }
}
