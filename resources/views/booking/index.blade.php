<x-layout>
    <div class="page-shell">
        <div class="page-header">
            <div class="page-header-split">
                <h1 class="page-title">Antrean booking</h1>
                <p class="page-description">
                    Pantau jadwal booking, penugasan mekanik, dan status servis kendaraan pelanggan secara real-time.
                </p>
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

        <form action="{{ route('booking.index') }}" method="GET" class="mb-6 flex flex-wrap items-center gap-2">
            <select name="status" class="form-input !w-auto !py-2">
                <option value="">Semua Antrean Aktif</option>
                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-input !w-auto !py-2">
            <button type="submit" class="btn-primary !py-2">Filter Data</button>
            @if (request('status') || request('tanggal'))
                <a href="{{ route('booking.index') }}" class="btn-secondary !py-2">Reset Filter</a>
            @endif
        </form>

        <div class="table-card">
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Antrean</th>
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
                            @php
                                $nomorAntrean =
                                    $b->kode_antrean ??
                                    ($b->nomor_antrean
                                        ? '#' . str_pad((string) $b->nomor_antrean, 3, '0', STR_PAD_LEFT)
                                        : '-');
                            @endphp
                            <tr>
                                <td>{{ ($bookings->firstItem() ?? 1) + $index }}</td>
                                <td>
                                    <div class="font-bold text-slate-900">{{ $nomorAntrean }}</div>
                                    <div class="text-xs text-slate-500">
                                        {{ optional($b->tanggal_antrean)->format('d M Y') ?? \Carbon\Carbon::parse($b->jadwal_booking)->format('d M Y') }}
                                    </div>
                                </td>
                                <td class="font-semibold text-slate-900">
                                    {{ $b->pelanggan->nama_pelanggan ?? ($b->pelanggan->nama ?? 'Tanpa Nama') }}</td>
                                <td>
                                    <div class="font-semibold text-slate-900">{{ $b->plat_nomor }}</div>
                                    <div class="text-xs text-slate-500">{{ $b->tipe_motor }}</div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($b->jadwal_booking)->format('d M Y') }}</td>
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
                                        <a href="{{ route('booking.edit', $b->id) }}"
                                            class="btn-warning !px-4 !py-2">Edit</a>
                                        @if ($b->status === 'selesai')
                                            <a href="{{ route('transaksi.create', ['booking_id' => $b->id]) }}"
                                                class="btn-accent !px-4 !py-2">Proses Bayar</a>
                                        @endif
                                        <form action="{{ route('booking.destroy', $b->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus antrean ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger !px-4 !py-2">Hapus</button>
                                        </form>
                                        <a href="{{ route('booking.show', $b->id) }}"
                                            class="btn-primary !bg-blue-600 !px-4 !py-2 text-white hover:!bg-blue-700">Detail</a>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state my-4">
                                        <div class="empty-state-icon">BQ</div>
                                        <h3 class="text-xl font-bold text-slate-950">Belum ada booking</h3>
                                        <p class="max-w-lg text-sm leading-6 text-slate-500">Booking servis yang masuk
                                            akan muncul di sini untuk diproses oleh admin atau mekanik.</p>
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
