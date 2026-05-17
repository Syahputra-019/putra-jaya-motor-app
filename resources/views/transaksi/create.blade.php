<x-layout>
    @php
        $defaultTanggal = old('tanggal', optional($booking?->jadwal_booking)->format('Y-m-d') ?? date('Y-m-d'));
        $defaultPelangganId = old('pelanggan_id', $booking->pelanggan_id ?? '');
        $defaultMekanikId = old('mekanik_id', $booking->mekanik_id ?? '');
        $defaultKeluhan = old('keluhan', $booking->keluhan ?? '');

        $serviceIds = old('service_id', array_column($draftServiceRows, 'service_id'));
        $serviceQtys = old('service_qty', array_column($draftServiceRows, 'qty'));
        $initialServiceRows = collect($serviceIds)
            ->values()
            ->map(fn ($serviceId, $index) => [
                'key' => $index + 1,
                'service_id' => $serviceId,
                'qty' => $serviceQtys[$index] ?? 1,
            ])
            ->all();

        if (empty($initialServiceRows)) {
            $initialServiceRows = [['key' => 1, 'service_id' => '', 'qty' => 1]];
        }

        $sparepartIds = old('sparepart_id', array_column($draftSparepartRows, 'sparepart_id'));
        $sparepartQtys = old('jumlah', array_column($draftSparepartRows, 'qty'));
        $initialSparepartRows = collect($sparepartIds)
            ->values()
            ->map(fn ($sparepartId, $index) => [
                'key' => $index + 101,
                'sparepart_id' => $sparepartId,
                'qty' => $sparepartQtys[$index] ?? 1,
            ])
            ->all();

        if (empty($initialSparepartRows)) {
            $initialSparepartRows = [['key' => 101, 'sparepart_id' => '', 'qty' => 1]];
        }

        $customTypes = old('custom_item_type', array_column($draftCustomRows, 'jenis'));
        $customNames = old('custom_item_name', array_column($draftCustomRows, 'nama'));
        $customPrices = old('custom_item_price', array_column($draftCustomRows, 'harga'));
        $customQtys = old('custom_item_qty', array_column($draftCustomRows, 'qty'));
        $initialCustomRows = collect($customNames)
            ->values()
            ->map(fn ($nama, $index) => [
                'key' => $index + 201,
                'jenis' => $customTypes[$index] ?? 'part',
                'nama' => $nama,
                'harga' => $customPrices[$index] ?? 0,
                'qty' => $customQtys[$index] ?? 1,
            ])
            ->all();

        if (empty($initialCustomRows)) {
            $initialCustomRows = [['key' => 201, 'jenis' => 'part', 'nama' => '', 'harga' => 0, 'qty' => 1]];
        }

        $approvedRekomendasi = $booking?->status_konfirmasi === 'approved' ? $booking->rekomendasi_sparepart ?? [] : [];
    @endphp

    <div class="page-shell">
        <div class="page-header">
            <div class="page-header-split">
                <p class="page-kicker">Cashier Desk</p>
                <h1 class="page-title">Buat transaksi servis</h1>
                <p class="page-description">Booking selesai sekarang bisa langsung ditarik jadi draft transaksi lengkap, lalu tinggal lanjut ke pembayaran.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <div class="font-black">OK</div>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="font-black">!</div>
                <div>
                    <div class="font-bold">Transaksi belum bisa disimpan</div>
                    <ul class="mt-2 list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @if (!$booking && $bookingsSiapTransaksi->isNotEmpty())
            <div class="surface-card-soft">
                <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
                    <div>
                        <h2 class="section-title">Booking Siap Kasir</h2>
                        <p class="section-subtitle">Pilih booking yang sudah selesai agar jasa dan sparepartnya langsung dipindahkan ke draft transaksi.</p>
                    </div>
                    <div class="text-sm text-slate-500">{{ $bookingsSiapTransaksi->whereNull('transaksi')->count() }} booking siap diproses</div>
                </div>

                <div class="mt-5 grid gap-4 xl:grid-cols-2">
                    @forelse ($bookingsSiapTransaksi->whereNull('transaksi') as $bookingSiap)
                        <div class="rounded-[24px] border border-slate-100 bg-white p-5 shadow-sm shadow-slate-200/70">
                            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                <div>
                                    <div class="page-kicker">Booking #{{ $bookingSiap->id }}</div>
                                    <h3 class="mt-2 text-xl font-bold text-slate-950">
                                        {{ $bookingSiap->pelanggan->nama_pelanggan ?? 'Tanpa Nama' }}
                                    </h3>
                                    <p class="mt-2 text-sm leading-6 text-slate-500">
                                        {{ $bookingSiap->tipe_motor }} - {{ $bookingSiap->plat_nomor }}<br>
                                        Jadwal {{ \Carbon\Carbon::parse($bookingSiap->jadwal_booking)->format('d M Y, H:i') }}
                                    </p>
                                </div>
                                <a href="{{ route('transaksi.create', ['booking_id' => $bookingSiap->id]) }}" class="btn-primary">
                                    Ambil ke Kasir
                                </a>
                            </div>

                            <div class="mt-4 rounded-[20px] border border-slate-100 bg-slate-50/70 p-4 text-sm leading-6 text-slate-600">
                                {{ \Illuminate\Support\Str::limit($bookingSiap->keluhan, 140) }}
                            </div>
                        </div>
                    @empty
                        <div class="empty-state xl:col-span-2">
                            <div class="empty-state-icon">TR</div>
                            <h3 class="text-xl font-bold text-slate-950">Semua booking selesai sudah punya transaksi</h3>
                            <p class="max-w-lg text-sm leading-6 text-slate-500">Tidak ada booking selesai yang masih menunggu dipindahkan ke kasir saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif

        <div class="surface-card">
            <form action="{{ route('transaksi.store') }}" method="POST"
                x-data="transactionForm(@js($initialServiceRows), @js($initialSparepartRows), @js($initialCustomRows))"
                class="form-shell">
                @csrf

                @if ($booking)
                    <div class="alert alert-warning">
                        <div class="font-black">BK</div>
                        <div class="w-full">
                            <div class="font-bold">Booking referensi terhubung</div>
                            <div class="mt-1 text-sm leading-6">
                                Pelanggan: {{ $booking->pelanggan->nama_pelanggan }}<br>
                                Motor: {{ $booking->tipe_motor }} ({{ $booking->plat_nomor }})<br>
                                Keluhan: {{ $booking->keluhan ?: '-' }}<br>
                                Jadwal: {{ \Carbon\Carbon::parse($booking->jadwal_booking)->format('d M Y, H:i') }}
                            </div>

                            <div class="mt-4 grid gap-4 xl:grid-cols-3">
                                <div class="rounded-2xl border border-amber-200/70 bg-white/80 p-4">
                                    <div class="page-kicker">Jasa Dipilih</div>
                                    <div class="mt-2 text-sm leading-6 text-slate-700">
                                        @forelse ($booking->kategori_servis ?? [] as $kategori)
                                            <div>{{ $kategori }}</div>
                                        @empty
                                            <div>-</div>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="rounded-2xl border border-amber-200/70 bg-white/80 p-4">
                                    <div class="page-kicker">Sparepart Pelanggan</div>
                                    <div class="mt-2 text-sm leading-6 text-slate-700">
                                        @forelse ($booking->sparepart_diminta ?? [] as $part)
                                            <div>{{ $part }}</div>
                                        @empty
                                            <div>-</div>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="rounded-2xl border border-amber-200/70 bg-white/80 p-4">
                                    <div class="page-kicker">Rekomendasi Disetujui</div>
                                    <div class="mt-2 text-sm leading-6 text-slate-700">
                                        @forelse ($approvedRekomendasi as $rek)
                                            <div>{{ $rek['nama'] }} x{{ $rek['jumlah'] }}</div>
                                        @empty
                                            <div>-</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                        </div>
                    </div>
                @endif

                <div class="form-grid">
                    <div class="form-field">
                        <label class="field-label" for="kode_transaksi">Kode transaksi</label>
                        <input id="kode_transaksi" type="text" name="kode_transaksi" value="{{ $kode_transaksi }}" class="form-input" readonly>
                    </div>
                    <div class="form-field">
                        <label class="field-label" for="tanggal">Tanggal servis</label>
                        <input id="tanggal" type="date" name="tanggal" value="{{ $defaultTanggal }}" class="form-input" required>
                    </div>
                    <div class="form-field">
                        <label class="field-label" for="pelanggan_id">Pilih pelanggan</label>
                        <select id="pelanggan_id" name="pelanggan_id" class="form-select" required>
                            <option value="">-- Pilih pelanggan --</option>
                            @foreach ($pelanggans as $p)
                                <option value="{{ $p->id }}" @selected((string) $defaultPelangganId === (string) $p->id)>
                                    {{ $p->nama_pelanggan }} ({{ $p->no_telp }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-field">
                        <label class="field-label" for="mekanik_id">Pilih mekanik</label>
                        <select id="mekanik_id" name="mekanik_id" class="form-select" required>
                            <option value="">-- Pilih mekanik --</option>
                            @foreach ($mekaniks as $m)
                                <option value="{{ $m->id }}" @selected((string) $defaultMekanikId === (string) $m->id)>
                                    {{ $m->nama_mekanik }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-field form-field-full">
                        <label class="field-label" for="keluhan">Keluhan kendaraan</label>
                        <textarea id="keluhan" name="keluhan" class="form-textarea" placeholder="Contoh: mesin brebet, ganti oli rutin">{{ $defaultKeluhan }}</textarea>
                    </div>
                </div>

                <div class="surface-card-soft">
                    <div class="flex flex-col gap-3 border-b border-slate-100 pb-5 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="section-title">Jasa Servis</h2>
                            <p class="section-subtitle">Satu transaksi sekarang bisa memuat lebih dari satu jasa servis dari booking pelanggan.</p>
                        </div>
                        <button type="button" @click="addServiceRow()" class="btn-secondary">
                            Tambah Jasa
                        </button>
                    </div>

                    <div class="mt-5 space-y-4">
                        <template x-for="(row, index) in serviceRows" :key="row.key">
                            <div class="grid grid-cols-1 gap-4 rounded-[24px] border border-slate-100 bg-white p-4 md:grid-cols-[1fr_120px_auto]">
                                <div class="form-field">
                                    <label class="field-label">Jenis jasa servis</label>
                                    <select class="form-select" :name="`service_id[${index}]`" x-model="row.service_id">
                                        <option value="">-- Pilih jasa servis --</option>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}">
                                                {{ $service->nama_service }} - Rp {{ number_format($service->harga, 0, ',', '.') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-field">
                                    <label class="field-label">Qty</label>
                                    <input type="number" min="1" class="form-input" :name="`service_qty[${index}]`" x-model="row.qty">
                                </div>
                                <div class="flex items-end">
                                    <button type="button" @click="removeServiceRow(index)" class="btn-danger w-full md:w-auto" x-show="serviceRows.length > 1">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="surface-card-soft">
                    <div class="flex flex-col gap-3 border-b border-slate-100 pb-5 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="section-title">Sparepart Bengkel</h2>
                            <p class="section-subtitle">Sparepart dari booking, rekomendasi yang disetujui, dan catatan mekanik bisa langsung masuk ke transaksi.</p>
                        </div>
                        <button type="button" @click="addSparepartRow()" class="btn-secondary">
                            Tambah Sparepart
                        </button>
                    </div>

                    <div class="mt-5 space-y-4">
                        <template x-for="(row, index) in sparepartRows" :key="row.key">
                            <div class="grid grid-cols-1 gap-4 rounded-[24px] border border-slate-100 bg-white p-4 md:grid-cols-[1fr_120px_auto]">
                                <div class="form-field">
                                    <label class="field-label">Pilih sparepart</label>
                                    <select class="form-select" :name="`sparepart_id[${index}]`" x-model="row.sparepart_id">
                                        <option value="">-- Pilih sparepart --</option>
                                        @foreach ($spareparts as $sparepart)
                                            <option value="{{ $sparepart->id }}">
                                                {{ $sparepart->nama_sparepart }} - Rp {{ number_format($sparepart->harga, 0, ',', '.') }} (Stok: {{ $sparepart->stok }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-field">
                                    <label class="field-label">Qty</label>
                                    <input type="number" min="1" class="form-input" :name="`jumlah[${index}]`" x-model="row.qty">
                                </div>
                                <div class="flex items-end">
                                    <button type="button" @click="removeSparepartRow(index)" class="btn-danger w-full md:w-auto" x-show="sparepartRows.length > 1">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="surface-card-soft">
                    <div class="flex flex-col gap-3 border-b border-slate-100 pb-5 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="section-title">Item Manual</h2>
                            <p class="section-subtitle">Pakai bagian ini untuk jasa custom, part luar, atau item yang belum ada di master data bengkel.</p>
                        </div>
                        <button type="button" @click="addCustomRow()" class="btn-secondary">
                            Tambah Item
                        </button>
                    </div>

                    <div class="mt-5 space-y-4">
                        <template x-for="(row, index) in customRows" :key="row.key">
                            <div class="grid grid-cols-1 gap-4 rounded-[24px] border border-slate-100 bg-white p-4 xl:grid-cols-[180px_1fr_160px_120px_auto]">
                                <div class="form-field">
                                    <label class="field-label">Kategori</label>
                                    <select class="form-select" :name="`custom_item_type[${index}]`" x-model="row.jenis">
                                        <option value="part">Part Luar</option>
                                        <option value="jasa">Jasa Custom</option>
                                    </select>
                                </div>
                                <div class="form-field">
                                    <label class="field-label">Nama item</label>
                                    <input type="text" class="form-input" :name="`custom_item_name[${index}]`" x-model="row.nama" placeholder="Contoh: Servis rumah / Busi aftermarket">
                                </div>
                                <div class="form-field">
                                    <label class="field-label">Harga</label>
                                    <input type="number" min="0" class="form-input" :name="`custom_item_price[${index}]`" x-model="row.harga">
                                </div>
                                <div class="form-field">
                                    <label class="field-label">Qty</label>
                                    <input type="number" min="1" class="form-input" :name="`custom_item_qty[${index}]`" x-model="row.qty">
                                </div>
                                <div class="flex items-end">
                                    <button type="button" @click="removeCustomRow(index)" class="btn-danger w-full md:w-auto" x-show="customRows.length > 1">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('transaksi.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Simpan dan Lanjut Pembayaran</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function transactionForm(serviceRows, sparepartRows, customRows) {
            const fallbackServiceRows = serviceRows.length ? serviceRows : [{
                key: 1,
                service_id: '',
                qty: 1
            }];
            const fallbackSparepartRows = sparepartRows.length ? sparepartRows : [{
                key: 101,
                sparepart_id: '',
                qty: 1
            }];
            const fallbackCustomRows = customRows.length ? customRows : [{
                key: 201,
                jenis: 'part',
                nama: '',
                harga: 0,
                qty: 1
            }];

            const allKeys = [
                ...fallbackServiceRows.map((row) => row.key || 0),
                ...fallbackSparepartRows.map((row) => row.key || 0),
                ...fallbackCustomRows.map((row) => row.key || 0),
            ];

            return {
                nextKey: Math.max(0, ...allKeys) + 1,
                serviceRows: fallbackServiceRows,
                sparepartRows: fallbackSparepartRows,
                customRows: fallbackCustomRows,
                addServiceRow() {
                    this.serviceRows.push({
                        key: this.nextKey++,
                        service_id: '',
                        qty: 1
                    });
                },
                removeServiceRow(index) {
                    if (this.serviceRows.length > 1) {
                        this.serviceRows.splice(index, 1);
                    }
                },
                addSparepartRow() {
                    this.sparepartRows.push({
                        key: this.nextKey++,
                        sparepart_id: '',
                        qty: 1
                    });
                },
                removeSparepartRow(index) {
                    if (this.sparepartRows.length > 1) {
                        this.sparepartRows.splice(index, 1);
                    }
                },
                addCustomRow() {
                    this.customRows.push({
                        key: this.nextKey++,
                        jenis: 'part',
                        nama: '',
                        harga: 0,
                        qty: 1
                    });
                },
                removeCustomRow(index) {
                    if (this.customRows.length > 1) {
                        this.customRows.splice(index, 1);
                    }
                }
            };
        }
    </script>
</x-layout>
