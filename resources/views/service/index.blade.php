<x-layout>
    <div class="page-shell">
        <div class="page-header">
            <div class="page-header-split">
                <p class="page-kicker">Layanan</p>
                <h1 class="page-title">Data jasa servis</h1>
                <p class="page-description">Atur daftar layanan bengkel dengan harga yang seragam dan mudah dibaca.</p>
            </div>

            <div class="page-actions">
                <a href="{{ route('service.create') }}" class="btn-primary">Tambah Jasa Servis</a>
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
                            <th>Nama Servis</th>
                            <th>Harga</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($services as $index => $s)
                            <tr>
                                <td>{{ ($services->firstItem() ?? 1) + $index }}</td>
                                <td class="font-semibold text-slate-900">{{ $s->nama_service }}</td>
                                <td>Rp {{ number_format($s->harga, 0, ',', '.') }}</td>
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('service.edit', $s->id) }}" class="btn-warning !px-4 !py-2">Edit</a>
                                        <form action="{{ route('service.destroy', $s->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger !px-4 !py-2">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state my-4">
                                        <div class="empty-state-icon">JS</div>
                                        <h3 class="text-xl font-bold text-slate-950">Belum ada jasa servis</h3>
                                        <p class="max-w-lg text-sm leading-6 text-slate-500">Tambahkan layanan servis untuk mempermudah proses transaksi dan pencatatan biaya.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="surface-card-tight">
            {{ $services->links() }}
        </div>
    </div>
</x-layout>
