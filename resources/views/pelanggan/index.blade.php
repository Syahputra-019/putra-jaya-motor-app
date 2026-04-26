<x-layout>
    <div class="page-shell">
        <div class="page-header">
            <div class="page-header-split">
                <p class="page-kicker">Customer Data</p>
                <h1 class="page-title">Data pelanggan</h1>
                <p class="page-description">Semua data pelanggan kini tampil dalam pola tabel yang sama dengan modul lain agar lebih nyaman dikelola.</p>
            </div>

            <div class="page-actions">
                <a href="{{ route('pelanggan.create') }}" class="btn-primary">Tambah Pelanggan</a>
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
                            <th>Nama Pelanggan</th>
                            <th>No. Telepon</th>
                            <th>Alamat</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pelanggans as $index => $pelanggan)
                            <tr>
                                <td>{{ ($pelanggans->firstItem() ?? 1) + $index }}</td>
                                <td class="font-semibold text-slate-900">{{ $pelanggan->nama_pelanggan }}</td>
                                <td>{{ $pelanggan->no_telp }}</td>
                                <td>{{ $pelanggan->alamat ?? '-' }}</td>
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('pelanggan.edit', $pelanggan->id) }}" class="btn-warning !px-4 !py-2">Edit</a>
                                        <form action="{{ route('pelanggan.destroy', $pelanggan->id) }}" method="POST" class="form-hapus">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-hapus btn-danger !px-4 !py-2">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state my-4">
                                        <div class="empty-state-icon">PL</div>
                                        <h3 class="text-xl font-bold text-slate-950">Belum ada pelanggan</h3>
                                        <p class="max-w-lg text-sm leading-6 text-slate-500">Data pelanggan akan muncul di sini setelah ditambahkan oleh admin atau tercipta dari proses booking.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="surface-card-tight">
            {{ $pelanggans->links() }}
        </div>
    </div>

    <script>
        document.querySelectorAll('.btn-hapus').forEach((button) => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');

                Swal.fire({
                    title: 'Hapus pelanggan ini?',
                    text: 'Data pelanggan akan hilang permanen.',
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
