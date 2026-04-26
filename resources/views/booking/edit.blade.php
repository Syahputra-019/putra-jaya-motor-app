<x-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Edit Antrean Booking</h2>

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

            <form action="{{ route('booking.update', $booking->id) }}" method="POST">
                @csrf
                @method('PUT') <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Pelanggan <span class="text-red-500">*</span></label>
                    <select name="pelanggan_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @foreach($pelanggans as $p)
                            <option value="{{ $p->id }}" {{ $booking->pelanggan_id == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_pelanggan ?? $p->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Plat Nomor <span class="text-red-500">*</span></label>
                        <input type="text" name="plat_nomor" value="{{ $booking->plat_nomor }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Tipe Motor <span class="text-red-500">*</span></label>
                        <input type="text" name="tipe_motor" value="{{ $booking->tipe_motor }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Jadwal Booking <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="jadwal_booking" value="{{ \Carbon\Carbon::parse($booking->jadwal_booking)->format('Y-m-d\TH:i') }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Keluhan Kerusakan <span class="text-red-500">*</span></label>
                    <textarea name="keluhan" rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ $booking->keluhan }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Mekanik Pengerja</label>
                        <select name="mekanik_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Belum Ditentukan --</option>
                            @foreach($mekaniks as $m)
                                <option value="{{ $m->id }}" {{ $booking->mekanik_id == $m->id ? 'selected' : '' }}>
                                    {{ $m->nama_mekanik ?? $m->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Status Antrean <span class="text-red-500">*</span></label>
                        <select name="status" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="menunggu" {{ $booking->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="diproses" {{ $booking->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ $booking->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ $booking->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <a href="{{ route('booking.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                        Batal
                    </a>
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                        Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>