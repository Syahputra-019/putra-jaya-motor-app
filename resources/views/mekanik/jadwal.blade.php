<x-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">🔧 Jadwal Servis Hari Ini</h1>
        <p class="mt-1 text-slate-500">Daftar antrean motor yang harus lu kerjakan bro.</p>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl border border-green-400 bg-green-100 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-xl border bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase text-slate-500">Waktu</th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase text-slate-500">Pelanggan / Motor</th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase text-slate-500">Keluhan</th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase text-slate-500">Status</th>
                    <th class="px-6 py-4 text-center text-xs font-bold uppercase text-slate-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @forelse ($bookings as $b)
                    <tr class="transition hover:bg-slate-50">
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-600">
                            {{ \Carbon\Carbon::parse($b->jadwal_booking)->format('H:i') }} WIB
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-slate-900">
                                {{ $b->pelanggan->nama_pelanggan ?? $b->pelanggan->name }}</div>
                            <div class="text-xs text-slate-500">{{ $b->tipe_motor }} ({{ $b->plat_nomor }})</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ Str::limit($b->keluhan, 50) }}
                        </td>
                        <td class="px-6 py-4">
                            @if ($b->status == 'menunggu')
                                <span
                                    class="rounded-full bg-amber-100 px-3 py-1 text-[10px] font-bold uppercase text-amber-700">Menunggu</span>
                            @else
                                <span
                                    class="rounded-full bg-blue-100 px-3 py-1 text-[10px] font-bold uppercase text-blue-700">Diproses</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if ($b->status == 'menunggu')
                                <form action="{{ route('mekanik.jadwal.update', $b->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="diproses">
                                    <input type="hidden" name="pelanggan_id" value="{{ $b->pelanggan_id }}">
                                    <input type="hidden" name="plat_nomor" value="{{ $b->plat_nomor }}">
                                    <input type="hidden" name="tipe_motor" value="{{ $b->tipe_motor }}">
                                    <input type="hidden" name="keluhan" value="{{ $b->keluhan }}">
                                    <input type="hidden" name="jadwal_booking" value="{{ $b->jadwal_booking }}">

                                    <button type="submit"
                                        class="rounded-lg bg-blue-600 px-4 py-2 text-xs font-bold text-white hover:bg-blue-700">
                                        Mulai Kerjakan
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('mekanik.jadwal.update', $b->id) }}" method="POST" class="mt-2 text-left bg-slate-50 p-3 rounded-xl border border-slate-200 w-64">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="selesai">
                                    <input type="hidden" name="pelanggan_id" value="{{ $b->pelanggan_id }}">
                                    <input type="hidden" name="plat_nomor" value="{{ $b->plat_nomor }}">
                                    <input type="hidden" name="tipe_motor" value="{{ $b->tipe_motor }}">
                                    <input type="hidden" name="keluhan" value="{{ $b->keluhan }}">
                                    <input type="hidden" name="jadwal_booking" value="{{ $b->jadwal_booking }}">
                                    <input type="hidden" name="mekanik_id" value="{{ $b->mekanik_id }}">

                                    <div class="mb-2">
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Sparepart Diganti</label>
                                        <input type="text" name="sparepart_terpakai" class="w-full text-xs p-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Cth: Kampas Rem, Oli..." required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Catatan Servis</label>
                                        <textarea name="catatan_mekanik" class="w-full text-xs p-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" rows="2" placeholder="Tindakan yang dikerjakan..." required></textarea>
                                    </div>

                                    <button type="submit"
                                        class="w-full rounded-lg bg-green-600 px-4 py-2 text-xs font-bold text-white hover:bg-green-700 transition shadow-md shadow-green-200">
                                        Simpan & Selesaikan
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <div
                                class="mb-4 inline-flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                                <i class="fas fa-tools text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-700">Belum Ada Antrean</h3>
                            <p class="mx-auto mt-2 max-w-md italic text-slate-500">Saat ini belum ada motor yang
                                ditugaskan buat lu. Santai dulu atau tanya admin kasir bro.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layout>
