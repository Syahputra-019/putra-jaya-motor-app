<x-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Edit Data Sparepart</h1>
        <p class="text-slate-500 text-sm">Perbarui informasi harga atau stok suku cadang.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 max-w-2xl">
        
        <form action="{{ route('sparepart.update', $sparepart->id) }}" method="POST">
            @csrf 
            @method('PUT') <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Nama Sparepart</label>
                <input type="text" name="nama_sparepart" value="{{ $sparepart->nama_sparepart }}" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-amber-500 focus:border-amber-500 outline-none" required>
            </div>

            <div class="mb-4 grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Harga (Rp)</label>
                    <input type="number" name="harga" value="{{ $sparepart->harga }}" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-amber-500 focus:border-amber-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Stok Saat Ini</label>
                    <input type="number" name="stok" value="{{ $sparepart->stok }}" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-amber-500 focus:border-amber-500 outline-none" required>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('sparepart.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition font-medium">Batal</a>
                <button type="submit" class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition font-medium">Update Data</button>
            </div>
        </form>
        
    </div>
</x-layout>