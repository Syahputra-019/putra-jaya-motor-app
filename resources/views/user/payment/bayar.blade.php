@extends('components.app')

@section('title', 'Pembayaran')

@php
    $metodeLabel =
        [
            'cash' => 'Admin Kasir',
            'transfer_manual' => 'Transfer Manual',
            'midtrans' => 'Online Midtrans',
        ][$transaksi->metode_pembayaran] ?? 'Belum Dipilih';

    if ($transaksi->status_pembayaran === 'belum_bayar') {
        $metodeLabel = 'Belum Dipilih';
    } elseif ($transaksi->bukti_struk) {
        $metodeLabel = 'Transfer Manual';
    }

    $initialCheckout = null;
    $midtransHost =
        $midtransConfig['is_production'] ?? false ? 'https://app.midtrans.com' : 'https://app.sandbox.midtrans.com';
@endphp

@section('styles')
    @if ($midtransEnabled && $transaksi->status_pembayaran === 'belum_bayar')
        <link rel="preconnect" href="{{ $midtransHost }}">
        <link rel="dns-prefetch" href="{{ $midtransHost }}">
    @endif
@endsection

@section('content')
    <section class="pb-16 pt-10">
        <div class="container mx-auto max-w-6xl px-4">
            <div class="page-header">
                <div class="page-header-split">
                    <p class="page-kicker">Payment Center</p>
                    <h1 class="page-title">Pilih metode pembayaran</h1>
                    <p class="page-description">Selesaikan pembayaran transaksi <span
                            class="font-bold text-[color:var(--brand-navy-800)]">{{ $transaksi->kode_transaksi }}</span>.
                    </p>
                </div>
            </div>

            <div class="surface-card">
                <div class="grid gap-4 rounded-[28px] border border-slate-100 bg-slate-50/80 p-5 md:grid-cols-4">
                    <div>
                        <div class="page-kicker">Total Tagihan</div>
                        <div class="mt-2 text-3xl font-bold text-slate-950">Rp
                            {{ number_format($transaksi->total_biaya, 0, ',', '.') }}</div>
                    </div>
                    <div>
                        <div class="page-kicker">Pelanggan</div>
                        <div class="mt-2 text-xl font-bold text-slate-950">
                            {{ $transaksi->pelanggan->nama_pelanggan ?? 'Umum' }}</div>
                    </div>
                    <div>
                        <div class="page-kicker">Status</div>
                        <div class="mt-2">
                            <span
                                class="badge {{ $transaksi->status_pembayaran === 'lunas' ? 'badge-success' : 'badge-warning' }}">
                                {{ str_replace('_', ' ', $transaksi->status_pembayaran) }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <div class="page-kicker">Metode</div>
                        <div class="mt-2">
                            <span class="badge badge-info">{{ $metodeLabel }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    <div class="font-black">OK</div>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    <div class="font-black">!</div>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-[1.15fr_0.85fr]">
                <div class="surface-card">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="page-kicker">Rincian Tagihan</p>
                            <h2 class="mt-2 text-2xl font-bold text-slate-950">Item transaksi yang akan dibayar</h2>
                        </div>
                        <span class="badge badge-info">{{ $transaksi->line_items->count() }} item</span>
                    </div>

                    <div class="mt-6 space-y-3">
                        @foreach ($transaksi->line_items as $item)
                            <div
                                class="flex items-start justify-between gap-4 rounded-[20px] border border-slate-100 bg-slate-50/80 p-4">
                                <div>
                                    <div class="font-semibold text-slate-900">{{ $item['nama'] }}</div>
                                    <div class="mt-1 text-sm leading-6 text-slate-500">
                                        @if ($item['jenis'] === 'service')
                                            Jasa servis
                                        @elseif ($item['jenis'] === 'sparepart')
                                            Sparepart bengkel
                                        @elseif ($item['jenis'] === 'custom_service')
                                            Jasa custom
                                        @else
                                            Part luar / item manual
                                        @endif
                                        - {{ $item['jumlah'] }} x Rp {{ number_format($item['harga'], 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="text-right font-semibold text-slate-900">
                                    Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-6">
                    @if ($transaksi->status_pembayaran === 'belum_bayar')
                        @if ($midtransEnabled)
                            <div class="surface-card">
                                <div class="feature-icon">M</div>
                                <h2 class="mt-5 text-2xl font-bold text-slate-950">Bayar otomatis</h2>
                                <p class="mt-3 text-sm leading-7 text-slate-500">Gunakan Midtrans untuk pembayaran instan
                                    lewat
                                    QRIS, virtual account, e-wallet, dan metode digital lain.</p>
                                <button id="btn-bayar-midtrans" type="button" class="btn-primary mt-8 w-full">Bayar
                                    Sekarang via Midtrans</button>
                            </div>
                        @else
                            <div class="surface-card">
                                <div class="feature-icon">M</div>
                                <h2 class="mt-5 text-2xl font-bold text-slate-950">Pembayaran otomatis belum aktif</h2>
                                <p class="mt-3 text-sm leading-7 text-slate-500">Midtrans belum tersedia untuk transaksi
                                    ini, jadi pembayaran tetap bisa dilanjutkan lewat transfer manual.</p>
                            </div>
                        @endif

                        <div class="surface-card">
                            <div class="feature-icon">T</div>
                            <h2 class="mt-5 text-2xl font-bold text-slate-950">Transfer manual</h2>
                            <p class="mt-3 text-sm leading-7 text-slate-500">Transfer ke rekening yang tersedia, lalu unggah
                                bukti pembayaran agar admin bisa melakukan konfirmasi.</p>

                            <div
                                class="mt-6 rounded-[24px] border border-slate-100 bg-slate-50/80 p-4 text-sm leading-7 text-slate-600">
                                BCA 123456789 a.n. Putra Jaya Motor
                            </div>

                            <form action="{{ route('transaksi.uploadStruk', $transaksi->id) }}" method="POST"
                                enctype="multipart/form-data" class="form-shell mt-6">
                                @csrf
                                <div class="form-field">
                                    <label class="field-label" for="bukti_struk">Upload bukti transfer</label>
                                    <input id="bukti_struk" type="file" name="bukti_struk" accept="image/*" required
                                        class="form-input file:mr-4 file:rounded-xl file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-bold file:text-white">
                                </div>
                                <button type="submit" class="btn-accent w-full">Kirim Bukti Transfer</button>
                            </form>
                        </div>
                    @elseif ($transaksi->status_pembayaran === 'menunggu_konfirmasi')
                        <div class="surface-card">
                            <div class="feature-icon">T</div>
                            <h2 class="mt-5 text-2xl font-bold text-slate-950">Menunggu Konfirmasi Admin</h2>
                            <p class="mt-3 text-sm leading-7 text-slate-500">Bukti transfer sudah diterima. Admin akan
                                memverifikasi pembayaran Anda sebelum nota dinyatakan lunas.</p>
                            <a href="{{ route('transaksi.cetak', $transaksi->id) }}"
                                class="btn-secondary mt-8 inline-flex w-full justify-center">Lihat Nota Sementara</a>
                        </div>
                    @else
                        <div class="surface-card">
                            <div class="feature-icon">OK</div>
                            <h2 class="mt-5 text-2xl font-bold text-slate-950">Pembayaran Sudah Lunas</h2>
                            <p class="mt-3 text-sm leading-7 text-slate-500">Transaksi ini sudah selesai. Anda bisa langsung
                                membuka nota digital kapan saja.</p>
                            <a href="{{ route('transaksi.cetak', $transaksi->id) }}"
                                class="btn-primary mt-8 inline-flex w-full justify-center">Lihat Nota</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    @if ($midtransEnabled && $transaksi->status_pembayaran === 'belum_bayar')
        <script>
            // Fungsi untuk memuat script eksternal HANYA ketika dibutuhkan (Lazy Load)
            function loadExternalScript(url, clientKey = null) {
                return new Promise((resolve, reject) => {
                    if (document.querySelector(`script[src="${url}"]`)) {
                        return resolve(); // Script sudah ada, langsung lanjut
                    }
                    const script = document.createElement('script');
                    script.src = url;
                    if (clientKey) script.setAttribute('data-client-key', clientKey);
                    script.onload = resolve;
                    script.onerror = reject;
                    document.body.appendChild(script);
                });
            }

            document.addEventListener("DOMContentLoaded", function() {
                const btnBayar = document.getElementById('btn-bayar-midtrans');

                if (btnBayar) {
                    btnBayar.addEventListener('click', async function() {
                        const originalText = btnBayar.innerHTML;
                        btnBayar.innerHTML = 'Menyiapkan Pembayaran...';
                        btnBayar.disabled = true;

                        try {
                            // 1. Muat library SweetAlert dan Midtrans di latar belakang
                            await loadExternalScript("https://cdn.jsdelivr.net/npm/sweetalert2@11");
                            await loadExternalScript("{{ $midtransConfig['snap_url'] }}",
                                "{{ $midtransConfig['client_key'] }}");

                            btnBayar.innerHTML = 'Memproses Token...';

                            // 2. Lakukan pengambilan token
                            const response = await fetch(
                                "{{ route('transaksi.midtransToken', $transaksi->id) }}", {
                                    method: 'GET',
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                });

                            const data = await response.json();

                            if (response.ok && data.snap_token) {
                                window.snap.pay(data.snap_token, {
                                    onSuccess: function(result) {
                                        Swal.fire('Berhasil!', 'Pembayaran berhasil.',
                                            'success').then(() => window.location.reload());
                                    },
                                    onPending: function(result) {
                                        Swal.fire('Menunggu',
                                                'Menunggu konfirmasi pembayaran Anda.', 'info')
                                            .then(() => window.location.reload());
                                    },
                                    onError: function(result) {
                                        Swal.fire('Gagal', 'Pembayaran gagal!', 'error');
                                    },
                                    onClose: function() {
                                        console.log("Popup ditutup");
                                    }
                                });
                            } else {
                                Swal.fire('Error', 'Gagal mendapatkan token: ' + (data.message ||
                                    'Error tidak diketahui'), 'error');
                            }
                        } catch (error) {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire('Error',
                                    'Terjadi kesalahan koneksi ke server. Silakan coba lagi.', 'error');
                            } else {
                                alert('Terjadi kesalahan koneksi ke server. Silakan coba lagi.');
                            }
                        } finally {
                            btnBayar.innerHTML = originalText;
                            btnBayar.disabled = false;
                        }
                    });
                }
            });
        </script>

        {{-- <script>
            // Fungsi untuk memuat script eksternal HANYA ketika dibutuhkan (Lazy Load)
            function loadExternalScript(url, clientKey = null) {
                return new Promise((resolve, reject) => {
                    if (document.querySelector(`script[src="${url}"]`)) {
                        return resolve(); // Script sudah ada, langsung lanjut
                    }
                    const script = document.createElement('script');
                    script.src = url;
                    if (clientKey) script.setAttribute('data-client-key', clientKey);
                    script.onload = resolve;
                    script.onerror = reject;
                    document.body.appendChild(script);
                });
            }

            document.addEventListener("DOMContentLoaded", function() {
                const btnBayar = document.getElementById('btn-bayar-midtrans');

                if (btnBayar) {
                    btnBayar.addEventListener('click', async function() {
                        const originalText = btnBayar.innerHTML;
                        btnBayar.innerHTML = 'Menyiapkan Pembayaran...';
                        btnBayar.disabled = true;

                        try {
                            // 1. Muat library SweetAlert di latar belakang
                            await loadExternalScript("https://cdn.jsdelivr.net/npm/sweetalert2@11");

                            btnBayar.innerHTML = 'Memproses Link Pembayaran...';

                            // 2. Lakukan pengambilan token
                            const response = await fetch(
                                "{{ route('transaksi.midtransToken', $transaksi->id) }}", {
                                    method: 'GET',
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                });

                            const data = await response.json();

                            if (response.ok && data.redirect_url) {
                                // Redirect langsung ke halaman Midtrans
                                window.location.href = data.redirect_url;
                            } else {
                                Swal.fire('Error', 'Gagal mendapatkan link pembayaran: ' + (data.message ||
                                    'Error tidak diketahui'), 'error');
                            }
                        } catch (error) {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire('Error',
                                    'Terjadi kesalahan koneksi ke server. Silakan coba lagi.', 'error');
                            } else {
                                alert('Terjadi kesalahan koneksi ke server. Silakan coba lagi.');
                            }
                        } finally {
                            btnBayar.innerHTML = originalText;
                            btnBayar.disabled = false;
                        }
                    });
                }
            });
        </script> --}}
    @endif
@endsection
