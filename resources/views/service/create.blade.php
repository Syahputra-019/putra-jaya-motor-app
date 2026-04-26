<x-layout>
    <div class="page-shell-sm">
        <div class="page-header">
            <div class="page-header-split">
                <p class="page-kicker">Layanan</p>
                <h1 class="page-title">Tambah jasa servis</h1>
                <p class="page-description">Tambahkan layanan baru dengan struktur form yang seragam dan mudah dibaca.</p>
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
            <form action="{{ route('service.store') }}" method="POST" class="form-shell">
                @csrf

                <div class="form-field">
                    <label class="field-label" for="nama_service">Nama jasa servis</label>
                    <input id="nama_service" type="text" name="nama_service" value="{{ old('nama_service') }}"
                        class="form-input" placeholder="Contoh: Ganti Oli, Servis Injeksi" required>
                </div>

                <div class="form-field">
                    <label class="field-label" for="harga">Harga</label>
                    <input id="harga" type="number" name="harga" value="{{ old('harga') }}" class="form-input"
                        placeholder="Contoh: 35000" required min="0">
                </div>

                <div class="form-actions">
                    <a href="{{ route('service.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
