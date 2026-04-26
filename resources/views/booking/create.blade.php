<x-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Tambah Antrean Booking</h2>

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

            <form action="{{ route('booking.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Pilih Pelanggan <span class="text-red-500">*</span></label>
                    <select name="pelanggan_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach($pelanggans as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_pelanggan ?? $p->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Plat Nomor <span class="text-red-500">*</span></label>
                        <input type="text" name="plat_nomor" placeholder="Contoh: N 1234 AB" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Tipe Motor <span class="text-red-500">*</span></label>
                        <input type="text" name="tipe_motor" placeholder="Contoh: Honda Vario 150" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Jadwal Booking <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="jadwal_booking" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Keluhan Kerusakan <span class="text-red-500">*</span></label>
                    <textarea name="keluhan" rows="3" placeholder="Contoh: Motor brebet saat digas, rem depan blong..." class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Mekanik (Opsional)</label>
                        <select name="mekanik_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Belum Ditentukan --</option>
                            @foreach($mekaniks as $m)
                                <option value="{{ $m->id }}">{{ $m->nama_mekanik ?? $m->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Status Antrean <span class="text-red-500">*</span></label>
                        <select name="status" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="menunggu">Menunggu</option>
                            <option value="diproses">Diproses</option>
                            <option value="selesai">Selesai</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <a href="{{ route('booking.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                        Batal
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                        Simpan Antrean
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>