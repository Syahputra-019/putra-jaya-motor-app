<x-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Transaksi Servis Baru</h2>
        <p class="text-sm text-gray-500">Catat layanan servis dan suku cadang yang digunakan.</p>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="p-6">
            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-400 bg-red-100 p-4 text-red-700">
                    <strong class="font-bold">Gagal Menyimpan Data!</strong>
                    <ul class="mt-2 list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('transaksi.store') }}" method="POST" x-data="{
                barisSparepart: [{ id: Date.now() }]
            }">
                @csrf

                @if (isset($booking))
                    <div class="mb-4 rounded-r border-l-4 border-blue-500 bg-blue-50 p-4 shadow-sm">
                        <h3 class="text-sm font-bold uppercase text-blue-800">Laporan dari Mekanik</h3>
                        <p class="mt-1 text-sm text-blue-900"><strong>Pelanggan:</strong>
                            {{ $booking->pelanggan->nama_pelanggan }}</p>
                        <p class="text-sm text-blue-900"><strong>Motor:</strong> {{ $booking->tipe_motor }}
                            ({{ $booking->plat_nomor }})</p>
                        <p class="mt-2 text-sm text-blue-900"><strong>🛠️ Sparepart Diganti:</strong>
                            {{ $booking->sparepart_terpakai }}</p>
                        <p class="text-sm text-blue-900"><strong>📝 Catatan:</strong> {{ $booking->catatan_mekanik }}
                        </p>

                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                    </div>
                @endif

                <div class="mb-6 grid grid-cols-1 gap-6 border-b border-gray-200 pb-6 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-gray-700">Kode Transaksi</label>
                        <input type="text" name="kode_transaksi" value="{{ $kode_transaksi }}" readonly
                            class="w-full rounded border bg-gray-100 px-3 py-2 text-gray-700 outline-none">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-bold text-gray-700">Tanggal Servis</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                            class="w-full rounded border px-3 py-2 text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-bold text-gray-700">Pilih Pelanggan</label>
                        <select name="pelanggan_id" required
                            class="w-full rounded border px-3 py-2 text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach ($pelanggans as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_pelanggan }} ({{ $p->no_telp }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-bold text-gray-700">Pilih Mekanik</label>
                        <select name="mekanik_id" required
                            class="w-full rounded border px-3 py-2 text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="">-- Pilih Mekanik --</option>
                            @foreach ($mekaniks as $m)
                                <option value="{{ $m->id }}">{{ $m->nama_mekanik }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="mb-1 block font-semibold">Jenis Jasa Servis <span
                                class="text-red-500">*</span></label>
                        <select name="service_id" class="w-full rounded border p-2 focus:ring-2 focus:ring-blue-500"
                            required>
                            <option value="">-- Pilih Jasa Servis --</option>
                            @foreach ($services as $s)
                                <option value="{{ $s->id }}">{{ $s->nama_service }} - Rp
                                    {{ number_format($s->harga, 0, ',', '.') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-bold text-gray-700">Keluhan Kendaraan</label>
                        <textarea name="keluhan" rows="2"
                            class="w-full rounded border px-3 py-2 text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            placeholder="Contoh: Mesin brebet, ganti oli rutin..."></textarea>
                    </div>
                </div>

                <div class="mb-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800">Keranjang Sparepart</h3>
                        <button type="button" @click="barisSparepart.push({ id: Date.now() })"
                            class="rounded bg-indigo-600 px-3 py-1.5 text-sm font-bold text-white transition hover:bg-indigo-700">
                            + Tambah Sparepart
                        </button>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                        <template x-for="(baris, index) in barisSparepart" :key="baris.id">
                            <div class="mb-3 flex items-end gap-4">
                                <div class="flex-1">
                                    <label class="mb-1 block text-xs font-bold text-gray-600">Pilih Sparepart</label>
                                    <select name="sparepart_id[]"
                                        class="w-full rounded border px-3 py-2 text-sm text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        <option value="">-- Pilih --</option>
                                        @foreach ($spareparts as $s)
                                            <option value="{{ $s->id }}">{{ $s->nama_sparepart }} - Rp
                                                {{ number_format($s->harga, 0, ',', '.') }} (Stok:
                                                {{ $s->stok }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-24">
                                    <label class="mb-1 block text-xs font-bold text-gray-600">Qty</label>
                                    <input type="number" name="jumlah[]" min="1" value="1"
                                        class="w-full rounded border px-3 py-2 text-sm text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                </div>
                                <div>
                                    <button type="button" @click="barisSparepart.splice(index, 1)"
                                        x-show="barisSparepart.length > 1"
                                        class="rounded bg-red-100 p-2 text-red-600 transition hover:bg-red-200">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 border-t border-gray-200 pt-6">
                    <a href="{{ route('transaksi.index') }}"
                        class="rounded bg-gray-500 px-6 py-2 font-bold text-white transition hover:bg-gray-600">
                        Batal
                    </a>
                    <button type="submit"
                        class="rounded bg-blue-600 px-6 py-2 font-bold text-white shadow-md transition hover:bg-blue-700">
                        Simpan & Proses Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
