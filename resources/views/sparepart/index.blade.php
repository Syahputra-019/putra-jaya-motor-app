<x-layout>
    <div class="page-shell">
        <div class="page-header">
            <div class="page-header-split">
                <p class="page-kicker">Inventori</p>
                <h1 class="page-title">Data sparepart</h1>
                <p class="page-description">Kelola stok dan harga suku cadang dengan tampilan tabel yang lebih rapi dan
                    konsisten.</p>
            </div>

            <div class="page-actions">
                <a href="{{ route('sparepart.create') }}" class="btn-primary">Tambah Sparepart</a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <div class="font-black">OK</div>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        <!-- START: Fitur Search Kelas Enterprise -->
        <div class="mb-6 flex items-center justify-between">
            <form action="{{ route('sparepart.index') }}" method="GET" id="searchForm"
                class="relative w-full max-w-md">
                <!-- Input Search -->
                <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                    placeholder="Cari nama sparepart ..." autocomplete="off"
                    class="w-full rounded-xl border border-slate-300 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-700 shadow-sm transition-all focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">

                <!-- Icon Kaca Pembesar (SVG) -->
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
            </form>
        </div>
        <!-- END: Fitur Search -->

        <div class="table-card">
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Sparepart</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($spareparts as $index => $item)
                            <tr>
                                <td>{{ ($spareparts->firstItem() ?? 1) + $index }}</td>
                                <td class="font-semibold text-slate-900">{{ $item->nama_sparepart }}</td>
                                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $item->stok > 5 ? 'badge-success' : 'badge-danger' }}">
                                        {{ $item->stok }}
                                    </span>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('sparepart.edit', $item->id) }}"
                                            class="btn-warning !px-4 !py-2">Edit</a>
                                        <form action="{{ route('sparepart.destroy', $item->id) }}" method="POST"
                                            class="form-hapus inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" data-name="{{ $item->nama_sparepart }}"
                                                class="btn-hapus btn-danger !px-4 !py-2">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state my-4">
                                        <div class="empty-state-icon">SP</div>
                                        <h3 class="text-xl font-bold text-slate-950">Belum ada sparepart</h3>
                                        <p class="max-w-lg text-sm leading-6 text-slate-500">Tambahkan sparepart baru
                                            untuk mulai membangun inventori bengkel.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="surface-card-tight">
            {{ $spareparts->links() }}
        </div>
    </div>

    <script>
        document.querySelectorAll('.btn-hapus').forEach((button) => {
            button.addEventListener('click', function() {
                const form = this.closest('.form-hapus');
                const namaBarang = this.getAttribute('data-name');

                Swal.fire({
                    title: 'Hapus sparepart ini?',
                    text: 'Data ' + namaBarang + ' akan dihapus permanen.',
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

    <script>
        // Script Debounce untuk Search
        let typingTimer;
        const doneTypingInterval = 500; // Waktu tunggu 0.5 detik
        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');

        if (searchInput) {
            // Saat user mulai mengetik, hapus timer sebelumnya
            searchInput.addEventListener('keyup', function() {
                clearTimeout(typingTimer);
                // Buat timer baru, kalau user berhenti ngetik selama 0.5 detik, otomatis submit
                typingTimer = setTimeout(() => {
                    searchForm.submit();
                }, doneTypingInterval);
            });

            // Hapus timer kalau tombol masih ditekan (belum selesai ngetik)
            searchInput.addEventListener('keydown', function() {
                clearTimeout(typingTimer);
            });

            // Trik tambahan: Pindahkan kursor ke akhir teks saat halaman reload
            const len = searchInput.value.length;
            if (len > 0) {
                searchInput.focus();
                searchInput.setSelectionRange(len, len);
            }
        }
    </script>
</x-layout>
