<x-layout>
    <div class="page-shell">
        <div class="page-header">
            <div class="page-header-split">
                <p class="page-kicker">Queue Management</p>
                <h1 class="page-title">Antrean booking</h1>
                <p class="page-description">Pantau semua antrean servis dengan tampilan status yang lebih jelas dan tombol aksi yang konsisten.</p>
            </div>

            <div class="page-actions">
                <a href="{{ route('booking.create') }}" class="btn-primary">Tambah Booking</a>
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
                            <th>No</th>
                            <th>Pelanggan</th>
                            <th>Kendaraan</th>
                            <th>Jadwal</th>
                            <th>Mekanik</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $index => $b)
                            <tr>
                                <td>{{ ($bookings->firstItem() ?? 1) + $index }}</td>
                                <td class="font-semibold text-slate-900">{{ $b->pelanggan->nama_pelanggan ?? ($b->pelanggan->nama ?? 'Tanpa Nama') }}</td>
                                <td>
                                    <div class="font-semibold text-slate-900">{{ $b->plat_nomor }}</div>
                                    <div class="text-xs text-slate-500">{{ $b->tipe_motor }}</div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($b->jadwal_booking)->format('d M Y, H:i') }}</td>
                                <td>{{ $b->mekanik->nama_mekanik ?? ($b->mekanik->nama ?? 'Belum Ditentukan') }}</td>
                                <td>
                                    @if ($b->status === 'menunggu')
                                        <span class="badge badge-warning">Menunggu</span>
                                    @elseif($b->status === 'diproses')
                                        <span class="badge badge-info">Diproses</span>
                                    @elseif($b->status === 'selesai')
                                        <span class="badge badge-success">Selesai</span>
                                    @else
                                        <span class="badge badge-danger">Dibatalkan</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('booking.edit', $b->id) }}" class="btn-warning !px-4 !py-2">Edit</a>
                                        @if ($b->status === 'selesai')
                                            <a href="{{ route('transaksi.create', ['booking_id' => $b->id]) }}" class="btn-accent !px-4 !py-2">Ke Kasir</a>
                                        @endif
                                        <form action="{{ route('booking.destroy', $b->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus antrean ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger !px-4 !py-2">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state my-4">
                                        <div class="empty-state-icon">BQ</div>
                                        <h3 class="text-xl font-bold text-slate-950">Belum ada booking</h3>
                                        <p class="max-w-lg text-sm leading-6 text-slate-500">Booking servis yang masuk akan muncul di sini untuk diproses oleh admin atau mekanik.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="surface-card-tight">
            {{ $bookings->links() }}
        </div>
    </div>
</x-layout>
