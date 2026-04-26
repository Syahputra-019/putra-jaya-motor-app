<x-layout>
    <div class="page-shell">
        <div class="page-header">
            <div class="page-header-split">
                <p class="page-kicker">Workshop Overview</p>
                <h1 class="page-title">Dashboard operasional</h1>
                <p class="page-description">
                    Ringkasan performa bengkel untuk periode <span class="font-bold text-[color:var(--brand-navy-800)]">{{ $judulFilter }}</span>,
                    dengan visual yang lebih konsisten dan fokus pada angka penting.
                </p>
            </div>

            <div class="page-actions">
                <form action="{{ route('dashboard') }}" method="GET" class="action-toolbar">
                    <select name="filter" onchange="this.form.submit()" class="form-select min-w-[180px]">
                        <option value="hari_ini" {{ $filter == 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="bulan_ini" {{ $filter == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="tahun_ini" {{ $filter == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
                    </select>
                </form>
                <a href="{{ route('dashboard.cetak', ['filter' => $filter]) }}" target="_blank" class="btn-secondary">Cetak PDF</a>
                <a href="{{ route('dashboard.excel', ['filter' => $filter]) }}" class="btn-accent">Export Excel</a>
            </div>
        </div>

        @if ($menungguAcc > 0)
            <div class="alert alert-warning">
                <div class="font-black">!</div>
                <div class="flex flex-1 flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <div class="font-bold">Ada {{ $menungguAcc }} transaksi menunggu konfirmasi pembayaran.</div>
                        <div class="mt-1 text-sm">Pelanggan sudah mengirim bukti transfer manual dan menunggu pengecekan admin.</div>
                    </div>
                    <a href="{{ route('transaksi.index') }}" class="btn-accent">Cek Transaksi</a>
                </div>
            </div>
        @endif

        <div class="metric-grid">
            <div class="metric-card">
                <div class="metric-label">Total Pendapatan</div>
                <div class="metric-value">Rp {{ number_format($pendapatanTotal, 0, ',', '.') }}</div>
                <div class="metric-caption">Pendapatan yang sudah berstatus lunas pada periode terpilih.</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Pendapatan Jasa</div>
                <div class="metric-value">Rp {{ number_format($pendapatanJasa, 0, ',', '.') }}</div>
                <div class="metric-caption">Akumulasi biaya jasa setelah dikurangi pendapatan sparepart.</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Pendapatan Part</div>
                <div class="metric-value">Rp {{ number_format($pendapatanSparepart, 0, ',', '.') }}</div>
                <div class="metric-caption">Nilai total sparepart yang terjual pada transaksi lunas.</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Servis Selesai</div>
                <div class="metric-value">{{ $transaksiHariIni }}</div>
                <div class="metric-caption">Jumlah transaksi lunas pada filter waktu yang sedang aktif.</div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-[1.15fr_0.85fr]">
            <div class="surface-card">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="section-title">Grafik pendapatan</h2>
                        <p class="section-subtitle">Performa 6 bulan terakhir dengan palet warna biru tua dan kuning.</p>
                    </div>
                </div>
                <div class="mt-6 h-[320px] w-full">
                    <canvas id="chartPendapatan"></canvas>
                </div>
            </div>

            <div class="surface-card">
                <div>
                    <h2 class="section-title">Sparepart terlaris</h2>
                    <p class="section-subtitle">Top 5 sparepart dengan penjualan tertinggi.</p>
                </div>
                <div class="mt-6 h-[320px] w-full">
                    <canvas id="chartTerlaris"></canvas>
                </div>
            </div>
        </div>

        <div class="table-card">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                <div>
                    <h2 class="section-title">Stok menipis</h2>
                    <p class="section-subtitle">Daftar sparepart yang perlu segera direstock.</p>
                </div>
                <span class="badge {{ $jumlahStokMenipis > 0 ? 'badge-danger' : 'badge-success' }}">
                    {{ $jumlahStokMenipis }} item
                </span>
            </div>

            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama Sparepart</th>
                            <th class="text-center">Sisa Stok</th>
                            <th class="text-right">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stokMenipis as $item)
                            <tr>
                                <td class="font-semibold text-slate-900">{{ $item->nama_sparepart }}</td>
                                <td class="text-center">
                                    <span class="badge badge-danger">{{ $item->stok }}</span>
                                </td>
                                <td class="text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    <div class="empty-state my-4">
                                        <div class="empty-state-icon">ST</div>
                                        <h3 class="text-xl font-bold text-slate-950">Stok aman</h3>
                                        <p class="max-w-lg text-sm leading-6 text-slate-500">Belum ada sparepart yang berada di bawah batas stok minimum.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const chartTextColor = '#334155';
        const chartGridColor = 'rgba(15, 23, 42, 0.08)';

        new Chart(document.getElementById('chartPendapatan'), {
            type: 'line',
            data: {
                labels: {!! json_encode($pendapatanBulanan->pluck('bulan')) !!},
                datasets: [{
                    label: 'Pendapatan',
                    data: {!! json_encode($pendapatanBulanan->pluck('total')) !!},
                    borderColor: '#13305a',
                    backgroundColor: 'rgba(246, 197, 75, 0.22)',
                    fill: true,
                    borderWidth: 3,
                    tension: 0.35,
                    pointRadius: 4,
                    pointBackgroundColor: '#f6c54b',
                    pointBorderColor: '#13305a'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: chartTextColor
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: chartTextColor },
                        grid: { color: chartGridColor }
                    },
                    y: {
                        ticks: { color: chartTextColor },
                        grid: { color: chartGridColor }
                    }
                }
            }
        });

        new Chart(document.getElementById('chartTerlaris'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($sparepartTerlaris->pluck('nama_sparepart')) !!},
                datasets: [{
                    data: {!! json_encode($sparepartTerlaris->pluck('total_terjual')) !!},
                    backgroundColor: ['#13305a', '#1b447d', '#f6c54b', '#ffd768', '#94a3b8'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: chartTextColor,
                            padding: 18,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    </script>
</x-layout>
