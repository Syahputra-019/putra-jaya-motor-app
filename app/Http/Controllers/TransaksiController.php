<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\DetailTransaksi;
use App\Models\Mekanik;
use App\Models\Pelanggan;
use App\Models\Service;
use App\Models\Sparepart;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ambil data transaksi beserta nama pelanggan dan mekanikknya
        $transaksis = Transaksi::with(['pelanggan', 'mekanik'])->latest()->paginate(10);
        return view('transaksi.index', compact('transaksis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // lempar data master ke view buat dropduwn form
        $pelanggans = Pelanggan::all();
        $mekaniks = Mekanik::all();
        $spareparts = Sparepart::where('stok', '>', 0)->get();
        $services = Service::all();

        $booking = null;
    if ($request->has('booking_id')) {
        $booking = Booking::find($request->booking_id);
    }

        // bikin kode transaksi otomatis
        $kode_transaksi = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(4));
    
        return view('transaksi.create', compact('pelanggans', 'mekaniks', 'spareparts', 'kode_transaksi', 'services', 'booking'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validasi input
        $request->validate([
            'kode_transaksi' => 'required|unique:transaksis',
            'tanggal' => 'required|date',
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'mekanik_id' => 'required|exists:mekaniks,id',
            'service_id' => 'required|exists:services,id',
            'keluhan' => 'nullable|string',
            'sparepart_id' => 'nullable|array',
            'jumlah' => 'nullable|array',
            'booking_id' => 'nullable'
        ]);

        // ambil data jasa servis
        $jasa = Service::findOrFail($request->service_id);

        // modal awal total biaya diambil dari harga jasa servis
        $total_biaya = $jasa->harga;

        //  simpan data transaksi induk dlu
        $transaksi = Transaksi::create([
            'kode_transaksi' => $request->kode_transaksi,
            'tanggal' => $request->tanggal,
            'pelanggan_id' => $request->pelanggan_id,
            'mekanik_id' => $request->mekanik_id,
            'service_id' => $request->service_id,
            'keluhan' => $request->keluhan,
            'status' => 'Selesai',
            'total_biaya' => $total_biaya,
        ]);

        // simpan detail transaksi

        if ($request->has('sparepart_id')) {
            foreach ($request->sparepart_id as $key => $sparepart_id) {
                // Cek kalau ID sparepart ada dan jumlahnya lebih dari 0
                if ($sparepart_id && $request->jumlah[$key] > 0) {
                    $sparepart = Sparepart::find($sparepart_id);
                    $subtotal = $sparepart->harga * $request->jumlah[$key];

                    DetailTransaksi::create([
                        'transaksi_id'   => $transaksi->id,
                        'sparepart_id'   => $sparepart_id,
                        'jumlah'         => $request->jumlah[$key],
                        'harga_satuan'   => $sparepart->harga,
                        'sub_total'       => $subtotal,
                    ]);

                    $total_biaya += $subtotal;
                }
            }
        }

        // Update total biaya transaksi
        $transaksi->update(['total_biaya' => $total_biaya]);

        if ($request->has('booking_id') && $request->booking_id != null) {
        \App\Models\Booking::where('id', $request->booking_id)->update([
            'status_pembayaran' => 'lunas' // atau sesuaikan dengan nama kolom status lu
        ]);
    }

        return redirect()->route('transaksi.bayar', $transaksi->id)->with('success', 'Transaksi berhasil dicatat! Silakan selesaikan pembayaran.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    public function cetak($id)
    {
        $transaksi = Transaksi::with(['pelanggan', 'mekanik', 'detailTransaksis.sparepart', 'service'])->findOrFail($id);
        return view('transaksi.cetak', compact('transaksi'));
    }

    public function bayar($id)
    {
        // Cari data transaksi berdasarkan ID
        $transaksi = Transaksi::with('pelanggan')->findOrFail($id);

        // Cek kalau statusnya masih belum bayar dan tokennya belum ada
        if ($transaksi->status_pembayaran === 'belum_bayar' && empty($transaksi->snap_token)) {
            
            // 1. Set Konfigurasi Midtrans dari file .env
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            // 2. Siapin data yang mau dikirim ke Midtrans
            $params = [
                'transaction_details' => [
                    'order_id' => $transaksi->kode_transaksi . '-' . time(), 
                    'gross_amount' => $transaksi->total_biaya,
                ],
                'customer_details' => [
                    'first_name' => $transaksi->pelanggan->nama_pelanggan ?? 'Pelanggan Umum',
                ]
            ];

            // 3. Minta Snap Token ke Midtrans
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            // 4. Simpan token ke database biar bisa dipanggil di tampilan HTML
            $transaksi->snap_token = $snapToken;
            $transaksi->save();
        }

        // Lempar data ke halaman view pembayaran
        return view('transaksi.bayar', compact('transaksi'));
    }

    public function uploadStruk(Request $request, $id)
    {

        // Validasi input
        $request->validate([
            'bukti_struk' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $transaksi = Transaksi::findOrFail($id);

        // Simpan file struk ke storage
        if ($request->hasFile('bukti_struk')) {
            $file = $request->file('bukti_struk');
            $nama_file = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('struk_transfer'), $nama_file);
            $transaksi->status_pembayaran = 'menunggu_konfirmasi';
            $transaksi->bukti_struk = $nama_file;
            $transaksi->save();

            return redirect()->route('transaksi.cetak', $id)->with('success', 'Bukti transfer berhasil dikirim! Menunggu konfirmasi admin.');
        }

        return back()->with('error', 'Gagal mengupload struk.');
    }

    public function callback(Request $request)
    {
        // 1. Ambil Server Key buat cocokin keamanan
        $serverKey = env('MIDTRANS_SERVER_KEY');
        
        // 2. Rumus validasi resmi dari Midtrans biar nggak ada hacker yang nipu
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        // 3. Kalau signature-nya beneran cocok (asli dari Midtrans)
        if ($hashed == $request->signature_key) {
            
            // Ambil kode transaksi asli 
            $order_id = $request->order_id;
            $kode_transaksi = substr($order_id, 0, strrpos($order_id, '-'));
            
            // 🔥 PERBAIKAN: Load 'pelanggan' di sini biar no_telp-nya kebaca pas mau kirim WA
            $transaksi = Transaksi::with(['detailTransaksis.sparepart', 'pelanggan'])->where('kode_transaksi', $kode_transaksi)->first();
            
            // 4. Update status berdasarkan laporan Midtrans
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                
                // Kalau status sebelumnya bukan lunas, potong stok dan kirim WA!
                if ($transaksi->status_pembayaran != 'lunas') {
                    
                    foreach ($transaksi->detailTransaksis as $detail) {
                        $sparepart = $detail->sparepart;
                        if ($sparepart) {
                            $sparepart->stok -= $detail->jumlah;
                            $sparepart->save();
                                if ($sparepart->stok <= 5) {
                                $this->kirimNotifStokWA($sparepart);
                            }
                        }
                    }
                    
                    // Ubah dan simpan status lunas
                    $transaksi->status_pembayaran = 'lunas';
                    $transaksi->save(); 

                    // 🔥 EKSEKUSI KIRIM WA OTOMATIS DARI MIDTRANS DI SINI! 🔥
                    $this->kirimNotaWhatsApp($transaksi);
                }

            } elseif ($request->transaction_status == 'cancel' || $request->transaction_status == 'deny' || $request->transaction_status == 'expire') {
                $transaksi->status_pembayaran = 'belum_bayar'; 
                $transaksi->save();
            }
        }
        
        // 5. Kasih jempol (Response 200) ke Midtrans
        return response()->json(['message' => 'Callback diterima bro']);
    }

    public function konfirmasiPembayaran($id)
    {
        $transaksi = Transaksi::with('detailTransaksis.sparepart')->findOrFail($id);
        
        // Pastiin cuma diproses kalau belum lunas (biar stok gak kepotong dobel)
        if ($transaksi->status_pembayaran != 'lunas') {
            
            // Looping buat motong stok tiap sparepart yang dibeli
            foreach ($transaksi->detailTransaksis as $detail) {
                $sparepart = $detail->sparepart;
                if ($sparepart) {
                    $sparepart->stok -= $detail->jumlah; // Kurangi stok
                    $sparepart->save();
                            if ($sparepart->stok <= 5) {
                        $this->kirimNotifStokWA($sparepart);
                    }
                }
            }

            // Ubah status jadi lunas
            $transaksi->status_pembayaran = 'lunas';
            $transaksi->save();

            $this->kirimNotaWhatsApp($transaksi);
        }

        return redirect()->back()->with('success', 'Mantap! Pembayaran di-ACC, stok sparepart otomatis terpotong, dan nota telah dikirim via WhatsApp.');
    }

    private function kirimNotaWhatsApp($transaksi)
    {
        // GANTI DI SINI: Pakai no_telp sesuai kolom di tabel pelanggan lu
        $no_hp = $transaksi->pelanggan->no_telp ?? null; 
        
        if (!$no_hp) return false; 

        // Isi pesan WA
        $pesan = "Halo *" . ($transaksi->pelanggan->nama_pelanggan ?? 'Pelanggan') . "*,\n\n";
        $pesan .= "Terima kasih telah servis di *PUTRA JAYA MOTOR*.\n\n";
        $pesan .= "📝 *Detail Transaksi:*\n";
        $pesan .= "Kode: " . $transaksi->kode_transaksi . "\n";
        $pesan .= "Total Tagihan: *Rp " . number_format($transaksi->total_biaya, 0, ',', '.') . "*\n";
        $pesan .= "Status: ✅ *LUNAS*\n\n";
        
        $pesan .= "Cek e-Nota lengkap Anda di sini:\n";
        $pesan .= route('transaksi.cetak', $transaksi->id) . "\n\n";
        
        $pesan .= "Semoga motornya awet dan tarikannya makin ngacir! 🏍️💨";

        // Eksekusi kirim via Fonnte
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $no_hp,
                'message' => $pesan,
                'countryCode' => '62',
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: dufDotss7Jzn81XtiiGV' // Tempel token Fonnte lu di sini
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    private function kirimNotifStokWA($sparepart)
    {
        $target = env('ADMIN_PHONE');
        $token = env('FONNTE_TOKEN');

        // Kalau nomor bos atau token gak disetting, batalin aja biar gak error
        if (!$target || !$token) return;

        $pesan = "⚠️ *ALERT STOK BENGKEL PUTRA JAYA* ⚠️\n\n";
        $pesan .= "Bos, stok barang ini udah mau abis nih! Buruan restock ya:\n\n";
        $pesan .= "🔧 Nama Barang: *" . $sparepart->nama_sparepart . "*\n";
        $pesan .= "📦 Sisa Stok: *" . $sparepart->stok . "*\n\n";
        $pesan .= "_Pesan otomatis dari Sistem Aplikasi Bengkel_";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $target,
                'message' => $pesan,
                'delay' => '1',
            ),
            CURLOPT_HTTPHEADER => array(
                "Authorization: $token"
            ),
        ));

        curl_exec($curl);
        curl_close($curl);
    }
}
