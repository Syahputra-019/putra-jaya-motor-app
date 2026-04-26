<x-layout>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Kasir & Riwayat Transaksi</h2>
            <p class="text-sm text-gray-500">Kelola data servis dan pembayaran pelanggan.</p>
        </div>
        <a href="{{ route('transaksi.create') }}"
            class="rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white shadow-md transition hover:bg-blue-700">
            + Transaksi Baru
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-400 bg-green-100 p-4 text-green-700">
            <strong class="font-bold">Berhasil!</strong> {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Kode Transaksi</th>
                        <th class="px-4 py-3 font-semibold">Tanggal</th>
                        <th class="px-4 py-3 font-semibold">Pelanggan</th>
                        <th class="px-4 py-3 font-semibold">Mekanik</th>
                        <th class="px-4 py-3 font-semibold">Total Biaya</th>
                        <th class="px-4 py-3 text-center font-semibold">Status</th>
                        <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($transaksis as $transaksi)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-blue-600">{{ $transaksi->kode_transaksi }}</td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d M Y') }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $transaksi->pelanggan->nama_pelanggan }}
                            </td>
                            <td class="px-4 py-3">{{ $transaksi->mekanik->nama_mekanik }}</td>
                            <td class="px-4 py-3 font-semibold">Rp
                                {{ number_format($transaksi->total_biaya, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">
                                    {{ $transaksi->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">

                                @if ($transaksi->status_pembayaran == 'belum_bayar')
                                    <a href="{{ route('transaksi.bayar', $transaksi->id) }}"
                                        class="mr-2 inline-block rounded bg-green-500 px-3 py-1 text-xs font-bold text-white transition hover:bg-green-600">
                                        💳 Bayar
                                    </a>
                                @elseif ($transaksi->status_pembayaran == 'menunggu_konfirmasi')
                                    <a href="{{ asset('struk_transfer/' . $transaksi->bukti_struk) }}" target="_blank"
                                        class="mr-2 inline-block rounded bg-gray-500 px-2 py-1 text-xs font-bold text-white hover:bg-gray-600">
                                        Lihat Struk
                                    </a>
                                    <form action="{{ route('transaksi.konfirmasi', $transaksi->id) }}" method="POST"
                                        style="display: inline-block;" class="mr-2">
                                        @csrf
                                        <button type="submit"
                                            class="rounded bg-blue-500 px-2 py-1 text-xs font-bold text-white hover:bg-blue-600"
                                            onclick="return confirm('Yakin struknya valid dan mau di-LUNAS-kan?')">
                                            ✅ ACC Lunas
                                        </button>
                                    </form>
                                @elseif ($transaksi->status_pembayaran == 'lunas')
                                    <a href="{{ route('transaksi.cetak', $transaksi->id) }}" target="_blank"
                                        class="mr-2 inline-block rounded bg-indigo-500 px-3 py-1 text-xs font-bold text-white transition hover:bg-indigo-600">
                                        🖨️ Cetak
                                    </a>
                                @endif

                                <form action="{{ route('transaksi.destroy', $transaksi->id) }}" method="POST"
                                    class="form-hapus inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="btn-hapus rounded bg-red-500 px-3 py-1 text-xs font-bold text-white transition hover:bg-red-600">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">Belum ada riwayat transaksi
                                servis.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-gray-200 p-4">
            {{ $transaksis->links() }}
        </div>
    </div>

    <script>
        document.querySelectorAll('.btn-hapus').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                Swal.fire({
                    title: 'Hapus Transaksi?',
                    text: "Data akan dihapus permanen, tapi stok sparepart TIDAK akan kembali otomatis.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                })
            });
        });
    </script>
</x-layout>
