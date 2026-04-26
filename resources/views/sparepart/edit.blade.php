<x-layout>
    <div class="page-shell-sm">
        <div class="page-header">
            <div class="page-header-split">
                <p class="page-kicker">Inventori</p>
                <h1 class="page-title">Edit sparepart</h1>
                <p class="page-description">Perbarui harga dan stok sparepart dengan struktur form yang sama seperti modul lain.</p>
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
            <form action="{{ route('sparepart.update', $sparepart->id) }}" method="POST" class="form-shell">
                @csrf
                @method('PUT')

                <div class="form-field">
                    <label class="field-label" for="nama_sparepart">Nama sparepart</label>
                    <input id="nama_sparepart" type="text" name="nama_sparepart"
                        value="{{ old('nama_sparepart', $sparepart->nama_sparepart) }}" class="form-input" required>
                </div>

                <div class="form-grid">
                    <div class="form-field">
                        <label class="field-label" for="harga">Harga</label>
                        <input id="harga" type="number" name="harga" value="{{ old('harga', $sparepart->harga) }}"
                            class="form-input" required>
                    </div>
                    <div class="form-field">
                        <label class="field-label" for="stok">Stok</label>
                        <input id="stok" type="number" name="stok" value="{{ old('stok', $sparepart->stok) }}"
                            class="form-input" required>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('sparepart.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-accent">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
