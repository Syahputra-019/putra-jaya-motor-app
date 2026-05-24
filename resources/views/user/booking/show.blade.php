@extends('components.app')

@section('title', 'Detail Booking')

@section('content')
<div class="container mx-auto max-w-4xl px-4 py-12">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Detail Booking</h1>
            <p class="mt-2 text-slate-500">Dibuat pada {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y, H:i') }}</p>
        </div>
        <a href="{{ route('booking.mine', ['id' => $booking->id]) }}" class="btn-secondary">Kembali</a>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        {{-- Info Pelanggan & Kendaraan --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-bold text-slate-800">Informasi Pelanggan & Kendaraan</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500">Nama Pelanggan</span>
                    <span class="font-medium text-slate-900">{{ $booking->pelanggan->nama_pelanggan ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500">No. Telepon / WA</span>
                    <span class="font-medium text-slate-900">{{ $booking->pelanggan->no_telp ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500">Tipe / Merk Motor</span>
                    <span class="font-medium text-slate-900">{{ $booking->tipe_motor }}</span>
                </div>
                <div class="flex justify-between pb-2">
                    <span class="text-slate-500">Plat Nomor</span>
                    <span class="font-bold text-slate-900">{{ $booking->plat_nomor }}</span>
                </div>
            </div>
        </div>

        {{-- Info Jadwal & Status --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-bold text-slate-800">Jadwal & Status</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500">Jadwal Servis</span>
                    <span class="font-medium text-slate-900">{{ \Carbon\Carbon::parse($booking->jadwal_booking)->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500">Status Servis</span>
                    <span class="font-medium uppercase {{ in_array($booking->status, ['dibatalkan']) ? 'text-red-600' : 'text-blue-600' }}">{{ $booking->status }}</span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500">Status Pembayaran</span>
                    <span class="font-medium uppercase {{ $booking->status_pembayaran === 'lunas' ? 'text-green-600' : 'text-amber-600' }}">{{ $booking->status_pembayaran }}</span>
                </div>
                <div class="flex justify-between pb-2">
                    <span class="text-slate-500">Mekanik</span>
                    <span class="font-medium text-slate-900">{{ $booking->mekanik->nama_mekanik ?? 'Belum Ditentukan / Bebas' }}</span>
                </div>
            </div>
        </div>

        {{-- Detail Layanan yang Dipilih --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:col-span-2">
            <h3 class="mb-4 text-lg font-bold text-slate-800">Detail Layanan & Keluhan</h3>
            
            <div class="mb-4">
                <span class="mb-1 block text-sm text-slate-500">Keluhan Kendaraan:</span>
                <p class="rounded-lg bg-slate-50 p-3 text-sm text-slate-800">{{ $booking->keluhan ?: 'Tidak ada keluhan yang dituliskan.' }}</p>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <span class="mb-2 block text-sm text-slate-500">Kategori Servis yang Dipilih:</span>
                    @php
                        $kategori = is_string($booking->kategori_servis) ? json_decode($booking->kategori_servis, true) : $booking->kategori_servis;
                    @endphp
                    @if(is_array($kategori) && count($kategori) > 0)
                        <ul class="list-inside list-disc space-y-1 text-sm text-slate-800 pl-4">
                            @foreach($kategori as $servis)
                                <li>{{ $servis }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-slate-500 italic">Tidak ada kategori servis yang dipilih.</p>
                    @endif
                </div>

                <div>
                    <span class="mb-2 block text-sm text-slate-500">Request Sparepart Awal:</span>
                    @php
                        $sparepartDiminta = is_string($booking->sparepart_diminta) ? json_decode($booking->sparepart_diminta, true) : $booking->sparepart_diminta;
                    @endphp
                    @if(is_array($sparepartDiminta) && count($sparepartDiminta) > 0)
                        <ul class="list-inside list-disc space-y-1 text-sm text-slate-800 pl-4">
                            @foreach($sparepartDiminta as $sparepart)
                                <li>{{ $sparepart }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-slate-500 italic">Tidak ada request sparepart diawal.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Catatan & Hasil Servis --}}
        @if(!empty($booking->catatan_mekanik) || !empty($booking->sparepart_terpakai) || !empty($booking->rekomendasi_sparepart))
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 shadow-sm md:col-span-2">
                <h3 class="mb-4 text-lg font-bold text-amber-900">Hasil Pemeriksaan & Catatan Mekanik</h3>
                
                @if(!empty($booking->catatan_mekanik))
                    <div class="mb-4">
                        <span class="mb-1 block text-sm font-semibold text-amber-800">Catatan Tambahan Mekanik:</span>
                        <p class="text-sm text-amber-900">{{ $booking->catatan_mekanik }}</p>
                    </div>
                @endif

                @if(!empty($booking->sparepart_terpakai))
                    <div class="mb-4">
                        <span class="mb-1 block text-sm font-semibold text-amber-800">Sparepart yang Terpakai/Diganti:</span>
                        <p class="text-sm text-amber-900">{{ $booking->sparepart_terpakai }}</p>
                    </div>
                @endif
                
                @php
                    $rekomendasi = is_string($booking->rekomendasi_sparepart) ? json_decode($booking->rekomendasi_sparepart, true) : $booking->rekomendasi_sparepart;
                @endphp
                @if(!empty($rekomendasi) && is_array($rekomendasi))
                    <div>
                        <span class="mb-2 block text-sm font-semibold text-amber-800">Rekomendasi Perbaikan Tambahan:</span>
                        <ul class="list-inside list-disc space-y-1 text-sm text-amber-900 pl-4">
                            @foreach($rekomendasi as $rek)
                                <li>{{ $rek['nama'] }} ({{ $rek['jumlah'] }}x) - Estimasi: Rp {{ number_format($rek['harga'], 0, ',', '.') }}</li>
                            @endforeach
                        </ul>
                        <div class="mt-2 text-sm text-amber-800">
                            Status Persetujuan: 
                            <span class="font-bold uppercase {{ $booking->status_konfirmasi === 'approved' ? 'text-green-600' : ($booking->status_konfirmasi === 'rejected' ? 'text-red-600' : 'text-amber-600') }}">
                                {{ $booking->status_konfirmasi ?? 'Belum ada tanggapan' }}
                            </span>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
