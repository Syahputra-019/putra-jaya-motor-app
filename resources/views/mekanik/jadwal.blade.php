<x-layout>
    <div class="page-shell">
        <div class="page-header">
            <div class="page-header-split">
                <p class="page-kicker">Mechanic Queue</p>
                <h1 class="page-title">Jadwal servis</h1>
                <p class="page-description">Antrean kerja mekanik kini tampil dalam kartu-kartu rapi dengan aksi yang lebih fokus dan mudah dijalankan.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <div class="font-black">OK</div>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                <div class="font-black">!</div>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if ($bookings->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">JD</div>
                <h3 class="text-xl font-bold text-slate-950">Belum ada antrean</h3>
                <p class="max-w-xl text-sm leading-6 text-slate-500">Saat ini belum ada motor yang ditugaskan. Begitu ada antrean, kartu servis akan muncul di sini.</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                @foreach ($bookings as $b)
                    <div class="surface-card">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="page-kicker">Jadwal {{ \Carbon\Carbon::parse($b->jadwal_booking)->format('H:i') }} WIB</p>
                                <h2 class="mt-2 text-2xl font-bold text-slate-950">{{ $b->pelanggan->nama_pelanggan ?? $b->pelanggan->name }}</h2>
                                <p class="mt-2 text-sm text-slate-500">{{ $b->tipe_motor }} - {{ $b->plat_nomor }}</p>
                            </div>
                            <span class="badge {{ $b->status == 'menunggu' ? 'badge-warning' : 'badge-info' }}">
                                {{ ucfirst($b->status) }}
                            </span>
                        </div>

                        <div class="mt-5 rounded-[22px] border border-slate-100 bg-slate-50/80 p-4 text-sm leading-7 text-slate-600">
                            {{ \Illuminate\Support\Str::limit($b->keluhan, 160) }}
                        </div>

                        @if ($b->status == 'menunggu')
                            <form action="{{ route('mekanik.jadwal.update', $b->id) }}" method="POST" class="mt-6">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="diproses">
                                <button type="submit" class="btn-primary w-full">Mulai Kerjakan</button>
                            </form>
                        @else
                            <form action="{{ route('mekanik.jadwal.update', $b->id) }}" method="POST" class="form-shell mt-6">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="selesai">

                                <div class="form-field">
                                    <label class="field-label" for="sparepart_terpakai_{{ $b->id }}">Sparepart diganti</label>
                                    <input id="sparepart_terpakai_{{ $b->id }}" type="text" name="sparepart_terpakai"
                                        class="form-input" placeholder="Contoh: Kampas rem, oli mesin" required>
                                </div>

                                <div class="form-field">
                                    <label class="field-label" for="catatan_mekanik_{{ $b->id }}">Catatan servis</label>
                                    <textarea id="catatan_mekanik_{{ $b->id }}" name="catatan_mekanik" class="form-textarea" placeholder="Tindakan yang sudah dikerjakan..." required></textarea>
                                </div>

                                <button type="submit" class="btn-accent w-full">Simpan dan Selesaikan</button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layout>
