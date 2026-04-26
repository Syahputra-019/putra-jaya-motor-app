<x-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-xl mx-auto bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Tambah Jasa Servis</h2>

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-400 bg-red-100 p-4 text-red-700">
                    <strong class="font-bold">Gagal Menyimpan Data!</strong>
                    <ul class="mt-2 list-inside list-disc text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('service.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Nama Jasa Servis <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_service" placeholder="Contoh: Ganti Oli, Servis Injeksi..." class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Harga / Biaya (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="harga" placeholder="Contoh: 35000" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required min="0">
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('service.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                        Batal
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>