<x-layout>
    <div class="page-shell-sm">
        <div class="page-header">
            <div class="page-header-split">
                <p class="page-kicker">Queue Management</p>
                <h1 class="page-title">Tambah booking</h1>
                <p class="page-description">Buat antrean booking baru dengan layout form yang konsisten untuk admin maupun pelanggan.</p>
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
</x-layout>
