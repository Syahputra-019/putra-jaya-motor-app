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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['pelanggan', 'mekanik'])->latest()->paginate(10);
        return view('transaksi.index', compact('transaksis'));
    }

    public function create(Request $request)
    {
        $pelanggans = Pelanggan::all();
        $mekaniks = Mekanik::all();
        $spareparts = Sparepart::where('stok', '>', 0)->get();
        $services = Service::all();

        $booking = null;
        if ($request->has('booking_id')) {
            $booking = Booking::find($request->booking_id);
        }

        $kode_transaksi = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        return view('transaksi.create', compact('pelanggans', 'mekaniks', 'spareparts', 'kode_transaksi', 'services', 'booking'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_transaksi' => 'required|unique:transaksis',
            'tanggal' => 'required|date',
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'mekanik_id' => 'required|exists:mekaniks,id',
            'service_id' => 'required|exists:services,id',
            'keluhan' => 'nullable|string',
            'sparepart_id' => 'nullable|array',
            'sparepart_id.*' => 'nullable|exists:spareparts,id',
            'jumlah' => 'nullable|array',
            'jumlah.*' => 'nullable|integer|min:1',
            'booking_id' => 'nullable|exists:bookings,id',
        ]);

        if ($request->has('sparepart_id')) {
            foreach ($request->sparepart_id as $key => $sparepart_id) {
                $qty = (int) ($request->jumlah[$key] ?? 0);

                if ($sparepart_id && $qty > 0) {
                    $sparepart = Sparepart::findOrFail($sparepart_id);

                    if ($sparepart->stok < $qty) {
                        return back()->withInput()->with('error', 'Stok tidak cukup');
                    }
                }
            }
        }

        $transaksi = DB::transaction(function () use ($request) {
            $jasa = Service::findOrFail($request->service_id);
            $total_biaya = $jasa->harga;

            $transaksi = Transaksi::create([
                'booking_id' => $request->booking_id,
                'kode_transaksi' => $request->kode_transaksi,
                'tanggal' => $request->tanggal,
                'pelanggan_id' => $request->pelanggan_id,
                'mekanik_id' => $request->mekanik_id,
                'service_id' => $request->service_id,
                'keluhan' => $request->keluhan,
                'status' => 'selesai',
                'total_biaya' => $total_biaya,
            ]);

            if ($request->has('sparepart_id')) {
                foreach ($request->sparepart_id as $key => $sparepart_id) {
                    $qty = (int) ($request->jumlah[$key] ?? 0);

                    if ($sparepart_id && $qty > 0) {
                        $sparepart = Sparepart::findOrFail($sparepart_id);
                        $subtotal = $sparepart->harga * $qty;

                        DetailTransaksi::create([
                            'transaksi_id' => $transaksi->id,
                            'sparepart_id' => $sparepart_id,
                            'jumlah' => $qty,
                            'harga_satuan' => $sparepart->harga,
                            'sub_total' => $subtotal,
                        ]);

                        $total_biaya += $subtotal;
                    }
                }
            }

            $transaksi->update(['total_biaya' => $total_biaya]);

            if ($transaksi->booking_id) {
                Booking::whereKey($transaksi->booking_id)->update([
                    'status_pembayaran' => 'belum lunas',
                ]);
            }

            return $transaksi;
        });

        return redirect()->route('transaksi.bayar', $transaksi->id)->with('success', 'Transaksi berhasil dicatat! Silakan selesaikan pembayaran.');
    }

    public function show(Transaksi $transaksi)
    {
        //
    }

    public function edit(Transaksi $transaksi)
    {
        //
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        //
    }

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

    public function notaPublik($id)
    {
        $transaksi = Transaksi::with(['pelanggan', 'mekanik', 'detailTransaksis.sparepart', 'service'])->findOrFail($id);
        return view('transaksi.cetak', compact('transaksi'));
    }

    public function bayar($id)
    {
        $transaksi = Transaksi::with('pelanggan')->findOrFail($id);

        if ($transaksi->status_pembayaran === 'belum_bayar' && empty($transaksi->snap_token)) {
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $transaksi->kode_transaksi . '-' . time(),
                    'gross_amount' => $transaksi->total_biaya,
                ],
                'customer_details' => [
                    'first_name' => $transaksi->pelanggan->nama_pelanggan ?? 'Pelanggan Umum',
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $transaksi->snap_token = $snapToken;
            $transaksi->save();
        }

        return view('transaksi.bayar', compact('transaksi'));
    }

    public function uploadStruk(Request $request, $id)
    {
        $request->validate([
            'bukti_struk' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $transaksi = Transaksi::findOrFail($id);

        if ($request->hasFile('bukti_struk')) {
            $file = $request->file('bukti_struk');
            $nama_file = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('struk_transfer'), $nama_file);
            $transaksi->status_pembayaran = 'menunggu_konfirmasi';
            $transaksi->bukti_struk = $nama_file;
            $transaksi->save();

            $this->updateBookingPaymentStatus($transaksi, 'menunggu_konfirmasi');

            return redirect()->route('transaksi.cetak', $id)->with('success', 'Bukti transfer berhasil dikirim! Menunggu konfirmasi admin.');
        }

        return back()->with('error', 'Gagal mengupload struk.');
    }

    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            $orderId = $request->order_id;
            $kodeTransaksi = substr($orderId, 0, strrpos($orderId, '-'));
            $transaksi = Transaksi::where('kode_transaksi', $kodeTransaksi)->first();

            if (!$transaksi) {
                return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
            }

            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                $this->settleTransaksi($transaksi->id);
            } elseif ($request->transaction_status == 'cancel' || $request->transaction_status == 'deny' || $request->transaction_status == 'expire') {
                $transaksi->status_pembayaran = 'belum_bayar';
                $transaksi->save();
                $this->updateBookingPaymentStatus($transaksi, 'belum lunas');
            }
        }

        return response()->json(['message' => 'Callback diterima bro']);
    }

    public function konfirmasiPembayaran($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $this->settleTransaksi($transaksi->id);

        return redirect()->back()->with('success', 'Mantap! Pembayaran di-ACC, stok sparepart otomatis terpotong, dan nota telah dikirim via WhatsApp.');
    }

    private function kirimNotaWhatsApp($transaksi)
    {
        $noHp = $transaksi->pelanggan->no_telp ?? null;
        $token = env('FONNTE_TOKEN');

        if (!$noHp || !$token) {
            return false;
        }

        $notaUrl = URL::temporarySignedRoute('transaksi.nota', now()->addDays(7), ['id' => $transaksi->id]);

        $pesan = "Halo *" . ($transaksi->pelanggan->nama_pelanggan ?? 'Pelanggan') . "*,\n\n";
        $pesan .= "Terima kasih telah servis di *PUTRA JAYA MOTOR*.\n\n";
        $pesan .= "Detail transaksi:\n";
        $pesan .= "Kode: " . $transaksi->kode_transaksi . "\n";
        $pesan .= "Total Tagihan: Rp " . number_format($transaksi->total_biaya, 0, ',', '.') . "\n";
        $pesan .= "Status: LUNAS\n\n";
        $pesan .= "Cek e-nota Anda di sini:\n";
        $pesan .= $notaUrl . "\n\n";
        $pesan .= "Semoga motornya awet dan tarikannya makin ngacir!";

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'target' => $noHp,
                'message' => $pesan,
                'countryCode' => '62',
            ],
            CURLOPT_HTTPHEADER => [
                "Authorization: $token",
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    private function kirimNotifStokWA($sparepart)
    {
        $target = env('ADMIN_PHONE');
        $token = env('FONNTE_TOKEN');

        if (!$target || !$token) return;

        $pesan = "ALERT STOK BENGKEL PUTRA JAYA\n\n";
        $pesan .= "Bos, stok barang ini sudah mau habis. Segera restock:\n\n";
        $pesan .= "Nama Barang: " . $sparepart->nama_sparepart . "\n";
        $pesan .= "Sisa Stok: " . $sparepart->stok . "\n\n";
        $pesan .= "Pesan otomatis dari sistem aplikasi bengkel.";

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'target' => $target,
                'message' => $pesan,
                'delay' => '1',
            ],
            CURLOPT_HTTPHEADER => [
                "Authorization: $token",
            ],
        ]);

        curl_exec($curl);
        curl_close($curl);
    }

    private function settleTransaksi(int $transaksiId): void
    {
        $lowStockSpareparts = [];
        $shouldSendNota = false;

        $transaksi = DB::transaction(function () use ($transaksiId, &$lowStockSpareparts, &$shouldSendNota) {
            $transaksi = Transaksi::with(['detailTransaksis', 'pelanggan', 'mekanik', 'service', 'booking'])
                ->lockForUpdate()
                ->findOrFail($transaksiId);

            if ($transaksi->status_pembayaran === 'lunas') {
                return $transaksi->load(['detailTransaksis.sparepart']);
            }

            foreach ($transaksi->detailTransaksis as $detail) {
                $sparepart = Sparepart::lockForUpdate()->find($detail->sparepart_id);

                if ($sparepart) {
                    $sparepart->stok = max(0, $sparepart->stok - $detail->jumlah);
                    $sparepart->save();

                    if ($sparepart->stok <= 5) {
                        $lowStockSpareparts[] = $sparepart->fresh();
                    }
                }
            }

            $transaksi->status_pembayaran = 'lunas';
            $transaksi->save();

            $this->updateBookingPaymentStatus($transaksi, 'lunas');
            $shouldSendNota = true;

            return $transaksi->load(['detailTransaksis.sparepart']);
        });

        foreach ($lowStockSpareparts as $sparepart) {
            $this->kirimNotifStokWA($sparepart);
        }

        if ($shouldSendNota) {
            $this->kirimNotaWhatsApp($transaksi);
        }
    }

    private function updateBookingPaymentStatus(Transaksi $transaksi, string $status): void
    {
        if ($transaksi->booking_id) {
            Booking::whereKey($transaksi->booking_id)->update([
                'status_pembayaran' => $status,
            ]);
        }
    }
}
