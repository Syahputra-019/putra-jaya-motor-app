<x-layout>
    <div class="page-shell-sm">
        <div class="page-header">
            <div class="page-header-split">
                <p class="page-kicker">Queue Management</p>
                <h1 class="page-title">Edit booking</h1>
                <p class="page-description">Perbarui antrean booking dengan pola form yang tetap seragam dan nyaman digunakan.</p>
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
            <form action="{{ route('booking.update', $booking->id) }}" method="POST" class="form-shell">
                @csrf
                @method('PUT')

                <div class="form-field">
                    <label class="field-label" for="pelanggan_id">Pilih pelanggan</label>
                    <select id="pelanggan_id" name="pelanggan_id" class="form-select" required>
                        @foreach ($pelanggans as $p)
                            <option value="{{ $p->id }}" {{ $booking->pelanggan_id == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_pelanggan ?? $p->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-grid">
                    <div class="form-field">
                        <label class="field-label" for="plat_nomor">Plat nomor</label>
                        <input id="plat_nomor" type="text" name="plat_nomor" value="{{ old('plat_nomor', $booking->plat_nomor) }}"
                            class="form-input" required>
                    </div>
                    <div class="form-field">
                        <label class="field-label" for="tipe_motor">Tipe motor</label>
                        <input id="tipe_motor" type="text" name="tipe_motor" value="{{ old('tipe_motor', $booking->tipe_motor) }}"
                            class="form-input" required>
                    </div>
                </div>

                <div class="form-field">
                    <label class="field-label" for="jadwal_booking">Jadwal booking</label>
                    <input id="jadwal_booking" type="datetime-local" name="jadwal_booking"
                        value="{{ \Carbon\Carbon::parse(old('jadwal_booking', $booking->jadwal_booking))->format('Y-m-d\TH:i') }}"
                        class="form-input" required>
                </div>

                <div class="form-field">
                    <label class="field-label" for="keluhan">Keluhan kendaraan</label>
                    <textarea id="keluhan" name="keluhan" class="form-textarea" required>{{ old('keluhan', $booking->keluhan) }}</textarea>
                </div>

                <div class="form-grid">
                    <div class="form-field">
                        <label class="field-label" for="mekanik_id">Mekanik pengerja</label>
                        <select id="mekanik_id" name="mekanik_id" class="form-select">
                            <option value="">-- Belum ditentukan --</option>
                            @foreach ($mekaniks as $m)
                                <option value="{{ $m->id }}" {{ $booking->mekanik_id == $m->id ? 'selected' : '' }}>
                                    {{ $m->nama_mekanik ?? $m->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-field">
                        <label class="field-label" for="status">Status antrean</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="menunggu" {{ $booking->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="diproses" {{ $booking->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ $booking->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ $booking->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('booking.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-accent">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
