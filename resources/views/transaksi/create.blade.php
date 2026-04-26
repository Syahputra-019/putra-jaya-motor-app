<x-layout>
    <div class="page-shell">
        <div class="page-header">
            <div class="page-header-split">
                <p class="page-kicker">Cashier Desk</p>
                <h1 class="page-title">Buat transaksi servis</h1>
                <p class="page-description">Catat jasa, sparepart, dan data pelanggan dalam satu form yang lebih bersih dan konsisten.</p>
            </div>
        </div>

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

        <div class="surface-card">
            <form action="{{ route('transaksi.store') }}" method="POST" x-data="{ barisSparepart: [{ id: Date.now() }] }" class="form-shell">
                @csrf

                @if (isset($booking))
                    <div class="alert alert-warning">
                        <div class="font-black">BK</div>
                        <div>
                            <div class="font-bold">Booking referensi terhubung</div>
                            <div class="mt-1 text-sm leading-6">
                                Pelanggan: {{ $booking->pelanggan->nama_pelanggan }}<br>
                                Motor: {{ $booking->tipe_motor }} ({{ $booking->plat_nomor }})<br>
                                Sparepart: {{ $booking->sparepart_terpakai }}<br>
                                Catatan: {{ $booking->catatan_mekanik }}
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
                        <input id="tanggal" type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="form-input" required>
                    </div>
                    <div class="form-field">
                        <label class="field-label" for="pelanggan_id">Pilih pelanggan</label>
                        <select id="pelanggan_id" name="pelanggan_id" class="form-select" required>
                            <option value="">-- Pilih pelanggan --</option>
                            @foreach ($pelanggans as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_pelanggan }} ({{ $p->no_telp }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-field">
                        <label class="field-label" for="mekanik_id">Pilih mekanik</label>
                        <select id="mekanik_id" name="mekanik_id" class="form-select" required>
                            <option value="">-- Pilih mekanik --</option>
                            @foreach ($mekaniks as $m)
                                <option value="{{ $m->id }}">{{ $m->nama_mekanik }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-field form-field-full">
                        <label class="field-label" for="service_id">Jenis jasa servis</label>
                        <select id="service_id" name="service_id" class="form-select" required>
                            <option value="">-- Pilih jasa servis --</option>
                            @foreach ($services as $s)
                                <option value="{{ $s->id }}">{{ $s->nama_service }} - Rp {{ number_format($s->harga, 0, ',', '.') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-field form-field-full">
                        <label class="field-label" for="keluhan">Keluhan kendaraan</label>
                        <textarea id="keluhan" name="keluhan" class="form-textarea" placeholder="Contoh: mesin brebet, ganti oli rutin">{{ old('keluhan') }}</textarea>
                    </div>
                </div>

                <div class="surface-card-soft">
                    <div class="flex flex-col gap-3 border-b border-slate-100 pb-5 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="section-title">Keranjang sparepart</h2>
                            <p class="section-subtitle">Tambahkan sparepart yang dipakai selama proses servis.</p>
                        </div>
                        <button type="button" @click="barisSparepart.push({ id: Date.now() + Math.random() })" class="btn-secondary">
                            Tambah Baris
                        </button>
                    </div>

                    <div class="mt-5 space-y-4">
                        <template x-for="(baris, index) in barisSparepart" :key="baris.id">
                            <div class="grid grid-cols-1 gap-4 rounded-[24px] border border-slate-100 bg-white p-4 md:grid-cols-[1fr_120px_auto]">
                                <div class="form-field">
                                    <label class="field-label">Pilih sparepart</label>
                                    <select name="sparepart_id[]" class="form-select">
                                        <option value="">-- Pilih --</option>
                                        @foreach ($spareparts as $s)
                                            <option value="{{ $s->id }}">{{ $s->nama_sparepart }} - Rp {{ number_format($s->harga, 0, ',', '.') }} (Stok: {{ $s->stok }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-field">
                                    <label class="field-label">Qty</label>
                                    <input type="number" name="jumlah[]" min="1" value="1" class="form-input">
                                </div>
                                <div class="flex items-end">
                                    <button type="button" @click="barisSparepart.splice(index, 1)" x-show="barisSparepart.length > 1" class="btn-danger w-full md:w-auto">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('transaksi.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Simpan dan Proses</button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
