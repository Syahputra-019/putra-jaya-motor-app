<x-layout>
    <div class="page-shell-sm">
        <div class="page-header">
            <div class="page-header-split">
                <p class="page-kicker">Customer Data</p>
                <h1 class="page-title">Edit pelanggan</h1>
                <p class="page-description">Perbarui informasi pelanggan tanpa mengubah ritme dan struktur tampilan form.</p>
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
            <form action="{{ route('pelanggan.update', $pelanggan->id) }}" method="POST" class="form-shell">
                @csrf
                @method('PUT')

                <div class="form-field">
                    <label class="field-label" for="nama_pelanggan">Nama pelanggan</label>
                    <input id="nama_pelanggan" type="text" name="nama_pelanggan"
                        value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan) }}" class="form-input" required>
                </div>

                <div class="form-field">
                    <label class="field-label" for="no_telp">No. telepon / WhatsApp</label>
                    <input id="no_telp" type="text" name="no_telp" value="{{ old('no_telp', $pelanggan->no_telp) }}"
                        class="form-input" required>
                </div>

                <div class="form-field">
                    <label class="field-label" for="alamat">Alamat lengkap</label>
                    <textarea id="alamat" name="alamat" class="form-textarea">{{ old('alamat', $pelanggan->alamat) }}</textarea>
                </div>

                <div class="form-actions">
                    <a href="{{ route('pelanggan.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-accent">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
