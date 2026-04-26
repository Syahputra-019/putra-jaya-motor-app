<x-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Laporan Pendapatan Bengkel</h1>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border mb-6">
        <form action="{{ route('laporan.index') }}" method="GET" class="flex gap-4 items-end">
            <div>
                <label class="block text-sm font-medium mb-1">Dari</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="border rounded-md px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Sampai</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="border rounded-md px-3 py-2">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Filter</button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-emerald-500">
            <p class="text-sm text-slate-500 font-semibold uppercase">Total Pendapatan</p>
            <h2 class="text-3xl font-black text-slate-800">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
            <p class="text-sm text-slate-500 font-semibold uppercase">Total Transaksi</p>
            <h2 class="text-3xl font-black text-slate-800">{{ $totalTransaksi }} Nota</h2>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b">
                <tr>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Nota</th>
                    <th class="px-6 py-4">Pelanggan</th>
                    <th class="px-6 py-4 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($transaksis as $t)
                <tr>
                    <td class="px-6 py-4">{{ $t->tanggal }}</td>
                    <td class="px-6 py-4 font-mono text-blue-600">{{ $t->kode_transaksi }}</td>
                    <td class="px-6 py-4">{{ $t->pelanggan->nama_pelanggan ?? 'Umum' }}</td>
                    <td class="px-6 py-4 text-right font-bold">Rp {{ number_format($t->total_biaya, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-slate-500">Tidak ada data transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layout>