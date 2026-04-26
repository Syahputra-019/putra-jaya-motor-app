<x-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Master Data Mekanik') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800">Daftar Mekanik</h3>
                        <a href="{{ route('mekanik.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition duration-150">
                            + Tambah Mekanik
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-700">No</th>
                                    <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-700">Nama Mekanik</th>
                                    <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-700">No Telp</th>
                                    <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-700">Spesialisasi</th>
                                    <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($mekaniks as $index => $mekanik)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="py-3 px-4 border-b text-sm text-gray-700">
                                            {{ $mekaniks->firstItem() + $index }}
                                        </td>
                                        <td class="py-3 px-4 border-b text-sm text-gray-700 font-medium">{{ $mekanik->nama_mekanik }}</td>
                                        <td class="py-3 px-4 border-b text-sm text-gray-700">{{ $mekanik->no_telp }}</td>
                                        <td class="py-3 px-4 border-b text-sm text-gray-700">
                                            <span class="bg-gray-200 text-gray-700 py-1 px-3 rounded-full text-xs font-semibold">
                                                {{ $mekanik->spesialisasi }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 border-b text-center space-x-2">
                                            <a href="{{ route('mekanik.edit', $mekanik->id) }}" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-1 px-3 rounded text-sm transition duration-150">
                                                Edit
                                            </a>
                                            <form action="{{ route('mekanik.destroy', $mekanik->id) }}" method="POST" class="inline-block form-hapus">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-1 px-3 rounded text-sm transition duration-150 btn-hapus">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-6 px-4 text-center text-gray-500 font-medium">Belum ada data mekanik yang terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $mekaniks->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.btn-hapus').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Yakin mau hapus?',
                    text: "Data mekanik ini nggak bisa dikembalikan lagi!",
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