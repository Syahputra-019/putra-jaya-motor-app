<x-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Tambah Sparepart Baru</h1>
        <p class="text-slate-500 text-sm">Masukkan detail suku cadang ke dalam sistem.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 max-w-2xl">
        
        <form action="{{ route('sparepart.store') }}" method="POST">
            @csrf 

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Nama Sparepart</label>
                <input type="text" name="name" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="Contoh: Kampas Rem Depan" required>
            </div>

            <div class="mb-4 grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Harga (Rp)</label>
                    <input type="number" name="price" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="Contoh: 50000" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Stok Awal</label>
                    <input type="number" name="stock" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="Contoh: 10" required>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('sparepart.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition font-medium">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">Simpan Data</button>
            </div>
        </form>
        
    </div>
</x-layout>