<x-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">Daftar Antrean Booking</h2>
            <a href="{{ route('booking.create') }}"
                class="rounded-lg bg-blue-600 px-4 py-2 font-bold text-white shadow-md transition duration-300 hover:bg-blue-700">
                + Tambah Booking
            </a>
        </div>

        @if (session('success'))
            <div class="relative mb-4 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="overflow-hidden rounded-lg bg-white shadow-md">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-100 text-left text-sm uppercase leading-normal text-gray-600">
                        <th class="px-6 py-3 text-left">No</th>
                        <th class="px-6 py-3 text-left">Pelanggan</th>
                        <th class="px-6 py-3 text-left">Kendaraan</th>
                        <th class="px-6 py-3 text-left">Jadwal</th>
                        <th class="px-6 py-3 text-left">Mekanik</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-light text-gray-600">
                    @forelse ($bookings as $index => $b)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="whitespace-nowrap px-6 py-3 text-left">{{ $index + 1 }}</td>
                            <td class="px-6 py-3 text-left font-semibold">
                                {{ $b->pelanggan->nama_pelanggan ?? ($b->pelanggan->nama ?? 'Tanpa Nama') }}
                            </td>
                            <td class="px-6 py-3 text-left">
                                {{ $b->plat_nomor }} <br>
                                <span class="text-xs text-gray-400">{{ $b->tipe_motor }}</span>
                            </td>
                            <td class="px-6 py-3 text-left">
                                {{ \Carbon\Carbon::parse($b->jadwal_booking)->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-3 text-left">
                                {{ $b->mekanik->nama_mekanik ?? ($b->mekanik->nama ?? 'Belum Ditentukan') }}
                            </td>
                            <td class="px-6 py-3 text-center">
                                @if ($b->status == 'menunggu')
                                    <span
                                        class="rounded-full bg-yellow-200 px-3 py-1 text-xs font-bold text-yellow-700">Menunggu</span>
                                @elseif($b->status == 'diproses')
                                    <span
                                        class="rounded-full bg-blue-200 px-3 py-1 text-xs font-bold text-blue-700">Diproses</span>
                                @elseif($b->status == 'selesai')
                                    <span
                                        class="rounded-full bg-green-200 px-3 py-1 text-xs font-bold text-green-700">Selesai</span>
                                @else
                                    <span
                                        class="rounded-full bg-red-200 px-3 py-1 text-xs font-bold text-red-700">Dibatalkan</span>
                                @endif
                            </td>
                            <td class="flex justify-center space-x-2 px-6 py-3 text-center">
                                <a href="{{ route('booking.edit', $b->id) }}"
                                    class="rounded bg-yellow-500 px-3 py-1 text-xs text-white shadow hover:bg-yellow-600">Edit</a>
                                @if ($b->status == 'selesai')
                                    <a href="{{ route('transaksi.create', ['booking_id' => $b->id]) }}"
                                        class="rounded bg-yellow-500 px-3 py-1 text-xs font-bold text-white hover:bg-yellow-600">
                                        <i class="fas fa-cash-register mr-1"></i> Bawa ke Kasir
                                    </a>
                                @endif
                                
                                <form action="{{ route('booking.destroy', $b->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus data antrean ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="rounded bg-red-500 px-3 py-1 text-xs text-white shadow hover:bg-red-600">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-6 text-center text-gray-500">Belum ada antrean booking
                                saat ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $bookings->links() }}
        </div>
    </div>
</x-layout>
