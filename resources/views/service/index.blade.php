<x-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Daftar Jasa Servis</h2>
            <a href="{{ route('service.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                + Tambah Jasa Servis
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-100 text-left text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">No</th>
                        <th class="py-3 px-6 text-left">Nama Servis</th>
                        <th class="py-3 px-6 text-left">Harga / Biaya</th>
                        <th class="py-3 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @forelse ($services as $index => $s)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $index + 1 }}</td>
                            <td class="py-3 px-6 text-left font-semibold">{{ $s->nama_service }}</td>
                            <td class="py-3 px-6 text-left">Rp {{ number_format($s->harga, 0, ',', '.') }}</td>
                            <td class="py-3 px-6 text-center flex justify-center space-x-2">
                                <a href="{{ route('service.edit', $s->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-3 rounded shadow text-xs">Edit</a>
                                <form action="{{ route('service.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded shadow text-xs">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 px-6 text-center text-gray-500">Belum ada data jasa servis.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $services->links() }}
        </div>
    </div>
</x-layout>