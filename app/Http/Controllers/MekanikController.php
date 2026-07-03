<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Mekanik;
use App\Models\Sparepart;
use App\Notifications\BookingStatusNotification;
use App\Notifications\SparepartRecommendationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
        $spareparts = Sparepart::query()
            ->where('stok', '>', 0)
            ->orderBy('nama_sparepart', 'asc')
            ->get(['id', 'nama_sparepart', 'stok', 'harga']);

        // 1. CEK JIKA YANG LOGIN ADALAH ADMIN
        if ($user->role === 'admin') {
            // Admin bisa lihat SEMUA motor yang lagi antre & diproses oleh SEMUA mekanik
            $bookings = Booking::with(['pelanggan', 'mekanik'])
                ->whereIn('status', ['menunggu', 'diproses'])
                ->orderBy('tanggal_antrean', 'asc')
                ->orderBy('nomor_antrean', 'asc')
                ->orderBy('jadwal_booking', 'asc')
                ->get();

            return view('mekanik.jadwal', compact('bookings', 'spareparts'));
        }

        // 2. JIKA YANG LOGIN ADALAH MEKANIK
        $mekanik = Mekanik::where('user_id', $user->id)->first();

        if (!$mekanik) {
            return view('mekanik.jadwal', ['bookings' => collect([]), 'spareparts' => $spareparts])
                ->with('error', 'Akun login lu belum disambungkan ke data mekanik.');
        }

        // 2. Ambil booking yang ditugaskan ke ID mekanik tersebut
        $bookings = Booking::with('pelanggan')
            ->where('mekanik_id', $mekanik->id)
            ->whereIn('status', ['menunggu', 'diproses'])
            ->orderBy('tanggal_antrean', 'asc')
            ->orderBy('nomor_antrean', 'asc')
            ->orderBy('jadwal_booking', 'asc')
            ->get();

        return view('mekanik.jadwal', compact('bookings', 'spareparts'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $mekanik = Mekanik::where('user_id', auth()->id())->first();

        if (auth()->user()->role === 'mekanik' && (!$mekanik || $booking->mekanik_id !== $mekanik->id)) {
            abort(403);
        }

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

        // Kirim update status ke user/pelanggan (Pastikan bukan guest booking)
        if ($booking->user_id) {
            $booking->user->notify(new BookingStatusNotification($booking));
        }

        return redirect()->back()->with('success', 'Mantap! Status motor berhasil diupdate.');
    }

    public function kirimRekomendasi(Request $request, Booking $booking)
    {
        $mekanik = Mekanik::where('user_id', auth()->id())->first();

        if (auth()->user()->role === 'mekanik' && (!$mekanik || $booking->mekanik_id !== $mekanik->id)) {
            abort(403);
        }

        $request->validate([
            'sparepart_id' => 'nullable|array',
            'sparepart_id.*' => 'nullable|exists:spareparts,id',
            'jumlah' => 'nullable|array',
            'jumlah.*' => 'nullable|integer|min:1',
            'nama_part_luar' => 'nullable|array',
            'nama_part_luar.*' => 'nullable|string|max:255',
            'harga_part_luar' => 'nullable|array',
            'harga_part_luar.*' => 'nullable|numeric|min:0',
            'jumlah_luar' => 'nullable|array',
            'jumlah_luar.*' => 'nullable|integer|min:1',
            'nama_jasa' => 'nullable|array',
            'nama_jasa.*' => 'nullable|string|max:255',
            'harga_jasa' => 'nullable|array',
            'harga_jasa.*' => 'nullable|numeric|min:0',
        ]);

        $rekomendasiBaru = [];

        foreach ($request->input('sparepart_id', []) as $index => $id) {
            if (blank($id)) {
                continue;
            }

            $sparepart = Sparepart::find($id);
            $jumlah = (int) ($request->input("jumlah.$index") ?? 0);

            if (!$sparepart || $jumlah < 1) {
                continue;
            }

            $rekomendasiBaru[] = [
                'id' => $sparepart->id,
                'nama' => $sparepart->nama_sparepart,
                'harga' => (int) $sparepart->harga,
                'jumlah' => $jumlah,
                'tipe' => 'bengkel',
            ];
        }

        foreach ($request->input('nama_part_luar', []) as $index => $namaPartLuar) {
            $namaPartLuar = trim((string) $namaPartLuar);

            if ($namaPartLuar === '') {
                continue;
            }

            $harga = (int) ($request->input("harga_part_luar.$index") ?? 0);
            $jumlah = (int) ($request->input("jumlah_luar.$index") ?? 0);

            if ($jumlah < 1 || $harga < 0) {
                continue;
            }

            $rekomendasiBaru[] = [
                'id' => null,
                'nama' => $namaPartLuar,
                'harga' => $harga,
                'jumlah' => $jumlah,
                'tipe' => 'custom',
            ];
        }

        foreach ($request->input('nama_jasa', []) as $index => $namaJasa) {
            $namaJasa = trim((string) $namaJasa);

            if ($namaJasa === '') {
                continue;
            }

            $harga = (int) ($request->input("harga_jasa.$index") ?? 0);

            if ($harga < 0) {
                continue;
            }

            $rekomendasiBaru[] = [
                'id' => null,
                'nama' => $namaJasa,
                'harga' => $harga,
                'jumlah' => 1, // Jasa otomatis berjumlah 1
                'tipe' => 'jasa',
            ];
        }

        if (empty($rekomendasiBaru)) {
            throw ValidationException::withMessages([
                'rekomendasi' => 'Tambahkan minimal satu sparepart bengkel, part luar, atau jasa sebelum mengirim rekomendasi.',
            ]);
        }

        $rekomendasiGabungan = $this->gabungkanRekomendasi(
            $booking->rekomendasi_sparepart ?? [],
            $rekomendasiBaru,
        );

        $booking->update([
            'rekomendasi_sparepart' => $rekomendasiGabungan,
            'status_konfirmasi' => 'pending',
        ]);

        // Kirim notifikasi konfirmasi part ke pelanggan
        if ($booking->user_id) {
            $booking->user->notify(new SparepartRecommendationNotification($booking));
        }

        return redirect()->back()->with('success', 'Rekomendasi perbaikan berhasil dikirim ke pelanggan.');
    }

    private function gabungkanRekomendasi(array $rekomendasiSaatIni, array $rekomendasiBaru): array
    {
        $hasil = [];

        foreach (array_merge($rekomendasiSaatIni, $rekomendasiBaru) as $item) {
            $data = [
                'id' => $item['id'] ?? null,
                'nama' => $item['nama'] ?? '',
                'harga' => (int) ($item['harga'] ?? 0),
                'jumlah' => (int) ($item['jumlah'] ?? 0),
                'tipe' => $item['tipe'] ?? (($item['id'] ?? null) ? 'bengkel' : 'custom'),
            ];

            if ($data['nama'] === '' || $data['jumlah'] < 1) {
                continue;
            }

            $kunci = $this->buatKunciRekomendasi($data);

            if (isset($hasil[$kunci])) {
                $hasil[$kunci]['jumlah'] += $data['jumlah'];
                continue;
            }

            $hasil[$kunci] = $data;
        }

        return array_values($hasil);
    }

    private function buatKunciRekomendasi(array $item): string
    {
        if (!empty($item['id'])) {
            return 'sparepart:' . $item['id'];
        }

        return 'custom:' . Str::lower(trim($item['nama'])) . ':' . (int) ($item['harga'] ?? 0);
    }
}
