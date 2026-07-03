<x-layout>
    <div class="page-shell">
        <div class="page-header">
            <div class="page-header-split">
                <p class="page-kicker">Booking Details</p>
                <h1 class="page-title">Detail Antrean Booking</h1>
                <p class="page-description">Informasi lengkap mengenai booking dan keluhan pelanggan.</p>
            </div>
            <div class="page-actions">
                <a href="{{ route('booking.index') }}" class="btn-primary">Kembali</a>
            </div>
        </div>

        <div class="surface-card mt-6 p-6">
            <div class="grid gap-4 md:grid-cols-3">
                <div>
                    <span class="block text-sm font-medium text-slate-500">Nomor Antrean</span>
                    <span class="block text-2xl font-bold text-blue-700">
                        {{ $booking->kode_antrean ?? ($booking->nomor_antrean ? '#' . str_pad((string) $booking->nomor_antrean, 3, '0', STR_PAD_LEFT) : '-') }}
                    </span>
                </div>
                <div>
                    <span class="block text-sm font-medium text-slate-500">Tanggal Antrean</span>
                    <span class="block text-base font-semibold text-slate-800">
                        {{ optional($booking->tanggal_antrean)->format('d M Y') ?? \Carbon\Carbon::parse($booking->jadwal_booking)->format('d M Y') }}
                    </span>
                </div>
                <div>
                    <span class="block text-sm font-medium text-slate-500">Nomor Harian</span>
                    <span class="block text-base font-semibold text-slate-800">
                        {{ $booking->nomor_antrean ? '#' . str_pad((string) $booking->nomor_antrean, 3, '0', STR_PAD_LEFT) : '-' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="surface-card mt-6 p-6">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Info Pelanggan -->
                <div>
                    <h3 class="mb-4 border-b pb-2 text-lg font-semibold text-slate-900">Informasi Pelanggan</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="block text-sm font-medium text-slate-500">Nama Pelanggan</span>
                            <span
                                class="block text-base font-semibold text-slate-800">{{ $booking->pelanggan->nama_pelanggan ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-slate-500">Nomor HP</span>
                            <span
                                class="block text-base font-semibold text-slate-800">{{ $booking->pelanggan->no_telp ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-slate-500">Dibuat Pada</span>
                            <span
                                class="block text-base font-semibold text-slate-800">{{ $booking->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Info Booking -->
                <div>
                    <h3 class="mb-4 border-b pb-2 text-lg font-semibold text-slate-900">Informasi Kendaraan & Servis
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <span class="block text-sm font-medium text-slate-500">Plat Nomor / Motor</span>
                            <span class="block text-base font-semibold text-slate-800">{{ $booking->plat_nomor }} -
                                {{ $booking->tipe_motor }}</span>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-slate-500">Jadwal Booking</span>
                            <span
                                class="block text-base font-semibold text-slate-800">{{ \Carbon\Carbon::parse($booking->jadwal_booking)->format('d M Y, H:i') }}</span>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-slate-500">Status Saat Ini</span>
                            <span
                                class="badge {{ $booking->status == 'menunggu' ? 'badge-warning' : ($booking->status == 'diproses' ? 'badge-info' : ($booking->status == 'selesai' ? 'badge-success' : 'badge-danger')) }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Keluhan & Layanan -->
                <div class="mt-4 md:col-span-2">
                    <h3 class="mb-4 border-b pb-2 text-lg font-semibold text-slate-900">Keluhan & Permintaan</h3>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <p class="mb-1 text-sm font-medium text-slate-500">Keluhan Pelanggan:</p>
                        <p class="mb-4 text-base text-slate-800">
                            {{ $booking->keluhan ?? 'Tidak ada keluhan tertulis.' }}</p>

                        @if (!empty($booking->kategori_servis) && is_array($booking->kategori_servis))
                            <p class="mb-1 text-sm font-medium text-slate-500">Kategori Servis:</p>
                            <ul class="mb-4 list-inside list-disc text-sm text-slate-800">
                                @foreach ($booking->kategori_servis as $layanan)
                                    <li>{{ $layanan }}</li>
                                @endforeach
                            </ul>
                        @endif

                        @if (!empty($booking->sparepart_diminta) && is_array($booking->sparepart_diminta))
                            <p class="mb-1 text-sm font-medium text-slate-500">Permintaan Sparepart:</p>
                            <ul class="list-inside list-disc text-sm text-slate-800">
                                @foreach ($booking->sparepart_diminta as $part)
                                    <li>{{ $part }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
