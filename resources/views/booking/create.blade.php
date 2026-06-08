<x-layout>
    <div class="page-shell-sm">
        <div class="page-header">
            <div class="page-header-split">
                <p class="page-kicker">Queue Management</p>
                <h1 class="page-title">Tambah booking</h1>
                <p class="page-description">
                    Daftarkan jadwal reservasi servis baru untuk mengatur antrean kendaraan dan alokasi mekanik secara terstruktur.
                </p>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="font-black">!</div>
                <div>
                    <div class="font-bold">Data belum lengkap</div>
                    <ul class="mt-2 list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="surface-card">
            <form action="{{ route('booking.store') }}" method="POST" class="form-shell">
                @csrf

                <div class="form-field">
                    <label class="field-label" for="pelanggan_id">Pilih pelanggan</label>
                    <select id="pelanggan_id" name="pelanggan_id" class="form-select" required>
                        <option value="">-- Pilih pelanggan --</option>
                        @foreach ($pelanggans as $p)
                            <option value="{{ $p->id }}" {{ old('pelanggan_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_pelanggan ?? $p->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-grid">
                    <div class="form-field">
                        <label class="field-label" for="plat_nomor">Plat nomor</label>
                        <input id="plat_nomor" type="text" name="plat_nomor" value="{{ old('plat_nomor') }}"
                            class="form-input" placeholder="Contoh: N 1234 AB" required>
                    </div>
                    <div class="form-field">
                        <label class="field-label" for="tipe_motor">Tipe motor</label>
                        <input id="tipe_motor" type="text" name="tipe_motor" value="{{ old('tipe_motor') }}"
                            class="form-input" placeholder="Contoh: Honda Vario 150" required>
                    </div>
                </div>

                <div class="form-field">
                    <label class="field-label" for="jadwal_booking">Jadwal booking</label>
                    <input id="jadwal_booking" type="datetime-local" name="jadwal_booking" value="{{ old('jadwal_booking') }}"
                        class="form-input" required>
                </div>

                <div class="form-field">
                    <label class="field-label" for="keluhan">Keluhan kendaraan</label>
                    <textarea id="keluhan" name="keluhan" class="form-textarea" placeholder="Contoh: motor brebet saat digas" required>{{ old('keluhan') }}</textarea>
                </div>

                <div class="form-field">
                    <label class="field-label mb-2">Pilihan Layanan</label>
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @if(isset($services) && $services->count() > 0)
                            @foreach ($services as $layanan)
                                <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 transition hover:bg-slate-100">
                                    <input type="checkbox" name="kategori_servis[]" value="{{ $layanan->nama_service }}" class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm font-medium text-slate-700">{{ $layanan->nama_service }}</span>
                                </label>
                            @endforeach
                        @else
                            <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 transition hover:bg-slate-100">
                                <input type="checkbox" name="kategori_servis[]" value="Servis Rutin" class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm font-medium text-slate-700">Servis Rutin</span>
                            </label>
                        @endif
                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 transition hover:bg-slate-100">
                            <input type="checkbox" id="checkbox-lainnya" name="kategori_servis[]" value="Lainnya" class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-slate-700">Lainnya</span>
                        </label>
                    </div>
                    <div id="input-lainnya-container" class="mt-3 hidden">
                        <input type="text" id="layanan_lainnya" name="layanan_lainnya" value="{{ old('layanan_lainnya') }}" class="form-input" placeholder="Sebutkan layanan lainnya (Contoh: Pasang spion)">
                    </div>
                </div>

                <div class="form-field">
                    <label class="field-label" for="sparepart_diminta">Request Sparepart</label>
                    <select id="sparepart_diminta" name="sparepart_diminta[]" class="form-select select2-multiple" multiple="multiple">
                        @if(isset($spareparts))
                            @foreach ($spareparts as $s)
                                <option value="{{ $s->nama_sparepart }}">
                                    {{ $s->nama_sparepart }} (Stok: {{ $s->stok }})
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <p class="mt-1 text-xs text-slate-500">*Bisa dikosongkan jika tidak ada request spesifik.</p>
                </div>

                <div class="form-grid">
                    <div class="form-field">
                        <label class="field-label" for="mekanik_id">Mekanik</label>
                        <select id="mekanik_id" name="mekanik_id" class="form-select">
                            <option value="">-- Belum ditentukan --</option>
                            @foreach ($mekaniks as $m)
                                <option value="{{ $m->id }}" {{ old('mekanik_id') == $m->id ? 'selected' : '' }}>
                                    {{ $m->nama_mekanik ?? $m->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-field">
                        <label class="field-label" for="status">Status antrean</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="menunggu" {{ old('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="diproses" {{ old('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ old('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('booking.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Simpan Booking</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2-multiple').select2({
                placeholder: "Pilih sparepart...",
                allowClear: true
            });

            $('#checkbox-lainnya').change(function() {
                if(this.checked) {
                    $('#input-lainnya-container').removeClass('hidden');
                } else {
                    $('#input-lainnya-container').addClass('hidden');
                }
            });
        });
    </script>
</x-layout>
