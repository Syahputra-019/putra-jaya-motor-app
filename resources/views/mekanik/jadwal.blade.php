<x-layout>
    <div class="page-shell">
        <div class="page-header">
            <div class="page-header-split">
                <p class="page-kicker">Mechanic Queue</p>
                <h1 class="page-title">Jadwal servis</h1>
                <p class="page-description">
                    Pantau alokasi tugas dan jadwal servis kendaraan yang sedang atau akan dikerjakan oleh masing-masing mekanik secara real-time.
                </p>
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

        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="font-black">!</div>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($bookings->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">JD</div>
                <h3 class="text-xl font-bold text-slate-950">Belum ada antrean</h3>
                <p class="max-w-xl text-sm leading-6 text-slate-500">Saat ini belum ada motor yang ditugaskan. Begitu
                    ada antrean, kartu servis akan muncul di sini.</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                @foreach ($bookings as $b)
                    @php
                        $nomorAntrean = $b->kode_antrean ?? ($b->nomor_antrean ? '#' . str_pad((string) $b->nomor_antrean, 3, '0', STR_PAD_LEFT) : '-');
                    @endphp
                    <div class="surface-card">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="page-kicker">Waktu Booking
                                    {{ \Carbon\Carbon::parse($b->jadwal_booking)->format('d M Y') }}</p>
                                <div class="mt-2 inline-flex rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] text-blue-700">
                                    Antrean {{ $nomorAntrean }}
                                </div>
                                <h2 class="mt-2 text-2xl font-bold text-slate-950">
                                    {{ $b->pelanggan->nama_pelanggan ?? $b->pelanggan->name }}</h2>
                                <p class="mt-2 text-sm text-slate-500">{{ $b->tipe_motor }} - {{ $b->plat_nomor }}</p>
                            </div>
                            <span class="badge {{ $b->status == 'menunggu' ? 'badge-warning' : 'badge-info' }}">
                                {{ ucfirst($b->status) }}
                            </span>
                        </div>

                        <div
                            class="mt-5 rounded-[22px] border border-slate-100 bg-slate-50/80 p-4 text-sm leading-7 text-slate-600">
                            {{ \Illuminate\Support\Str::limit($b->keluhan, 160) }}
                        </div>

                        {{-- [TAMBAHAN] Menampilkan permintaan awal dari pelanggan --}}
                        @if (!empty($b->kategori_servis) || !empty($b->sparepart_diminta))
                            <div class="mt-5 space-y-4 rounded-[22px] border border-blue-100 bg-blue-50/80 p-4">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-blue-600">Permintaan Awal
                                    Pelanggan</h4>
                                <div class="grid grid-cols-1 gap-x-4 gap-y-3 sm:grid-cols-2">
                                    @if (!empty($b->kategori_servis) && is_array($b->kategori_servis))
                                        <div>
                                            <p class="mb-1.5 text-sm font-semibold text-slate-700">Layanan Dipilih:</p>
                                            <ul class="list-inside list-disc space-y-1 text-sm text-slate-600">
                                                @foreach ($b->kategori_servis as $layanan)
                                                    <li>{{ $layanan }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    @if (!empty($b->sparepart_diminta) && is_array($b->sparepart_diminta))
                                        <div>
                                            <p class="mb-1.5 text-sm font-semibold text-slate-700">Request Sparepart:
                                            </p>
                                            <ul class="list-inside list-disc space-y-1 text-sm text-slate-600">
                                                @foreach ($b->sparepart_diminta as $part)
                                                    <li>{{ $part }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if ($b->status == 'menunggu')
                            <form action="{{ route('mekanik.jadwal.update', $b->id) }}" method="POST" class="mt-6">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="diproses">
                                <button type="submit" class="btn-primary w-full">Mulai Kerjakan</button>
                            </form>
                        @else
                            <form action="{{ route('mekanik.jadwal.update', $b->id) }}" method="POST"
                                class="form-shell mt-6">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="selesai">

                                {{-- [MODIFIKASI] Pre-fill input sparepart terpakai dari request & rekomendasi --}}
                                @php
                                    $partTerpakaiValue = $b->sparepart_terpakai; // Default ke nilai yang sudah ada

                                    // Jika belum ada, bangun dari request & rekomendasi
                                    if (empty($partTerpakaiValue)) {
                                        $parts = [];
                                        // 1. Dari request awal pelanggan
                                        if (!empty($b->sparepart_diminta) && is_array($b->sparepart_diminta)) {
                                            $parts = array_merge($parts, $b->sparepart_diminta);
                                        }
                                        // 2. Dari rekomendasi yang disetujui
                                        if (
                                            $b->status_konfirmasi === 'approved' &&
                                            !empty($b->rekomendasi_sparepart) &&
                                            is_array($b->rekomendasi_sparepart)
                                        ) {
                                            foreach ($b->rekomendasi_sparepart as $rek) {
                                                $parts[] = $rek['nama'] . ' (x' . $rek['jumlah'] . ')';
                                            }
                                        }
                                        $partTerpakaiValue = implode(', ', array_unique(array_filter($parts)));
                                    }
                                @endphp
                                <div class="form-field">
                                    <label class="field-label" for="sparepart_terpakai_{{ $b->id }}">Sparepart
                                        diganti</label>
                                    <input id="sparepart_terpakai_{{ $b->id }}" type="text"
                                        name="sparepart_terpakai" class="form-input"
                                        placeholder="Contoh: Kampas rem, oli mesin" required
                                        value="{{ old('sparepart_terpakai', $partTerpakaiValue) }}">
                                </div>

                                <div class="form-field">
                                    <label class="field-label" for="catatan_mekanik_{{ $b->id }}">Catatan
                                        servis</label>
                                    <textarea id="catatan_mekanik_{{ $b->id }}" name="catatan_mekanik" class="form-textarea"
                                        placeholder="Tindakan yang sudah dikerjakan..." required></textarea>
                                </div>

                                <button type="submit" class="btn-accent w-full">Simpan dan Selesaikan</button>
                            </form>

                            <hr class="my-6 border-slate-200">

                            <!-- Section Rekomendasi Perbaikan Tambahan -->
                            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                                <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50/50 px-5 py-4">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 shadow-sm">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-semibold text-slate-900">Rekomendasi Perbaikan</h3>
                                        <p class="mt-0.5 text-xs text-slate-500">Ajukan pergantian part di luar keluhan
                                            awal</p>
                                    </div>
                                </div>

                                <div class="p-5">
                                    @if (!empty($b->rekomendasi_sparepart))
                                        <div
                                            class="{{ $b->status_konfirmasi === 'approved' ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : ($b->status_konfirmasi === 'rejected' ? 'bg-red-50 text-red-700 ring-red-200' : 'bg-amber-50 text-amber-700 ring-amber-200') }} mb-6 rounded-xl p-4 text-sm ring-1 ring-inset">
                                            <div class="mb-3 flex items-center gap-2">
                                                @if ($b->status_konfirmasi === 'approved')
                                                    <svg class="h-5 w-5 text-emerald-500" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="font-semibold text-emerald-900">Disetujui
                                                        Pelanggan</span>
                                                @elseif($b->status_konfirmasi === 'rejected')
                                                    <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="font-semibold text-red-900">Ditolak Pelanggan</span>
                                                @else
                                                    <svg class="h-5 w-5 text-amber-500" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="font-semibold text-amber-900">Menunggu
                                                        Persetujuan</span>
                                                @endif
                                            </div>
                                            <ul class="ml-7 space-y-1.5">
                                                @foreach ($b->rekomendasi_sparepart as $rek)
                                                    <li class="flex items-center justify-between text-sm">
                                                        <span>{{ $rek['nama'] }} <span
                                                                class="opacity-75">x{{ $rek['jumlah'] }}</span></span>
                                                        <span class="font-medium">Rp
                                                            {{ number_format($rek['harga'] * $rek['jumlah'], 0, ',', '.') }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form action="{{ route('mekanik.jadwal.rekomendasi', $b->id) }}" method="POST"
                                        class="space-y-4">
                                        @csrf
                                        <div
                                            class="rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 via-white to-slate-100/80 p-4 shadow-sm">
                                            <div
                                                class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                                                <div>
                                                    <p
                                                        class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">
                                                        Form rekomendasi
                                                    </p>
                                                    <h4 class="mt-1 text-base font-semibold text-slate-900">
                                                        Tambahkan sparepart tambahan dengan detail yang jelas
                                                    </h4>
                                                </div>
                                                <div
                                                    class="inline-flex items-center gap-2 self-start rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-medium text-slate-500 shadow-sm">
                                                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                                                    Data sparepart aktif dari stok bengkel
                                                </div>
                                            </div>

                                            <p class="mt-4 text-sm leading-6 text-slate-600">
                                                Part baru akan ditambahkan ke daftar rekomendasi yang sudah ada, lalu
                                                status
                                                persetujuan pelanggan akan kembali menjadi menunggu.
                                            </p>
                                        </div>

                                        @if ($spareparts->isEmpty())
                                            <div
                                                class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm leading-6 text-amber-800">
                                                Belum ada sparepart bengkel dengan stok tersedia. Anda masih bisa
                                                menambahkan part luar secara manual.
                                            </div>
                                        @endif

                                        <div id="sparepart-container-{{ $b->id }}" class="space-y-4">
                                            <!-- Container kosong awalnya. Input akan muncul saat tombol ditekan -->
                                        </div>

                                        @php
                                            $layananLainnya = [];
                                            // Deteksi kalau ada keluhan jasa 'Lainnya' dan belum pernah dikirim rekomendasinya
                                            if (empty($b->rekomendasi_sparepart) && !empty($b->kategori_servis) && is_array($b->kategori_servis)) {
                                                foreach ($b->kategori_servis as $layanan) {
                                                    if (str_starts_with($layanan, 'Lainnya: ')) {
                                                        $layananLainnya[] = substr($layanan, 9);
                                                    }
                                                }
                                            }
                                        @endphp

                                        <div class="flex flex-wrap items-center gap-3 border-t border-slate-100 pt-5">
                                            <button type="button" onclick="addPartBengkel({{ $b->id }})"
                                                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-all hover:-translate-y-0.5 hover:border-slate-300 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50"
                                                @disabled($spareparts->isEmpty())>
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Tambah Part Bengkel
                                            </button>

                                            <button type="button" onclick="addPartLuar({{ $b->id }})"
                                                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-all hover:-translate-y-0.5 hover:border-slate-300 hover:bg-slate-50">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Tambah Part Luar
                                            </button>

                                            <button type="button" onclick="addJasa({{ $b->id }})"
                                                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-all hover:-translate-y-0.5 hover:border-slate-300 hover:bg-slate-50">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Tambah Jasa / Layanan
                                            </button>

                                            <button type="submit" id="btn-submit-rek-{{ $b->id }}"
                                                class="hidden items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-slate-900/10 transition-all hover:-translate-y-0.5 hover:bg-slate-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-900">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Kirim Rekomendasi
                                            </button>
                                        </div>

                                        <!-- Template Part Bengkel -->
                                        <div id="tpl-part-bengkel-{{ $b->id }}" class="hidden">
                                            <div
                                                class="sparepart-row rounded-2xl border border-slate-200 bg-white p-4 shadow-sm shadow-slate-200/70">
                                                <div class="flex items-start justify-between gap-3">
                                                    <div>
                                                        <p
                                                            class="text-xs font-semibold uppercase tracking-[0.2em] text-sky-500">
                                                            Sparepart bengkel
                                                        </p>
                                                        <h4 class="mt-1 text-sm font-semibold text-slate-900">
                                                            Pilih dari stok yang tersedia
                                                        </h4>
                                                    </div>
                                                    <button type="button"
                                                        class="btn-remove rounded-xl border border-slate-200 p-2 text-slate-400 transition-colors hover:border-red-200 hover:bg-red-50 hover:text-red-600"
                                                        onclick="removeRow(this, {{ $b->id }})"
                                                        title="Hapus">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>

                                                <div class="mt-4 grid gap-4 lg:grid-cols-[minmax(0,1fr)_112px]">
                                                    <div class="space-y-3">
                                                        <div>
                                                            <label
                                                                class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                                                                Daftar part
                                                            </label>
                                                            <select name="sparepart_id[]" data-role="sparepart-select"
                                                                class="block w-full rounded-xl border-0 bg-slate-50 py-3 pl-4 pr-10 text-sm leading-6 text-slate-900 ring-1 ring-inset ring-slate-200 transition-colors hover:bg-white focus:ring-2 focus:ring-inset focus:ring-slate-900"
                                                                disabled required>
                                                                <option value="" data-nama="" data-stok=""
                                                                    data-harga="">
                                                                    Pilih sparepart bengkel
                                                                </option>
                                                                @foreach ($spareparts as $sparepart)
                                                                    <option value="{{ $sparepart->id }}"
                                                                        data-nama="{{ $sparepart->nama_sparepart }}"
                                                                        data-stok="{{ $sparepart->stok }}"
                                                                        data-harga="{{ $sparepart->harga }}">
                                                                        {{ $sparepart->nama_sparepart }} - Stok
                                                                        {{ $sparepart->stok }} - Rp
                                                                        {{ number_format($sparepart->harga, 0, ',', '.') }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div
                                                            class="grid gap-3 rounded-xl border border-dashed border-slate-200 bg-slate-50/80 p-3 sm:grid-cols-3">
                                                            <div>
                                                                <p
                                                                    class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                                                                    Nama barang
                                                                </p>
                                                                <p class="mt-1 text-sm font-semibold text-slate-700"
                                                                    data-meta="nama">
                                                                    Belum dipilih
                                                                </p>
                                                            </div>
                                                            <div>
                                                                <p
                                                                    class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                                                                    Stok tersedia
                                                                </p>
                                                                <p class="mt-1 text-sm font-semibold text-slate-700"
                                                                    data-meta="stok">
                                                                    -
                                                                </p>
                                                            </div>
                                                            <div>
                                                                <p
                                                                    class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                                                                    Harga satuan
                                                                </p>
                                                                <p class="mt-1 text-sm font-semibold text-slate-700"
                                                                    data-meta="harga">
                                                                    -
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                                                            Qty
                                                        </label>
                                                        <input type="number" name="jumlah[]" min="1"
                                                            value="1"
                                                            class="block w-full rounded-xl border-0 bg-slate-50 px-4 py-3 text-sm font-medium leading-6 text-slate-900 ring-1 ring-inset ring-slate-200 transition-colors placeholder:text-slate-400 hover:bg-white focus:ring-2 focus:ring-inset focus:ring-slate-900"
                                                            placeholder="1" disabled required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Template Part Luar -->
                                        <div id="tpl-part-luar-{{ $b->id }}" class="hidden">
                                            <div
                                                class="sparepart-row rounded-2xl border border-slate-200 bg-white p-4 shadow-sm shadow-slate-200/70">
                                                <div class="flex items-start justify-between gap-3">
                                                    <div>
                                                        <p
                                                            class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-500">
                                                            Part luar
                                                        </p>
                                                        <h4 class="mt-1 text-sm font-semibold text-slate-900">
                                                            Tambahkan barang custom di luar stok bengkel
                                                        </h4>
                                                    </div>
                                                    <button type="button"
                                                        class="btn-remove rounded-xl border border-slate-200 p-2 text-slate-400 transition-colors hover:border-red-200 hover:bg-red-50 hover:text-red-600"
                                                        onclick="removeRow(this, {{ $b->id }})"
                                                        title="Hapus">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>

                                                <div class="mt-4 grid gap-4 lg:grid-cols-[minmax(0,1fr)_160px_112px]">
                                                    <div>
                                                        <label
                                                            class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                                                            Nama barang
                                                        </label>
                                                        <input type="text" name="nama_part_luar[]"
                                                            class="block w-full rounded-xl border-0 bg-slate-50 px-4 py-3 text-sm leading-6 text-slate-900 ring-1 ring-inset ring-slate-200 transition-colors placeholder:text-slate-400 hover:bg-white focus:ring-2 focus:ring-inset focus:ring-slate-900"
                                                            placeholder="Contoh: Busi iridium aftermarket" disabled
                                                            required>
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                                                            Harga
                                                        </label>
                                                        <input type="number" name="harga_part_luar[]" min="0"
                                                            class="block w-full rounded-xl border-0 bg-slate-50 px-4 py-3 text-sm leading-6 text-slate-900 ring-1 ring-inset ring-slate-200 transition-colors placeholder:text-slate-400 hover:bg-white focus:ring-2 focus:ring-inset focus:ring-slate-900"
                                                            placeholder="0" disabled required>
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                                                            Qty
                                                        </label>
                                                        <input type="number" name="jumlah_luar[]" min="1"
                                                            value="1"
                                                            class="block w-full rounded-xl border-0 bg-slate-50 px-4 py-3 text-sm leading-6 text-slate-900 ring-1 ring-inset ring-slate-200 transition-colors placeholder:text-slate-400 hover:bg-white focus:ring-2 focus:ring-inset focus:ring-slate-900"
                                                            placeholder="1" disabled required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Template Jasa / Layanan -->
                                        <div id="tpl-jasa-{{ $b->id }}" class="hidden">
                                            <div class="sparepart-row rounded-2xl border border-slate-200 bg-white p-4 shadow-sm shadow-slate-200/70">
                                                <div class="flex items-start justify-between gap-3">
                                                    <div>
                                                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-500">
                                                            Jasa / Layanan
                                                        </p>
                                                        <h4 class="mt-1 text-sm font-semibold text-slate-900">
                                                            Biaya pengerjaan / layanan custom
                                                        </h4>
                                                    </div>
                                                    <button type="button" class="btn-remove rounded-xl border border-slate-200 p-2 text-slate-400 transition-colors hover:border-red-200 hover:bg-red-50 hover:text-red-600" onclick="removeRow(this, {{ $b->id }})" title="Hapus">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </div>

                                                <div class="mt-4 grid gap-4 lg:grid-cols-[minmax(0,1fr)_160px]">
                                                    <div>
                                                        <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Nama Jasa</label>
                                                        <input type="text" name="nama_jasa[]" class="block w-full rounded-xl border-0 bg-slate-50 px-4 py-3 text-sm leading-6 text-slate-900 ring-1 ring-inset ring-slate-200 transition-colors placeholder:text-slate-400 hover:bg-white focus:ring-2 focus:ring-inset focus:ring-slate-900" placeholder="Contoh: Pasang Spion / Jasa Kelistrikan" disabled required>
                                                    </div>
                                                    <div>
                                                        <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Harga Jasa</label>
                                                        <input type="number" name="harga_jasa[]" min="0" class="block w-full rounded-xl border-0 bg-slate-50 px-4 py-3 text-sm leading-6 text-slate-900 ring-1 ring-inset ring-slate-200 transition-colors placeholder:text-slate-400 hover:bg-white focus:ring-2 focus:ring-inset focus:ring-slate-900" placeholder="0" disabled required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if(!empty($layananLainnya))
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    @foreach($layananLainnya as $jasa)
                                                        addJasa({{ $b->id }}, "{{ $jasa }}");
                                                    @endforeach
                                                });
                                            </script>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        function formatRupiah(angka) {
            const nilai = Number(angka || 0);
            return `Rp ${new Intl.NumberFormat('id-ID').format(nilai)}`;
        }

        function toggleSubmitButton(bookingId) {
            const container = document.getElementById('sparepart-container-' + bookingId);
            const button = document.getElementById('btn-submit-rek-' + bookingId);

            if (!container || !button) {
                return;
            }

            if (container.children.length === 0) {
                button.classList.add('hidden');
                button.classList.remove('inline-flex');
                return;
            }

            button.classList.remove('hidden');
            button.classList.add('inline-flex');
        }

        function updateSparepartMeta(select) {
            const row = select.closest('.sparepart-row');

            if (!row) {
                return;
            }

            const option = select.options[select.selectedIndex];
            const namaEl = row.querySelector('[data-meta="nama"]');
            const stokEl = row.querySelector('[data-meta="stok"]');
            const hargaEl = row.querySelector('[data-meta="harga"]');

            if (!option || !option.value) {
                namaEl.textContent = 'Belum dipilih';
                stokEl.textContent = '-';
                hargaEl.textContent = '-';
                return;
            }

            namaEl.textContent = option.dataset.nama || option.textContent.trim();
            stokEl.textContent = `${option.dataset.stok || 0} unit`;
            hargaEl.textContent = formatRupiah(option.dataset.harga || 0);
        }

        function addPartBengkel(bookingId) {
            const container = document.getElementById('sparepart-container-' + bookingId);
            const template = document.getElementById('tpl-part-bengkel-' + bookingId);
            const clone = template.firstElementChild.cloneNode(true);

            // Buka gembok disabled supaya inputan bisa diketik
            clone.querySelectorAll('input, select').forEach(el => el.removeAttribute('disabled'));
            container.appendChild(clone);
            const select = clone.querySelector('[data-role="sparepart-select"]');
            if (select) {
                updateSparepartMeta(select);
                select.focus();
            }

            toggleSubmitButton(bookingId);
        }

        function addPartLuar(bookingId) {
            const container = document.getElementById('sparepart-container-' + bookingId);
            const template = document.getElementById('tpl-part-luar-' + bookingId);
            const clone = template.firstElementChild.cloneNode(true);

            // Buka gembok disabled
            clone.querySelectorAll('input, select').forEach(el => el.removeAttribute('disabled'));
            container.appendChild(clone);
            const firstInput = clone.querySelector('input');
            if (firstInput) {
                firstInput.focus();
            }

            toggleSubmitButton(bookingId);
        }

        function addJasa(bookingId, defaultName = '') {
            const container = document.getElementById('sparepart-container-' + bookingId);
            const template = document.getElementById('tpl-jasa-' + bookingId);
            if (!template) return;
            
            const clone = template.firstElementChild.cloneNode(true);
            clone.querySelectorAll('input, select').forEach(el => el.removeAttribute('disabled'));
            
            if (defaultName) {
                const nameInput = clone.querySelector('input[name="nama_jasa[]"]');
                if (nameInput) nameInput.value = defaultName;
            }

            container.appendChild(clone);
            // Focus ke harga kalau nama otomatis terisi, kalau nama belum ada, fokus ke nama.
            const firstInput = defaultName ? clone.querySelector('input[name="harga_jasa[]"]') : clone.querySelector('input[name="nama_jasa[]"]');
            if (firstInput) firstInput.focus();

            toggleSubmitButton(bookingId);
        }

        function removeRow(button, bookingId) {
            button.closest('.sparepart-row').remove();
            toggleSubmitButton(bookingId);
        }

        document.addEventListener('change', function(event) {
            if (event.target.matches('[data-role="sparepart-select"]')) {
                updateSparepartMeta(event.target);
            }
        });
    </script>
</x-layout>
