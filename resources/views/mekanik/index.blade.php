<x-layout>
    <div class="page-shell">
        <div class="page-header">
            <div class="page-header-split">
                <p class="page-kicker">Workshop Team</p>
                <h1 class="page-title">Data mekanik</h1>
                <p class="page-description">Daftar mekanik kini tampil lebih bersih dengan ritme visual yang sama seperti modul pelanggan, sparepart, dan servis.</p>
            </div>

            <div class="page-actions">
                <a href="{{ route('mekanik.create') }}" class="btn-primary">Tambah Mekanik</a>
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
                            <th>Nama Mekanik</th>
                            <th>No. Telepon</th>
                            <th>Spesialisasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mekaniks as $index => $mekanik)
                            <tr>
                                <td>{{ ($mekaniks->firstItem() ?? 1) + $index }}</td>
                                <td class="font-semibold text-slate-900">{{ $mekanik->nama_mekanik }}</td>
                                <td>{{ $mekanik->no_telp }}</td>
                                <td><span class="badge badge-neutral">{{ $mekanik->spesialisasi }}</span></td>
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('mekanik.edit', $mekanik->id) }}" class="btn-warning !px-4 !py-2">Edit</a>
                                        <form action="{{ route('mekanik.destroy', $mekanik->id) }}" method="POST" class="form-hapus">
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
                                        <div class="empty-state-icon">MK</div>
                                        <h3 class="text-xl font-bold text-slate-950">Belum ada mekanik</h3>
                                        <p class="max-w-lg text-sm leading-6 text-slate-500">Tambahkan data mekanik untuk mempermudah pembagian pekerjaan dan penugasan servis.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="surface-card-tight">
            {{ $mekaniks->links() }}
        </div>
    </div>

    <script>
        document.querySelectorAll('.btn-hapus').forEach((button) => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');

                Swal.fire({
                    title: 'Hapus mekanik ini?',
                    text: 'Data mekanik tidak bisa dikembalikan.',
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
