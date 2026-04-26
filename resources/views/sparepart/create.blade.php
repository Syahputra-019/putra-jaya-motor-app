<x-layout>
    <div class="page-shell-sm">
        <div class="page-header">
            <div class="page-header-split">
                <p class="page-kicker">Inventori</p>
                <h1 class="page-title">Tambah sparepart</h1>
                <p class="page-description">Masukkan detail sparepart baru ke sistem dengan tampilan form yang lebih rapi dan konsisten.</p>
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
            <form action="{{ route('sparepart.store') }}" method="POST" class="form-shell">
                @csrf

                <div class="form-field">
                    <label class="field-label" for="nama_sparepart">Nama sparepart</label>
                    <input id="nama_sparepart" type="text" name="nama_sparepart" value="{{ old('nama_sparepart') }}"
                        class="form-input" placeholder="Contoh: Kampas Rem Depan" required>
                </div>

                <div class="form-grid">
                    <div class="form-field">
                        <label class="field-label" for="harga">Harga</label>
                        <input id="harga" type="number" name="harga" value="{{ old('harga') }}" class="form-input"
                            placeholder="Contoh: 50000" required>
                    </div>
                    <div class="form-field">
                        <label class="field-label" for="stok">Stok awal</label>
                        <input id="stok" type="number" name="stok" value="{{ old('stok') }}" class="form-input"
                            placeholder="Contoh: 10" required>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('sparepart.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
