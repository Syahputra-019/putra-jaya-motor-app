@extends('components.app') <!-- Pastikan ini sesuai dengan nama layout utama aplikasi Anda -->

@section('content')
    <div class="container mx-auto max-w-4xl px-4 py-8">
        <h2 class="mb-6 text-2xl font-bold text-gray-800">Riwayat Servis Kendaraan</h2>

        @if ($transaksis->isEmpty())
            <div class="relative rounded border border-blue-400 bg-blue-100 px-4 py-3 text-blue-700 shadow-sm" role="alert">
                <span class="block sm:inline">Belum ada riwayat servis untuk akun Anda. Yuk, jadwalkan servis
                    sekarang!</span>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($transaksis as $transaksi)
                    <div
                        class="overflow-hidden rounded-lg border bg-white shadow-sm transition duration-300 hover:shadow-md">
                        <!-- Header Accordion -->
                        <div class="flex cursor-pointer flex-wrap items-center justify-between border-b bg-gray-50 p-4 transition-colors duration-200 hover:bg-gray-100"
                            onclick="toggleAccordion('trx-{{ $transaksi->id }}')">
                            <div class="mb-2 w-1/2 md:mb-0 md:w-auto">
                                <p class="text-xs font-medium text-gray-500">Tanggal Servis</p>
                                <p class="font-semibold text-gray-800">
                                    {{ \Carbon\Carbon::parse($transaksi->tanggal)->translatedFormat('d F Y') }}</p>
                            </div>
                            <div class="mb-2 w-1/2 md:mb-0 md:w-auto">
                                <p class="text-xs font-medium text-gray-500">Plat Nomor</p>
                                <p class="font-semibold text-gray-800">{{ $transaksi->booking->plat_nomor ?? '-' }}</p>
                            </div>
                            <div class="w-1/2 md:w-auto">
                                <p class="text-xs font-medium text-gray-500">Total Biaya</p>
                                <p class="font-bold text-gray-800">Rp
                                    {{ number_format($transaksi->total_biaya, 0, ',', '.') }}</p>
                            </div>
                            <div class="mt-2 flex w-1/2 items-center justify-end gap-3 md:mt-0 md:w-auto md:justify-start">
                                <span
                                    class="{{ ($transaksi->status_pembayaran ?? $transaksi->status) == 'lunas' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} inline-flex rounded-full px-3 py-1 text-xs font-bold leading-5">
                                    {{ strtoupper($transaksi->status_pembayaran ?? $transaksi->status) }}
                                </span>
                                <svg class="h-6 w-6 transform text-gray-500 transition-transform duration-200"
                                    id="icon-trx-{{ $transaksi->id }}" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Body Accordion (Detail Struk Digital) -->
                        <div id="trx-{{ $transaksi->id }}"
                            class="hidden border-t border-dashed border-gray-300 bg-white p-6">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <!-- Informasi Bengkel & Kendaraan -->
                                <div>
                                    <h4
                                        class="mb-2 border-b pb-1 text-sm font-semibold uppercase tracking-wider text-gray-700">
                                        Informasi Kendaraan</h4>
                                    <ul class="mt-3 space-y-2 text-sm text-gray-600">
                                        <li><span class="font-medium text-gray-800">Kode Transaksi:</span> <span
                                                class="rounded bg-gray-100 px-1 py-0.5 text-xs">{{ $transaksi->kode_transaksi }}</span>
                                        </li>
                                        <li><span class="font-medium text-gray-800">Tipe Motor:</span>
                                            {{ $transaksi->booking->tipe_motor ?? '-' }}</li>
                                        <li><span class="font-medium text-gray-800">Mekanik:</span>
                                            {{ $transaksi->mekanik->nama_mekanik ?? '-' }}</li>
                                        <li><span class="font-medium text-gray-800">Keluhan:</span>
                                            {{ $transaksi->keluhan ?? '-' }}</li>
                                        @if (!empty($transaksi->booking->catatan_mekanik))
                                            <li><span class="font-medium text-gray-800">Catatan Mekanik:</span>
                                                {{ $transaksi->booking->catatan_mekanik }}</li>
                                        @endif
                                        @if (!empty($transaksi->booking->status_konfirmasi))
                                            <li><span class="font-medium text-gray-800">Rekomendasi Part Tambahan:</span>
                                                @if ($transaksi->booking->status_konfirmasi === 'approved')
                                                    <span
                                                        class="rounded border border-green-200 bg-green-50 px-2 py-0.5 text-xs font-semibold text-green-600">Disetujui</span>
                                                @elseif($transaksi->booking->status_konfirmasi === 'rejected')
                                                    <span
                                                        class="rounded border border-red-200 bg-red-50 px-2 py-0.5 text-xs font-semibold text-red-600">Ditolak</span>
                                                @endif
                                            </li>
                                        @endif
                                    </ul>
                                </div>

                                <!-- Rincian Biaya / Nota Digital -->
                                <div class="rounded-md border border-yellow-100 bg-yellow-50 p-4">
                                    <h4
                                        class="mb-2 border-b border-yellow-200 pb-1 text-sm font-semibold uppercase tracking-wider text-gray-700">
                                        Rincian Nota</h4>

                                    <!-- Jasa -->
                                    <div class="mb-3 mt-3">
                                        @if ($transaksi->service)
                                            <div class="mb-1 flex justify-between text-sm text-gray-700">
                                                <span>Jasa: {{ $transaksi->service->nama_service }}</span>
                                                <span>Rp
                                                    {{ number_format($transaksi->service->harga, 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Sparepart -->
                                    @if ($transaksi->detailTransaksis->count() > 0)
                                        <div class="mb-3">
                                            @foreach ($transaksi->detailTransaksis as $detail)
                                                <div class="mb-1 flex justify-between text-sm text-gray-700">
                                                    <span>{{ $detail->sparepart->nama_sparepart }} <span
                                                            class="text-xs text-gray-500">(x{{ $detail->jumlah }})</span></span>
                                                    <span>Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Total Biaya -->
                                    <div
                                        class="mt-4 flex items-center justify-between border-t border-dashed border-yellow-400 pt-3 text-gray-800">
                                        <span class="text-sm font-bold uppercase tracking-wider">Total Tagihan</span>
                                        <span class="text-lg font-extrabold">Rp
                                            {{ number_format($transaksi->total_biaya, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        function toggleAccordion(id) {
            document.getElementById(id).classList.toggle('hidden');
            document.getElementById('icon-' + id).classList.toggle('rotate-180');
        }
    </script>
@endsection
