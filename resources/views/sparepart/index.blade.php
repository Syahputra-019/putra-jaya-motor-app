<x-layout>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Data Master Sparepart</h1>
            <p class="text-sm text-slate-500">Kelola stok dan harga suku cadang bengkel.</p>
        </div>
        <a href="{{ route('sparepart.create') }}"
            class="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 font-medium text-white shadow-sm transition hover:bg-blue-700">
            <span>+</span> Tambah Sparepart
        </a>
    </div>

    @if (session('success'))
        <div
            class="mb-6 flex items-center justify-between rounded-r-lg border-l-4 border-emerald-500 bg-emerald-100 p-4 font-medium text-emerald-700 shadow-sm">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-slate-100 bg-white shadow-sm">
        <table class="w-full border-collapse text-left">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50 text-xs uppercase tracking-wider text-slate-600">
                    <th class="p-4 font-semibold">No</th>
                    <th class="p-4 font-semibold">Nama Sparepart</th>
                    <th class="p-4 font-semibold">Harga</th>
                    <th class="p-4 font-semibold">Stok</th>
                    <th class="p-4 text-center font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">

                @forelse ($spareparts as $index => $item)
                    <tr class="transition hover:bg-slate-50">
                        <td class="p-4 text-slate-700">{{ $index + 1 }}</td>
                        <td class="p-4 font-medium text-slate-800">{{ $item->name }}</td>
                        <td class="p-4 text-slate-700">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="p-4 text-slate-700">
                            <span
                                class="{{ $item->stock > 5 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }} rounded-md px-2 py-1 text-xs font-bold">
                                {{ $item->stock }}
                            </span>
                        </td>
                        <td class="flex justify-center gap-2 p-4">
                            <a href="{{ route('sparepart.edit', $item->id) }}"
                                class="rounded-md bg-amber-100 px-3 py-1 text-sm font-medium text-amber-700 transition hover:bg-amber-200">Edit</a>

                            <form action="{{ route('sparepart.destroy', $item->id) }}" method="POST"
                                class="form-hapus inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="button" data-name="{{ $item->name }}"
                                    class="btn-hapus rounded-md bg-red-100 px-3 py-1 text-sm font-medium text-red-700 transition hover:bg-red-200">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-slate-500">
                            Belum ada data sparepart. Silakan klik tombol "Tambah Sparepart" di atas.
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $spareparts->links() }}
    </div>

    <script>
        // Cari semua tombol dengan class 'btn-hapus'
        const tombolHapus = document.querySelectorAll('.btn-hapus');

        tombolHapus.forEach(tombol => {
            tombol.addEventListener('click', function() {
                // Ambil nama barang dari atribut data-name
                const namaBarang = this.getAttribute('data-name');
                // Ambil form terdekat dari tombol yang diklik
                const form = this.closest('.form-hapus');

                // Tampilkan SweetAlert di tengah halaman!
                Swal.fire({
                    title: 'Yakin mau hapus?',
                    text: "Data " + namaBarang + " akan hilang permanen lho!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444', // Warna merah Tailwind
                    cancelButtonColor: '#94a3b8', // Warna abu-abu Tailwind
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true // Tukar posisi tombol
                }).then((result) => {
                    // Kalau user klik "Ya, Hapus!"
                    if (result.isConfirmed) {
                        form.submit(); // Baru form-nya dikirim ke controller
                    }
                });
            });
        });
    </script>
</x-layout>
