@extends('components.app') <!-- Pastikan ini sesuai dengan nama layout utama aplikasi Anda -->

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Riwayat Servis Kendaraan</h2>

    @if($transaksis->isEmpty())
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative shadow-sm" role="alert">
            <span class="block sm:inline">Belum ada riwayat servis untuk akun Anda. Yuk, jadwalkan servis sekarang!</span>
        </div>
    @else
        <div class="space-y-4">
            @foreach($transaksis as $transaksi)
                <div class="bg-white border rounded-lg shadow-sm overflow-hidden transition duration-300 hover:shadow-md">
                    <!-- Header Accordion -->
                    <div class="p-4 bg-gray-50 border-b flex flex-wrap justify-between items-center cursor-pointer hover:bg-gray-100 transition-colors duration-200" onclick="toggleAccordion('trx-{{ $transaksi->id }}')">
                        <div class="w-1/2 md:w-auto mb-2 md:mb-0">
                            <p class="text-xs text-gray-500 font-medium">Tanggal Servis</p>
                            <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($transaksi->tanggal)->translatedFormat('d F Y') }}</p>
                        </div>
                        <div class="w-1/2 md:w-auto mb-2 md:mb-0">
                            <p class="text-xs text-gray-500 font-medium">Plat Nomor</p>
                            <p class="font-semibold text-gray-800">{{ $transaksi->booking->plat_nomor ?? '-' }}</p>
                        </div>
                        <div class="w-1/2 md:w-auto">
                            <p class="text-xs text-gray-500 font-medium">Total Biaya</p>
                            <p class="font-bold text-gray-800">Rp {{ number_format($transaksi->total_biaya, 0, ',', '.') }}</p>
                        </div>
                        <div class="w-1/2 md:w-auto flex items-center gap-3 justify-end md:justify-start mt-2 md:mt-0">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full 
                                {{ ($transaksi->status_pembayaran ?? $transaksi->status) == 'lunas' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ strtoupper($transaksi->status_pembayaran ?? $transaksi->status) }}
                            </span>
                            <svg class="w-6 h-6 text-gray-500 transform transition-transform duration-200" id="icon-trx-{{ $transaksi->id }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>

                    <!-- Body Accordion (Detail Struk Digital) -->
                    <div id="trx-{{ $transaksi->id }}" class="hidden p-6 bg-white border-t border-dashed border-gray-300">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Informasi Bengkel & Kendaraan -->
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-2 border-b pb-1 uppercase text-sm tracking-wider">Informasi Kendaraan</h4>
                                <ul class="text-sm text-gray-600 space-y-2 mt-3">
                                    <li><span class="font-medium text-gray-800">Kode Transaksi:</span> <span class="bg-gray-100 px-1 py-0.5 rounded text-xs">{{ $transaksi->kode_transaksi }}</span></li>
                                    <li><span class="font-medium text-gray-800">Tipe Motor:</span> {{ $transaksi->booking->tipe_motor ?? '-' }}</li>
                                    <li><span class="font-medium text-gray-800">Mekanik:</span> {{ $transaksi->mekanik->nama_mekanik ?? '-' }}</li>
                                    <li><span class="font-medium text-gray-800">Keluhan:</span> {{ $transaksi->keluhan ?? '-' }}</li>
                                </ul>
                            </div>
                            
                            <!-- Rincian Biaya / Nota Digital -->
                            <div class="bg-yellow-50 p-4 rounded-md border border-yellow-100">
                                <h4 class="font-semibold text-gray-700 mb-2 border-b border-yellow-200 pb-1 uppercase text-sm tracking-wider">Rincian Nota</h4>
                                
                                <!-- Jasa -->
                                <div class="mb-3 mt-3">
                                    @if($transaksi->service)
                                    <div class="flex justify-between text-sm text-gray-700 mb-1">
                                        <span>Jasa: {{ $transaksi->service->nama_service }}</span>
                                        <span>Rp {{ number_format($transaksi->service->harga, 0, ',', '.') }}</span>
                                    </div>
                                    @endif
                                </div>

                                <!-- Sparepart -->
                                @if($transaksi->detailTransaksis->count() > 0)
                                <div class="mb-3">
                                    @foreach($transaksi->detailTransaksis as $detail)
                                    <div class="flex justify-between text-sm text-gray-700 mb-1">
                                        <span>{{ $detail->sparepart->nama_sparepart }} <span class="text-xs text-gray-500">(x{{ $detail->jumlah }})</span></span>
                                        <span>Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</span>
                                    </div>
                                    @endforeach
                                </div>
                                @endif

                                <!-- Total Biaya -->
                                <div class="mt-4 border-t border-dashed border-yellow-400 pt-3 flex justify-between items-center text-gray-800">
                                    <span class="font-bold uppercase tracking-wider text-sm">Total Tagihan</span>
                                    <span class="font-extrabold text-lg">Rp {{ number_format($transaksi->total_biaya, 0, ',', '.') }}</span>
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
