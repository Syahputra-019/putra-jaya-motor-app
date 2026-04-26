<x-layout>
    <div class="page-shell">
        <div class="page-header">
            <div class="page-header-split">
                <p class="page-kicker">Revenue Report</p>
                <h1 class="page-title">Laporan pendapatan</h1>
                <p class="page-description">Filter performa transaksi berdasarkan rentang tanggal dengan kartu ringkasan dan tabel yang seragam.</p>
            </div>
        </div>

        <div class="surface-card">
            <form action="{{ route('laporan.index') }}" method="GET" class="form-grid-3">
                <div class="form-field">
                    <label class="field-label" for="start_date">Dari tanggal</label>
                    <input id="start_date" type="date" name="start_date" value="{{ $startDate }}" class="form-input">
                </div>
                <div class="form-field">
                    <label class="field-label" for="end_date">Sampai tanggal</label>
                    <input id="end_date" type="date" name="end_date" value="{{ $endDate }}" class="form-input">
                </div>
                <div class="form-field justify-end">
                    <label class="field-label opacity-0">Aksi</label>
                    <button type="submit" class="btn-primary w-full md:w-auto">Terapkan Filter</button>
                </div>
            </form>
        </div>

        <div class="metric-grid md:grid-cols-2 xl:grid-cols-2">
            <div class="metric-card">
                <div class="metric-label">Total Pendapatan</div>
                <div class="metric-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                <div class="metric-caption">Akumulasi pendapatan dari rentang tanggal terpilih.</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Total Transaksi</div>
                <div class="metric-value">{{ $totalTransaksi }}</div>
                <div class="metric-caption">Jumlah nota transaksi yang tercatat pada periode ini.</div>
            </div>
        </div>

        <div class="table-card">
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kode Nota</th>
                            <th>Pelanggan</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $t)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($t->tanggal)->format('d M Y') }}</td>
                                <td class="font-semibold text-[color:var(--brand-navy-800)]">{{ $t->kode_transaksi }}</td>
                                <td>{{ $t->pelanggan->nama_pelanggan ?? 'Umum' }}</td>
                                <td class="text-right font-semibold text-slate-900">Rp {{ number_format($t->total_biaya, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state my-4">
                                        <div class="empty-state-icon">LP</div>
                                        <h3 class="text-xl font-bold text-slate-950">Tidak ada data transaksi</h3>
                                        <p class="max-w-lg text-sm leading-6 text-slate-500">Ubah rentang tanggal atau tambahkan transaksi baru untuk melihat laporan pendapatan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>
