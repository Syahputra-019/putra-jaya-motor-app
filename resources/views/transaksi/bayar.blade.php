<x-layout>
    <div class="page-shell-md">
        <div class="page-header">
            <div class="page-header-split">
                <p class="page-kicker">Payment Gateway</p>
                <h1 class="page-title">Pilih metode pembayaran</h1>
                <p class="page-description">Selesaikan pembayaran untuk transaksi <span class="font-bold text-[color:var(--brand-navy-800)]">{{ $transaksi->kode_transaksi }}</span> dengan tampilan yang lebih fokus dan konsisten.</p>
            </div>
        </div>

        <div class="surface-card">
            <div class="grid gap-4 rounded-[28px] border border-slate-100 bg-slate-50/80 p-5 md:grid-cols-3">
                <div>
                    <div class="page-kicker">Total Tagihan</div>
                    <div class="mt-2 text-3xl font-bold text-slate-950">Rp {{ number_format($transaksi->total_biaya, 0, ',', '.') }}</div>
                </div>
                <div>
                    <div class="page-kicker">Pelanggan</div>
                    <div class="mt-2 text-xl font-bold text-slate-950">{{ $transaksi->pelanggan->nama_pelanggan ?? 'Umum' }}</div>
                </div>
                <div>
                    <div class="page-kicker">Status</div>
                    <div class="mt-2">
                        <span class="badge {{ $transaksi->status_pembayaran === 'lunas' ? 'badge-success' : 'badge-warning' }}">
                            {{ str_replace('_', ' ', $transaksi->status_pembayaran) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="surface-card">
                <div class="feature-icon">M</div>
                <h2 class="mt-5 text-2xl font-bold text-slate-950">Bayar otomatis</h2>
                <p class="mt-3 text-sm leading-7 text-slate-500">Gunakan Midtrans untuk pembayaran instan lewat QRIS, virtual account, e-wallet, dan metode digital lain.</p>
                <button id="pay-button" class="btn-primary mt-8 w-full">Bayar Sekarang via Midtrans</button>
            </div>

            <div class="surface-card">
                <div class="feature-icon">T</div>
                <h2 class="mt-5 text-2xl font-bold text-slate-950">Transfer manual</h2>
                <p class="mt-3 text-sm leading-7 text-slate-500">Transfer ke rekening yang tersedia, lalu unggah bukti pembayaran agar admin bisa melakukan konfirmasi.</p>

                <div class="mt-6 rounded-[24px] border border-slate-100 bg-slate-50/80 p-4 text-sm leading-7 text-slate-600">
                    BCA 123456789 a.n. Putra Jaya Motor
                </div>

                <form action="{{ route('transaksi.uploadStruk', $transaksi->id) }}" method="POST" enctype="multipart/form-data" class="form-shell mt-6">
                    @csrf
                    <div class="form-field">
                        <label class="field-label" for="bukti_struk">Upload bukti transfer</label>
                        <input id="bukti_struk" type="file" name="bukti_struk" accept="image/*" required class="form-input file:mr-4 file:rounded-xl file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-bold file:text-white">
                    </div>
                    <button type="submit" class="btn-accent w-full">Kirim Bukti Transfer</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        document.getElementById('pay-button').onclick = function() {
            snap.pay('{{ $transaksi->snap_token }}', {
                onSuccess: function() {
                    alert('Pembayaran berhasil.');
                    window.location.href = "{{ route('transaksi.cetak', $transaksi->id) }}";
                },
                onPending: function() {
                    alert('Menunggu pembayaran.');
                },
                onError: function() {
                    alert('Pembayaran gagal.');
                },
                onClose: function() {
                    alert('Pop-up pembayaran ditutup sebelum transaksi selesai.');
                }
            });
        };
    </script>
</x-layout>
