<x-layout>
    <div class="page-shell">
        <div class="page-header">
            <div class="page-header-split">
                <p class="page-kicker">Cashier Desk</p>
                <h1 class="page-title">Kasir dan transaksi</h1>
                <p class="page-description">Kelola transaksi servis, cek status pembayaran, dan cetak nota dari tampilan yang lebih modern dan konsisten.</p>
            </div>

            <div class="page-actions">
                <a href="{{ route('transaksi.create') }}" class="btn-primary">Transaksi Baru</a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <div class="font-black">OK</div>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        <div class="table-card">
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Mekanik</th>
                            <th>Total</th>
                            <th>Status Bayar</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksis as $transaksi)
                            <tr>
                                <td class="font-semibold text-[color:var(--brand-navy-800)]">{{ $transaksi->kode_transaksi }}</td>
                                <td>{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d M Y') }}</td>
                                <td class="font-semibold text-slate-900">{{ $transaksi->pelanggan->nama_pelanggan }}</td>
                                <td>{{ $transaksi->mekanik->nama_mekanik }}</td>
                                <td class="font-semibold text-slate-900">Rp {{ number_format($transaksi->total_biaya, 0, ',', '.') }}</td>
                                <td>
                                    @if ($transaksi->status_pembayaran === 'belum_bayar')
                                        <span class="badge badge-warning">Belum Bayar</span>
                                    @elseif ($transaksi->status_pembayaran === 'menunggu_konfirmasi')
                                        <span class="badge badge-info">Menunggu Konfirmasi</span>
                                    @elseif ($transaksi->status_pembayaran === 'lunas')
                                        <span class="badge badge-success">Lunas</span>
                                    @else
                                        <span class="badge badge-danger">{{ $transaksi->status_pembayaran }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="table-actions">
                                        @if ($transaksi->status_pembayaran === 'belum_bayar')
                                            <a href="{{ route('transaksi.bayar', $transaksi->id) }}" class="btn-primary !px-4 !py-2">Bayar</a>
                                        @elseif ($transaksi->status_pembayaran === 'menunggu_konfirmasi')
                                            <a href="{{ asset('struk_transfer/' . $transaksi->bukti_struk) }}" target="_blank" class="btn-secondary !px-4 !py-2">Lihat Struk</a>
                                            <form action="{{ route('transaksi.konfirmasi', $transaksi->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="btn-accent !px-4 !py-2"
                                                    onclick="return confirm('Yakin struknya valid dan ingin mengubah status jadi lunas?')">
                                                    ACC Lunas
                                                </button>
                                            </form>
                                        @elseif ($transaksi->status_pembayaran === 'lunas')
                                            <a href="{{ route('transaksi.cetak', $transaksi->id) }}" target="_blank" class="btn-secondary !px-4 !py-2">Cetak</a>
                                        @endif

                                        <form action="{{ route('transaksi.destroy', $transaksi->id) }}" method="POST" class="form-hapus inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-hapus btn-danger !px-4 !py-2">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state my-4">
                                        <div class="empty-state-icon">TR</div>
                                        <h3 class="text-xl font-bold text-slate-950">Belum ada transaksi</h3>
                                        <p class="max-w-lg text-sm leading-6 text-slate-500">Transaksi servis yang sudah dibuat akan tampil di sini lengkap dengan status pembayarannya.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="surface-card-tight">
            {{ $transaksis->links() }}
        </div>
    </div>

    <script>
        document.querySelectorAll('.btn-hapus').forEach((button) => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');

                Swal.fire({
                    title: 'Hapus transaksi ini?',
                    text: 'Data akan dihapus permanen dan stok sparepart tidak kembali otomatis.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#0d1f3a',
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</x-layout>
