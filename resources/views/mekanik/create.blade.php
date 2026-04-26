<x-layout>
    <div class="page-shell-sm">
        <div class="page-header">
            <div class="page-header-split">
                <p class="page-kicker">Workshop Team</p>
                <h1 class="page-title">Tambah mekanik</h1>
                <p class="page-description">Masukkan profil mekanik baru dengan tampilan form yang konsisten di seluruh sistem.</p>
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
            <form action="{{ route('mekanik.store') }}" method="POST" class="form-shell">
                @csrf

                <div class="form-field">
                    <label class="field-label" for="nama_mekanik">Nama mekanik</label>
                    <input id="nama_mekanik" type="text" name="nama_mekanik" value="{{ old('nama_mekanik') }}"
                        class="form-input" placeholder="Masukkan nama mekanik" required>
                </div>

                <div class="form-field">
                    <label class="field-label" for="no_telp">No. telepon / WhatsApp</label>
                    <input id="no_telp" type="text" name="no_telp" value="{{ old('no_telp') }}" class="form-input"
                        placeholder="Contoh: 081234567890" required>
                </div>

                <div class="form-field">
                    <label class="field-label" for="spesialisasi">Spesialisasi</label>
                    <select id="spesialisasi" name="spesialisasi" class="form-select" required>
                        <option value="">-- Pilih spesialisasi --</option>
                        <option value="Servis Rutin" {{ old('spesialisasi') == 'Servis Rutin' ? 'selected' : '' }}>Servis Rutin</option>
                        <option value="Mesin" {{ old('spesialisasi') == 'Mesin' ? 'selected' : '' }}>Turun Mesin</option>
                        <option value="Kelistrikan" {{ old('spesialisasi') == 'Kelistrikan' ? 'selected' : '' }}>Kelistrikan</option>
                        <option value="Modifikasi" {{ old('spesialisasi') == 'Modifikasi' ? 'selected' : '' }}>Modifikasi</option>
                    </select>
                </div>

                <div class="form-actions">
                    <a href="{{ route('mekanik.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
