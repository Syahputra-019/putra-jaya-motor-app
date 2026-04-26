<x-layout>
    <div class="page-shell-sm">
        <div class="page-header">
            <div class="page-header-split">
                <p class="page-kicker">Workshop Team</p>
                <h1 class="page-title">Edit mekanik</h1>
                <p class="page-description">Perbarui profil mekanik dengan form yang tetap seragam dan mudah dipindai.</p>
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
            <form action="{{ route('mekanik.update', $mekanik->id) }}" method="POST" class="form-shell">
                @csrf
                @method('PUT')

                <div class="form-field">
                    <label class="field-label" for="nama_mekanik">Nama mekanik</label>
                    <input id="nama_mekanik" type="text" name="nama_mekanik"
                        value="{{ old('nama_mekanik', $mekanik->nama_mekanik) }}" class="form-input" required>
                </div>

                <div class="form-field">
                    <label class="field-label" for="no_telp">No. telepon / WhatsApp</label>
                    <input id="no_telp" type="text" name="no_telp" value="{{ old('no_telp', $mekanik->no_telp) }}"
                        class="form-input" required>
                </div>

                <div class="form-field">
                    <label class="field-label" for="spesialisasi">Spesialisasi</label>
                    <select id="spesialisasi" name="spesialisasi" class="form-select" required>
                        <option value="Servis Rutin" {{ old('spesialisasi', $mekanik->spesialisasi) == 'Servis Rutin' ? 'selected' : '' }}>Servis Rutin</option>
                        <option value="Mesin" {{ old('spesialisasi', $mekanik->spesialisasi) == 'Mesin' ? 'selected' : '' }}>Turun Mesin</option>
                        <option value="Kelistrikan" {{ old('spesialisasi', $mekanik->spesialisasi) == 'Kelistrikan' ? 'selected' : '' }}>Kelistrikan</option>
                        <option value="Modifikasi" {{ old('spesialisasi', $mekanik->spesialisasi) == 'Modifikasi' ? 'selected' : '' }}>Modifikasi</option>
                    </select>
                </div>

                <div class="form-actions">
                    <a href="{{ route('mekanik.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-accent">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
