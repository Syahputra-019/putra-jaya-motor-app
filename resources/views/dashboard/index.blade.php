<x-layout>
    <div class="mb-6 flex flex-col justify-between sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
            <p class="text-sm text-slate-500">Ringkasan aktivitas dan pendapatan bengkel <span
                    class="font-bold text-blue-600">({{ $judulFilter }})</span>.</p>
        </div>

        <div class="mt-4 sm:mt-0">
            <form action="{{ route('dashboard') }}" method="GET"
                class="flex items-center space-x-3 rounded-lg border border-slate-200 bg-white px-3 py-2 shadow-sm">
                <label for="filter" class="text-sm font-semibold text-slate-600">📅 Filter Waktu:</label>
                <select name="filter" id="filter" onchange="this.form.submit()"
                    class="cursor-pointer rounded border-0 bg-slate-50 text-sm font-bold text-slate-700 focus:ring-0">
                    <option value="hari_ini" {{ $filter == 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="bulan_ini" {{ $filter == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="tahun_ini" {{ $filter == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
                </select>
            </form>
            <div class="mt-4 flex items-center space-x-3 sm:mt-0">

                <div class="relative z-50 inline-block text-left">
                    <button type="button" onclick="document.getElementById('dropdownCetak').classList.toggle('hidden')"
                        class="flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none">
                        🖨️ Cetak Laporan
                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <div id="dropdownCetak"
                        class="absolute right-0 mt-2 hidden w-40 origin-top-right overflow-hidden rounded-md bg-white shadow-lg ring-1 ring-slate-200 focus:outline-none">
                        <div class="py-1">
                            <a href="{{ route('dashboard.cetak', ['filter' => $filter]) }}" target="_blank"
                                class="block px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-red-600">
                                📄 Cetak PDF
                            </a>
                            <a href="{{ route('dashboard.excel', ['filter' => $filter]) }}"
                                class="block border-t border-slate-100 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-emerald-600">
                                📊 Export Excel
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @if ($menungguAcc > 0)
        <div
            class="mb-6 flex items-center justify-between rounded-xl border-l-4 border-yellow-500 bg-yellow-50 p-4 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center">
                <span class="mr-4 text-3xl">⏳</span>
                <div>
                    <h3 class="text-lg font-bold text-yellow-800">Ada {{ $menungguAcc }} Transaksi Menunggu
                        Konfirmasi!
                    </h3>
                    <p class="text-sm text-yellow-700">Pelanggan sudah upload bukti transfer manual. Silakan cek dan ACC
                        agar stok terpotong.</p>
                </div>
            </div>
            <a href="{{ route('transaksi.index') }}"
                class="rounded bg-yellow-500 px-5 py-2.5 text-sm font-bold text-white shadow transition hover:bg-yellow-600">
                Cek Transaksi
            </a>
        </div>
    @endif

    <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-4">
        <div class="rounded-xl border-l-4 border-blue-500 bg-white p-6 shadow-sm transition hover:shadow-md">
            <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Pendapatan</h3>
            <p class="mt-2 text-2xl font-black text-slate-800">Rp {{ number_format($pendapatanTotal, 0, ',', '.') }}
            </p>
        </div>

        <div class="rounded-xl border-l-4 border-indigo-500 bg-white p-6 shadow-sm transition hover:shadow-md">
            <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500">Pendapatan Jasa</h3>
            <p class="mt-2 text-2xl font-black text-slate-800">Rp {{ number_format($pendapatanJasa, 0, ',', '.') }}</p>
        </div>

        <div class="rounded-xl border-l-4 border-purple-500 bg-white p-6 shadow-sm transition hover:shadow-md">
            <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500">Pendapatan Part</h3>
            <p class="mt-2 text-2xl font-black text-slate-800">Rp
                {{ number_format($pendapatanSparepart, 0, ',', '.') }}
            </p>
        </div>

        <div class="rounded-xl border-l-4 border-emerald-500 bg-white p-6 shadow-sm transition hover:shadow-md">
            <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500">Servis Selesai</h3>
            <p class="mt-2 text-2xl font-black text-slate-800">{{ $transaksiHariIni }} <span
                    class="text-sm font-normal text-slate-500">Motor</span></p>
        </div>
    </div>

    @if ($jumlahStokMenipis > 0)
        <div class="mb-6 overflow-hidden rounded-xl border border-red-100 bg-white shadow-sm">
            <div class="border-b border-red-100 bg-red-50 px-6 py-4">
                <h2 class="flex items-center text-lg font-bold text-red-700">
                    ⚠️ Segera Restock Sparepart Berikut!
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-700">
                        <tr>
                            <th class="px-6 py-3 font-semibold">Nama Sparepart</th>
                            <th class="px-6 py-3 text-center font-semibold">Sisa Stok</th>
                            <th class="px-6 py-3 text-right font-semibold">Harga Jual</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($stokMenipis as $item)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-3 font-medium text-slate-800">{{ $item->nama_sparepart }}</td>
                                <td class="px-6 py-3 text-center">
                                    <span class="rounded-full bg-red-100 px-2 py-1 text-xs font-bold text-red-700">
                                        {{ $item->stok }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-12">
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm lg:col-span-6">
            <div class="border-b border-slate-100 bg-slate-50 px-6 py-4">
                <h3 class="font-bold text-slate-700">📈 Grafik Pendapatan (6 Bulan Terakhir)</h3>
            </div>
            <div class="relative p-6 w-full" style="height: 350px;">
                <canvas id="chartPendapatan"></canvas>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm lg:col-span-6">
            <div class="border-b border-slate-100 bg-slate-50 px-6 py-4">
                <h3 class="font-bold text-slate-700">🍩 5 Sparepart Terlaris</h3>
            </div>
            <div class="w-full p-4">
                <div class="relative w-full" style="height: 300px;">
                    <canvas id="chartTerlaris"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('click', function(e) {
            const dropdown = document.getElementById('dropdownCetak');
            const button = dropdown.previousElementSibling;

            if (!button.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>

    <script>
        // --- GRAFIK PENDAPATAN ---
        const ctxPendapatan = document.getElementById('chartPendapatan');
        new Chart(ctxPendapatan, {
            type: 'line',
            data: {
                labels: {!! json_encode($pendapatanBulanan->pluck('bulan')) !!},
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: {!! json_encode($pendapatanBulanan->pluck('total')) !!},
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1,
                    fill: true,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)'
                }]
            }
        });

        // --- GRAFIK TERLARIS ---
        const ctxTerlaris = document.getElementById('chartTerlaris');
        new Chart(ctxTerlaris, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($sparepartTerlaris->pluck('nama_sparepart')) !!},
                datasets: [{
                    data: {!! json_encode($sparepartTerlaris->pluck('total_terjual')) !!},
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'
                    ]
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: 10
                },
                plugins: {
                    legend: { 
                        position: 'right', // 🔥 INI KUNCI BUAT MINDAHIN KE KANAN
                        labels: {
                            boxWidth: 15, // Biar kotak warnanya pas, gak kebesaran
                            padding: 15   // Biar jarak antar tulisan lega
                        }
                    } 
                }
            }
        });
    </script>
</x-layout>
