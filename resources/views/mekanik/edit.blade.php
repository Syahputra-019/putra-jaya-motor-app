<x-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Mekanik') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('mekanik.update', $mekanik->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="nama_mekanik" class="block text-gray-700 text-sm font-bold mb-2">Nama Mekanik</label>
                            <input type="text" name="nama_mekanik" id="nama_mekanik" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('nama_mekanik', $mekanik->nama_mekanik) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="no_telp" class="block text-gray-700 text-sm font-bold mb-2">No. telp / WhatsApp</label>
                            <input type="text" name="no_telp" id="no_telp" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('no_telp', $mekanik->no_telp) }}" required>
                        </div>

                        <div class="mb-6">
                            <label for="spesialisasi" class="block text-gray-700 text-sm font-bold mb-2">Spesialisasi</label>
                            <select name="spesialisasi" id="spesialisasi" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="Servis Rutin" {{ (old('spesialisasi', $mekanik->spesialisasi) == 'Servis Rutin') ? 'selected' : '' }}>Servis Rutin</option>
                                <option value="Mesin" {{ (old('spesialisasi', $mekanik->spesialisasi) == 'Mesin') ? 'selected' : '' }}>Turun Mesin</option>
                                <option value="Kelistrikan" {{ (old('spesialisasi', $mekanik->spesialisasi) == 'Kelistrikan') ? 'selected' : '' }}>Kelistrikan</option>
                                <option value="Modifikasi" {{ (old('spesialisasi', $mekanik->spesialisasi) == 'Modifikasi') ? 'selected' : '' }}>Modifikasi</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('mekanik.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150">
                                Batal
                            </a>
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150">
                                Update Data
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-layout>