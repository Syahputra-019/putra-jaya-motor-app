<x-layout>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Data Komplain Pelanggan</h2>
            <p class="text-sm text-slate-500">Daftar keluhan pasca servis dari pelanggan.</p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto p-4">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="border-b border-slate-200 bg-slate-50 text-slate-700">
                    <tr>
                        <th class="p-4 font-bold">Tanggal</th>
                        <th class="p-4 font-bold">Pelanggan</th>
                        <th class="p-4 font-bold">Motor / Transaksi</th>
                        <th class="p-4 font-bold">Keluhan</th>
                        <th class="p-4 font-bold">Status</th>
                        <th class="p-4 text-center font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($komplains as $item)
                        <tr class="transition hover:bg-slate-50">
                            <td class="p-4">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                            <td class="p-4 font-semibold text-slate-800">{{ $item->user->name ?? 'Anonim' }}</td>
                            <td class="p-4">
                                <span class="rounded-md bg-blue-100 px-2 py-1 text-xs font-bold text-blue-700">
                                    {{ $item->booking->tipe_motor ?? 'Tidak diketahui' }}
                                </span>
                            </td>
                            <td class="max-w-xs truncate p-4" title="{{ $item->deskripsi_komplain }}">
                                {{ $item->deskripsi_komplain }}
                            </td>
                            <td class="p-4">
                                @if ($item->status === 'menunggu')
                                    <span
                                        class="rounded-full bg-rose-100 px-2 py-1 text-xs font-bold text-rose-600">Menunggu</span>
                                @elseif($item->status === 'diproses')
                                    <span
                                        class="rounded-full bg-orange-100 px-2 py-1 text-xs font-bold text-orange-600">Diproses</span>
                                @else
                                    <span
                                        class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-bold text-emerald-600">Selesai</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <div x-data="{ modalBuka: false }">
                                    <button @click="modalBuka = true"
                                        class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-bold text-white transition hover:bg-blue-700">
                                        Lihat & Balas
                                    </button>

                                    <div x-show="modalBuka"
                                        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 px-4"
                                        style="display: none;">
                                        <div @click.away="modalBuka = false"
                                            class="w-full max-w-lg rounded-2xl bg-white p-6 text-left shadow-xl">
                                            <h3 class="mb-4 text-lg font-bold text-slate-800">Detail Komplain</h3>

                                            <div class="mb-4 rounded-xl border border-slate-100 bg-slate-50 p-4">
                                                <p class="text-sm text-slate-600"><strong>Keluhan:</strong></p>
                                                <p class="mt-1 text-sm text-slate-800">{{ $item->deskripsi_komplain }}
                                                </p>

                                                @if ($item->foto_bukti)
                                                    <div class="mt-3">
                                                        <a href="{{ asset('uploads/komplain/' . $item->foto_bukti) }}"
                                                            target="_blank"
                                                            class="text-xs text-blue-600 underline">Lihat Foto Bukti</a>
                                                    </div>
                                                @endif
                                            </div>

                                            <form action="{{ route('komplain.tanggapi', $item->id) }}"
                                                method="POST">
                                                @csrf
                                                <div class="mb-4">
                                                    <label class="mb-2 block text-sm font-bold text-slate-700">Tanggapan
                                                        Admin</label>
                                                    <textarea name="tanggapan" rows="3"
                                                        class="w-full rounded-lg border border-slate-300 p-3 text-sm focus:ring-blue-500" required
                                                        placeholder="Tulis solusi atau balasan di sini...">{{ $item->tanggapan_bengkel }}</textarea>
                                                </div>
                                                <div class="flex justify-end gap-2">
                                                    <button type="button" @click="modalBuka = false"
                                                        class="px-4 py-2 text-sm font-bold text-slate-500 hover:text-slate-700">Batal</button>
                                                    <button type="submit"
                                                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-bold text-white hover:bg-blue-700">Kirim
                                                        Balasan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-500">Belum ada komplain dari pelanggan.
                                Bengkel lu aman bro! 🛠️</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layout>
