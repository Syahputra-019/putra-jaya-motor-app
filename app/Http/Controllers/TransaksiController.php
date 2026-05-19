<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\DetailTransaksi;
use App\Models\Mekanik;
use App\Models\Pelanggan;
use App\Models\Service;
use App\Models\Sparepart;
use App\Models\Transaksi;
use App\Support\BookingTransactionBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['pelanggan', 'mekanik', 'booking'])->latest()->paginate(10);
        return view('transaksi.index', compact('transaksis'));
    }

    public function create(Request $request, BookingTransactionBuilder $bookingTransactionBuilder)
    {
        $pelanggans = Pelanggan::all();
        $mekaniks = Mekanik::all();
        $spareparts = Sparepart::where('stok', '>', 0)->orderBy('nama_sparepart')->get();
        $services = Service::orderBy('nama_service')->get();
        $bookingsSiapTransaksi = Booking::with(['pelanggan', 'mekanik', 'transaksi'])
            ->where('status', 'selesai')
            ->orderByDesc('jadwal_booking')
            ->get();

        $booking = null;
        $draftServiceRows = [];
        $draftSparepartRows = [];
        $draftCustomRows = [];

        if ($request->has('booking_id')) {
            $booking = Booking::with(['pelanggan', 'mekanik', 'transaksi'])->findOrFail($request->booking_id);

            if ($booking->transaksi) {
                $route = $booking->transaksi->status_pembayaran === 'lunas' ? 'transaksi.cetak' : 'transaksi.bayar';

                return redirect()
                    ->route($route, $booking->transaksi->id)
                    ->with('success', 'Transaksi untuk booking ini sudah tersedia. Silakan lanjutkan proses pembayarannya.');
            }

            $draft = $bookingTransactionBuilder->build($booking, $services, $spareparts);
            $draftServiceRows = $draft['service_rows'];
            $draftSparepartRows = $draft['sparepart_rows'];
            $draftCustomRows = $draft['custom_rows'];
        }

        $kode_transaksi = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        return view('transaksi.create', compact(
            'pelanggans',
            'mekaniks',
            'spareparts',
            'kode_transaksi',
            'services',
            'booking',
            'bookingsSiapTransaksi',
            'draftServiceRows',
            'draftSparepartRows',
            'draftCustomRows',
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_transaksi' => 'required|unique:transaksis',
            'tanggal' => 'required|date',
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'mekanik_id' => 'required|exists:mekaniks,id',
            'keluhan' => 'nullable|string',
            'service_id' => 'nullable|array',
            'service_id.*' => 'nullable|exists:services,id',
            'service_qty' => 'nullable|array',
            'service_qty.*' => 'nullable|integer|min:1',
            'sparepart_id' => 'nullable|array',
            'sparepart_id.*' => 'nullable|exists:spareparts,id',
            'jumlah' => 'nullable|array',
            'jumlah.*' => 'nullable|integer|min:1',
            'custom_item_type' => 'nullable|array',
            'custom_item_type.*' => 'nullable|in:jasa,part',
            'custom_item_name' => 'nullable|array',
            'custom_item_name.*' => 'nullable|string|max:255',
            'custom_item_price' => 'nullable|array',
            'custom_item_price.*' => 'nullable|numeric|min:0',
            'custom_item_qty' => 'nullable|array',
            'custom_item_qty.*' => 'nullable|integer|min:1',
            'booking_id' => 'nullable|exists:bookings,id',
        ]);

        if ($request->filled('booking_id')) {
            $transaksiEksisting = Transaksi::where('booking_id', $request->booking_id)->first();

            if ($transaksiEksisting) {
                $route = $transaksiEksisting->status_pembayaran === 'lunas' ? 'transaksi.cetak' : 'transaksi.bayar';

                return redirect()
                    ->route($route, $transaksiEksisting->id)
                    ->with('success', 'Booking ini sudah memiliki transaksi. Proses dilanjutkan ke transaksi yang sudah ada.');
            }
        }

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

        $serviceIds = array_filter($request->input('service_id', []), fn ($value) => filled($value));
        $sparepartIds = array_filter($request->input('sparepart_id', []), fn ($value) => filled($value));
        $customNames = collect($request->input('custom_item_name', []))
            ->filter(fn ($value) => trim((string) $value) !== '');

        if (empty($serviceIds) && empty($sparepartIds) && $customNames->isEmpty()) {
            return back()
                ->withInput()
                ->withErrors([
                    'items' => 'Tambahkan minimal satu jasa servis, sparepart bengkel, atau item manual sebelum menyimpan transaksi.',
                ]);
        }

        $transaksi = DB::transaction(function () use ($request) {
            $total_biaya = 0;
            $detailItems = [];
            $serviceIds = [];

            $transaksi = Transaksi::create([
                'booking_id' => $request->booking_id,
                'kode_transaksi' => $request->kode_transaksi,
                'tanggal' => $request->tanggal,
                'pelanggan_id' => $request->pelanggan_id,
                'mekanik_id' => $request->mekanik_id,
                'service_id' => null,
                'detail_items' => [],
                'keluhan' => $request->keluhan,
                'status' => 'selesai',
                'total_biaya' => $total_biaya,
            ]);

            foreach ($request->input('service_id', []) as $key => $serviceId) {
                if (blank($serviceId)) {
                    continue;
                }

                $qty = (int) ($request->input("service_qty.$key") ?? 1);
                $service = Service::findOrFail($serviceId);
                $subtotal = $service->harga * $qty;

                $detailItems[] = [
                    'jenis' => 'service',
                    'service_id' => (int) $service->id,
                    'nama' => $service->nama_service,
                    'jumlah' => $qty,
                    'harga' => (int) $service->harga,
                    'subtotal' => $subtotal,
                ];
                $serviceIds[] = (int) $service->id;
                $total_biaya += $subtotal;
            }

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

            foreach ($request->input('custom_item_name', []) as $key => $namaItem) {
                $namaItem = trim((string) $namaItem);

                if ($namaItem === '') {
                    continue;
                }

                $qty = (int) ($request->input("custom_item_qty.$key") ?? 1);
                $harga = (int) ($request->input("custom_item_price.$key") ?? 0);
                $jenis = $request->input("custom_item_type.$key") === 'jasa' ? 'custom_service' : 'custom_part';
                $subtotal = $harga * $qty;

                $detailItems[] = [
                    'jenis' => $jenis,
                    'nama' => $namaItem,
                    'jumlah' => $qty,
                    'harga' => $harga,
                    'subtotal' => $subtotal,
                ];
                $total_biaya += $subtotal;
            }

            $transaksi->update([
                'service_id' => $serviceIds[0] ?? null,
                'detail_items' => $detailItems,
                'total_biaya' => $total_biaya,
            ]);

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
        $transaksi = Transaksi::with(['pelanggan', 'mekanik', 'detailTransaksis.sparepart', 'service', 'booking'])->findOrFail($id);
        $this->authorizeTransactionAccess($transaksi);
        return view('transaksi.cetak', compact('transaksi'));
    }

    public function notaPublik($id)
    {
        $transaksi = Transaksi::with(['pelanggan', 'mekanik', 'detailTransaksis.sparepart', 'service', 'booking'])->findOrFail($id);
        return view('transaksi.cetak', compact('transaksi'));
    }

    public function bayar($id)
    {
        $transaksi = Transaksi::with(['pelanggan', 'detailTransaksis.sparepart', 'service', 'booking'])->findOrFail($id);
        $this->authorizeTransactionAccess($transaksi);
        $midtransEnabled = filled(env('MIDTRANS_SERVER_KEY')) && filled(env('MIDTRANS_CLIENT_KEY'));

        if ($midtransEnabled && $transaksi->status_pembayaran === 'belum_bayar' && empty($transaksi->snap_token)) {
            try {
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
            } catch (\Throwable $e) {
                Log::error('Gagal membuat Snap Token Midtrans: ' . $e->getMessage());
                session()->flash('error', 'Pembayaran otomatis sedang tidak tersedia. Silakan gunakan transfer manual.');
            }
        }

        return view('transaksi.bayar', compact('transaksi', 'midtransEnabled'));
    }

    public function uploadStruk(Request $request, $id)
    {
        $request->validate([
            'bukti_struk' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $this->authorizeTransactionAccess($transaksi);

        if ($transaksi->status_pembayaran !== 'belum_bayar') {
            return back()->with('error', 'Transaksi ini tidak dapat menerima bukti transfer baru.');
        }

        if ($request->hasFile('bukti_struk')) {
            $file = $request->file('bukti_struk');
            $nama_file = time() . '_' . $file->getClientOriginalName();

            // Simpan ke storage publik agar struk bisa diakses dari halaman transaksi.
            $file->storeAs('public/struk_transfer', $nama_file);

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
        Log::info('Webhook Midtrans Masuk Bro: ', $request->all());

        $serverKey = env('MIDTRANS_SERVER_KEY');
        $grossAmount = $request->gross_amount;
        $hashed = hash('sha512', $request->order_id . $request->status_code . $grossAmount . $serverKey);

        if ($hashed == $request->signature_key) {
            Log::info('Signature Key Midtrans Cocok!');

            $orderId = $request->order_id;
            $kodeTransaksi = substr($orderId, 0, strrpos($orderId, '-'));
            $transaksi = Transaksi::where('kode_transaksi', $kodeTransaksi)->first();

            if (!$transaksi) {
                Log::error('Transaksi tidak ditemukan dengan kode: ' . $kodeTransaksi);
                return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
            }

            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                Log::info('Proses update status jadi LUNAS untuk transaksi: ' . $kodeTransaksi);
                $this->settleTransaksi($transaksi->id);
                Log::info('Settle Transaksi Berhasil Dieksekusi!');
            } elseif (in_array($request->transaction_status, ['cancel', 'deny', 'expire'])) {
                $transaksi->status_pembayaran = 'belum_bayar';
                $transaksi->save();
                $this->updateBookingPaymentStatus($transaksi, 'belum lunas');
            }
        } else {
            Log::error('Signature Key Midtrans GAGAL/TIDAK COCOK bro!');
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
        try {
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
                try {
                    $this->kirimNotifStokWA($sparepart);
                } catch (\Exception $e) {
                    Log::error("Gagal kirim WA Stok: " . $e->getMessage());
                }
            }

            if ($shouldSendNota) {
                try {
                    $this->kirimNotaWhatsApp($transaksi);
                } catch (\Exception $e) {
                    Log::error("Gagal kirim WA Nota: " . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            Log::error("CRITICAL ERROR di settleTransaksi: " . $e->getMessage());
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

    public function riwayatServis()
    {
        $user = auth()->user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        if (!$pelanggan) {
            return redirect()->back()->with('error', 'Data pelanggan tidak ditemukan.');
        }

        // Menggunakan eager loading (with) agar tidak terjadi N+1 query problem
        $transaksis = Transaksi::with(['mekanik', 'service', 'detailTransaksis.sparepart', 'booking'])
            ->where(function ($query) use ($pelanggan, $user) {
                // 1. Cocokkan dengan pelanggan_id user saat ini
                $query->where('pelanggan_id', $pelanggan->id)
                    // 2. Transaksi dari booking milik user (jika admin lupa ubah dropdown pelanggan)
                    ->orWhereHas('booking', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })
                    // 3. Transaksi saat user masih jadi Guest (dicocokkan lewat nomor WA yang sama)
                    ->orWhereHas('pelanggan', function ($q) use ($pelanggan) {
                        $q->where('no_telp', $pelanggan->no_telp)->whereNotNull('no_telp');
                    });
            })
            ->latest()
            ->get();

        return view('user.history.riwayat-servis', compact('transaksis'));
    }

    private function authorizeTransactionAccess(Transaksi $transaksi): void
    {
        $user = auth()->user();

        if (!$user) {
            abort(403);
        }

        if ($user->role === 'admin') {
            return;
        }

        $pelanggan = Pelanggan::where('user_id', $user->id)->first();
        $isOwner = $transaksi->booking?->user_id === $user->id || 
                   $transaksi->pelanggan_id === $pelanggan?->id || 
                   (filled($transaksi->pelanggan?->no_telp) && $transaksi->pelanggan?->no_telp === $pelanggan?->no_telp);

        if ($user->role === 'pelanggan' && $isOwner) {
            return;
        }

        abort(403);
    }
}
