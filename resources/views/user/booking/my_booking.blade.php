@extends('components.app')

@section('title', 'Booking Saya')

@php
    $bookingStatus = $booking->status ?? null;
    $paymentStatus = $booking->status_pembayaran ?? null;
    $progressWidth = '0%';
    $statusLabel = 'Belum Ada Status';
    $statusBadgeClass = 'badge-neutral';
    $headline = 'Belum ada servis aktif';
    $description = 'Booking servis Anda akan tampil di halaman ini setelah antrean berhasil dibuat.';

    if (in_array($bookingStatus, ['diproses', 'Proses'])) {
        $progressWidth = '33%';
        $statusLabel = 'Sedang Dikerjakan';
        $statusBadgeClass = 'badge-info';
        $headline = 'Motor Anda sedang dalam proses servis.';
        $description =
            'Mekanik kami sedang menangani kendaraan Anda. Pantau halaman ini untuk melihat pembaruan berikutnya.';
    }

    if (in_array($bookingStatus, ['selesai', 'Selesai'])) {
        $progressWidth = '66%';
        $statusLabel = 'Servis Selesai';
        $statusBadgeClass = 'badge-warning';
        $headline = 'Proses servis sudah selesai.';
        $description =
            'Kendaraan sudah selesai dikerjakan. Silakan lanjut ke pembayaran atau tunggu arahan dari admin.';
    }

    if (in_array($bookingStatus, ['menunggu', 'Pending'])) {
        $statusLabel = 'Menunggu Antrean';
        $statusBadgeClass = 'badge-warning';
        $headline = 'Booking Anda sudah masuk antrean.';
        $description = 'Kendaraan Anda sudah tercatat dan sedang menunggu giliran untuk dikerjakan.';
    }

    if ($paymentStatus === 'lunas') {
        $progressWidth = '100%';
        $statusLabel = 'Pembayaran Lunas';
        $statusBadgeClass = 'badge-success';
        $headline = 'Semua proses sudah selesai.';
        $description = 'Pembayaran telah diterima dan kendaraan Anda siap dibawa pulang.';
    }
@endphp

@section('content')
    <section class="pb-16 pt-10">
        <div class="landing-container">
            <div class="mx-auto flex max-w-6xl flex-col gap-6">
                <section class="surface-card overflow-visible">
                    <div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr]">
                        <div>
                            <p class="page-kicker">STATUS TRACKING</p>
                            <h1 class="mt-3 text-4xl font-bold text-slate-950 md:text-5xl">
                                Pantau Progres Servis Motor Anda Secara Real-Time.
                            </h1>
                            <p class="mt-4 max-w-2xl text-base leading-8 text-slate-600">
                                Halaman khusus bagi pelanggan untuk melacak setiap tahapan perbaikan kendaraan secara transparan, memberikan kepastian dan kenyamanan layanan.
                            </p>

                            <div class="mt-8 flex flex-wrap gap-3">
                                <a href="{{ route('landing') }}" class="btn-accent">Booking Baru</a>
                                <a href="{{ route('landing') }}#booking" class="btn-secondary">Lihat Form Booking</a>
                            </div>
                        </div>

                        <div class="landing-spotlight">
                            <div class="landing-spotlight-panel">
                                <p class="text-sm font-bold uppercase tracking-[0.24em] text-yellow-200">HALO,
                                    {{ auth()->user()->name }}</p>
                                <h2 class="mt-3 text-3xl font-bold">{{ $headline }}</h2>
                                <p class="mt-4 text-sm leading-7 text-slate-200">{{ $description }}</p>

                                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                                    <div class="landing-stat-card">
                                        <div class="text-xs uppercase tracking-[0.22em] text-slate-300">Status Saat Ini
                                        </div>
                                        <div class="mt-2">
                                            <span class="badge {{ $statusBadgeClass }}">{{ $statusLabel }}</span>
                                        </div>
                                    </div>
                                    <div class="landing-stat-card">
                                        <div class="text-xs uppercase tracking-[0.22em] text-slate-300">Akses</div>
                                        <div class="mt-2 text-xl font-bold text-white">NOTIFIKASI</div>
                                        <p class="mt-2 text-sm text-slate-200">
                                            Pembaruan data terintegrasi langsung dari ruang mekanik secara instan.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                @if ($booking)
                    <section class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
                        <div class="flex flex-col gap-6">
                            <div class="tracking-card">
                                <div
                                    class="grid gap-4 rounded-[28px] border border-slate-100 bg-slate-50/80 p-5 md:grid-cols-3">
                                    <div>
                                        <div class="page-kicker">Kendaraan</div>
                                        <div class="mt-2 text-xl font-bold text-slate-950">{{ $booking->tipe_motor }}</div>
                                    </div>
                                    <div>
                                        <div class="page-kicker">Plat Nomor</div>
                                        <div class="mt-2 text-xl font-bold text-slate-950">{{ $booking->plat_nomor }}</div>
                                    </div>
                                    <div>
                                        <div class="page-kicker">Jadwal</div>
                                        <div class="mt-2 text-xl font-bold text-slate-950">
                                            {{ \Carbon\Carbon::parse($booking->jadwal_booking)->format('d M Y, H:i') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="timeline-track">
                                    <div class="timeline-progress" style="width: {{ $progressWidth }}"></div>

                                    <div class="timeline-node">
                                        <div
                                            class="timeline-icon {{ in_array($bookingStatus, ['menunggu', 'Pending']) ? 'is-active' : 'is-done' }}">
                                            1</div>
                                        <div class="timeline-label">Antre</div>
                                    </div>

                                    <div class="timeline-node">
                                        <div
                                            class="timeline-icon {{ in_array($bookingStatus, ['diproses', 'Proses']) ? 'is-active' : (in_array($bookingStatus, ['selesai', 'Selesai']) || $paymentStatus === 'lunas' ? 'is-done' : '') }}">
                                            2</div>
                                        <div class="timeline-label">Dikerjakan</div>
                                    </div>

                                    <div class="timeline-node">
                                        <div
                                            class="timeline-icon {{ in_array($bookingStatus, ['selesai', 'Selesai']) && $paymentStatus !== 'lunas' ? 'is-active' : ($paymentStatus === 'lunas' ? 'is-done' : '') }}">
                                            3</div>
                                        <div class="timeline-label">Selesai</div>
                                    </div>

                                    <div class="timeline-node">
                                        <div class="timeline-icon {{ $paymentStatus === 'lunas' ? 'is-done' : '' }}">4
                                        </div>
                                        <div class="timeline-label">Lunas</div>
                                    </div>
                                </div>

                                <div
                                    class="mt-6 rounded-[24px] border border-slate-100 bg-white p-5 text-sm leading-7 text-slate-600">
                                    {{ $description }}
                                </div>

                                @if (!empty($booking->rekomendasi_sparepart))
                                    @php
                                        $rekThemeClass = 'border-amber-200 bg-amber-50';
                                        $rekIconClass = 'bg-amber-100 text-amber-600';
                                        $rekTableFootClass = 'bg-amber-50/50';
                                        $rekTotalClass = 'text-amber-600';
                                        $rekIcon =
                                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />';
                                        $rekTitle = 'Tindakan Diperlukan: Rekomendasi Perbaikan Tambahan';

                                        if ($booking->status_konfirmasi === 'approved') {
                                            $rekThemeClass = 'border-green-200 bg-green-50';
                                            $rekIconClass = 'bg-green-100 text-green-600';
                                            $rekTableFootClass = 'bg-green-50/50';
                                            $rekTotalClass = 'text-green-600';
                                            $rekIcon =
                                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />';
                                            $rekTitle = 'Rekomendasi Perbaikan Telah Disetujui';
                                        } elseif ($booking->status_konfirmasi === 'rejected') {
                                            $rekThemeClass = 'border-red-200 bg-red-50';
                                            $rekIconClass = 'bg-red-100 text-red-600';
                                            $rekTableFootClass = 'bg-red-50/50';
                                            $rekTotalClass = 'text-red-600';
                                            $rekIcon =
                                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';
                                            $rekTitle = 'Rekomendasi Perbaikan Telah Ditolak';
                                        }
                                    @endphp

                                    <div class="{{ $rekThemeClass }} mt-6 rounded-[24px] border p-6 shadow-sm">
                                        <div class="flex flex-col items-start gap-4 sm:flex-row">
                                            <div
                                                class="{{ $rekIconClass }} flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    {!! $rekIcon !!}
                                                </svg>
                                            </div>
                                            <div class="w-full">
                                                <h3 class="text-lg font-bold text-slate-900">{{ $rekTitle }}</h3>
                                                <p class="mt-1 text-sm leading-6 text-slate-600">
                                                    {{ $booking->status_konfirmasi === 'pending' ? 'Mekanik menemukan beberapa kendala tambahan di luar keluhan awal Anda. Berikut adalah rincian part yang disarankan untuk diganti:' : 'Berikut adalah rincian part tambahan yang sebelumnya direkomendasikan mekanik:' }}
                                                </p>

                                                <div
                                                    class="mt-5 overflow-hidden rounded-[16px] border border-slate-200 bg-white shadow-sm">
                                                    <div class="overflow-x-auto">
                                                        <table class="w-full text-left text-sm">
                                                            <thead class="bg-slate-50 text-slate-600">
                                                                <tr>
                                                                    <th
                                                                        class="px-5 py-4 text-xs font-semibold uppercase tracking-wider">
                                                                        Nama Part</th>
                                                                    <th
                                                                        class="px-5 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                                                                        Jumlah</th>
                                                                    <th
                                                                        class="px-5 py-4 text-right text-xs font-semibold uppercase tracking-wider">
                                                                        Harga</th>
                                                                    <th
                                                                        class="px-5 py-4 text-right text-xs font-semibold uppercase tracking-wider">
                                                                        Subtotal</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y divide-slate-100">
                                                                @php $totalEstimasi = 0; @endphp
                                                                @if ($booking->rekomendasi_sparepart)
                                                                    @foreach ($booking->rekomendasi_sparepart as $rek)
                                                                        @php
                                                                            $subtotal = $rek['harga'] * $rek['jumlah'];
                                                                            $totalEstimasi += $subtotal;
                                                                        @endphp
                                                                        <tr class="transition hover:bg-slate-50/50">
                                                                            <td
                                                                                class="whitespace-nowrap px-5 py-4 font-medium text-slate-800">
                                                                                {{ $rek['nama'] }}</td>
                                                                            <td
                                                                                class="px-5 py-4 text-center text-slate-600">
                                                                                {{ $rek['jumlah'] }}</td>
                                                                            <td
                                                                                class="whitespace-nowrap px-5 py-4 text-right text-slate-600">
                                                                                Rp
                                                                                {{ number_format($rek['harga'], 0, ',', '.') }}
                                                                            </td>
                                                                            <td
                                                                                class="whitespace-nowrap px-5 py-4 text-right font-medium text-slate-900">
                                                                                Rp
                                                                                {{ number_format($subtotal, 0, ',', '.') }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif
                                                            </tbody>
                                                            <tfoot class="{{ $rekTableFootClass }}">
                                                                <tr>
                                                                    <th colspan="3"
                                                                        class="px-5 py-4 text-right font-bold text-slate-800">
                                                                        Estimasi Tambahan Biaya:</th>
                                                                    <th
                                                                        class="{{ $rekTotalClass }} whitespace-nowrap px-5 py-4 text-right text-base font-bold">
                                                                        Rp {{ number_format($totalEstimasi, 0, ',', '.') }}
                                                                    </th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>

                                                @if ($booking->status_konfirmasi === 'pending')
                                                    <form
                                                        action="{{ route('booking.konfirmasi_rekomendasi', $booking->id) }}"
                                                        method="POST" class="mt-6 flex flex-col gap-3 sm:flex-row">
                                                        @csrf
                                                        <button type="submit" name="status_konfirmasi" value="approved"
                                                            class="flex-1 rounded-xl bg-green-500 px-5 py-3 text-center text-sm font-semibold text-white shadow-sm transition hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2">
                                                            ✓ Setujui Perbaikan
                                                        </button>
                                                        <button type="submit" name="status_konfirmasi" value="rejected"
                                                            class="flex-1 rounded-xl border border-red-200 bg-red-50 px-5 py-3 text-center text-sm font-semibold text-red-600 shadow-sm transition hover:border-red-300 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2">
                                                            ✕ Tolak & Tetap Servis Awal
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-6 rounded-[24px] border border-slate-100 bg-white p-6 shadow-sm">
                                        <div class="flex flex-col items-start gap-4 sm:flex-row">
                                            <div
                                                class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-slate-50 text-slate-400">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-bold text-slate-900">Rekomendasi Perbaikan</h3>
                                                <p class="mt-1 text-sm leading-6 text-slate-500">Saat ini tidak ada
                                                    rekomendasi perbaikan tambahan dari mekanik.</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- UI Tab Pilihan Motor Baru --}}
                            @if (isset($bookings) && $bookings->count() > 1)
                                <div class="rounded-[28px] border border-slate-100 bg-white p-6 shadow-sm">
                                    <div class="mb-5 flex items-center justify-between">
                                        <div>
                                            <h3 class="text-lg font-bold text-slate-900">Kendaraan Anda Lainnya</h3>
                                            <p class="mt-1 text-sm text-slate-500">Pilih motor di bawah ini untuk beralih
                                                melihat status servisnya.</p>
                                        </div>
                                        <div
                                            class="hidden h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-500 sm:flex">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="grid gap-3 sm:grid-cols-2">
                                        @foreach ($bookings as $b)
                                            <a href="{{ route('booking.mine', ['id' => $b->id]) }}"
                                                class="{{ $booking->id == $b->id ? 'border-blue-200 bg-blue-50/50 shadow-sm ring-1 ring-blue-500' : 'border-slate-100 bg-white hover:border-blue-100 hover:bg-slate-50 hover:shadow-md' }} group relative flex items-center justify-between overflow-hidden rounded-2xl border p-4 transition-all duration-300">

                                                @if ($booking->id == $b->id)
                                                    <div class="absolute bottom-0 left-0 top-0 w-1.5 bg-blue-600"></div>
                                                @endif

                                                <div
                                                    class="{{ $booking->id == $b->id ? 'pl-2' : '' }} flex items-center gap-4">
                                                    <div
                                                        class="{{ $booking->id == $b->id ? 'bg-blue-600 text-white shadow-md' : 'bg-slate-100 text-slate-400 group-hover:bg-blue-100 group-hover:text-blue-600' }} flex h-10 w-10 items-center justify-center rounded-full">
                                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M13 10V3L4 14h7v8l9-11h-7z" />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h4
                                                            class="{{ $booking->id == $b->id ? 'text-blue-900' : 'text-slate-800 group-hover:text-blue-700' }} text-sm font-bold">
                                                            {{ $b->tipe_motor }}</h4>
                                                        <p
                                                            class="{{ $booking->id == $b->id ? 'text-blue-600' : 'text-slate-400' }} mt-0.5 text-xs font-semibold uppercase tracking-wider">
                                                            {{ $b->plat_nomor }}</p>
                                                    </div>
                                                </div>

                                                <div class="flex flex-col items-end gap-1">
                                                    @if (in_array($b->status, ['menunggu', 'diproses']))
                                                        <span
                                                            class="{{ $booking->id == $b->id ? 'bg-blue-600 shadow-[0_0_8px_rgba(37,99,235,0.6)]' : 'bg-yellow-400 shadow-[0_0_8px_rgba(250,204,21,0.6)]' }} flex h-3 w-3 animate-pulse rounded-full"></span>
                                                        <span class="text-[10px] font-bold text-slate-400">Proses</span>
                                                    @else
                                                        <span
                                                            class="{{ $booking->id == $b->id ? 'bg-blue-600' : 'bg-emerald-400' }} flex h-3 w-3 rounded-full"></span>
                                                        <span class="text-[10px] font-bold text-slate-400">Selesai</span>
                                                    @endif
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="surface-card">
                            <div>
                                <p class="page-kicker">Ringkasan Booking</p>
                                <h2 class="mt-3 text-2xl font-bold text-slate-950">Detail servis Anda</h2>
                                <p class="mt-3 text-sm leading-7 text-slate-500">
                                    Semua informasi utama booking diringkas di sini agar pelanggan lebih mudah memahami
                                    posisi servisnya.
                                </p>
                            </div>

                            <div class="mt-6 space-y-4">
                                <div class="rounded-[24px] border border-slate-100 bg-slate-50/80 p-4">
                                    <div class="page-kicker">Keluhan</div>
                                    <div class="mt-2 text-sm leading-7 text-slate-700">{{ $booking->keluhan }}</div>
                                </div>

                                <div class="rounded-[24px] border border-slate-100 bg-slate-50/80 p-4">
                                    <div class="page-kicker">Status Booking</div>
                                    <div class="mt-2">
                                        <span class="badge {{ $statusBadgeClass }}">{{ $statusLabel }}</span>
                                    </div>
                                </div>

                                @if (!empty($booking->catatan_mekanik))
                                    <div class="rounded-[24px] border border-slate-100 bg-slate-50/80 p-4">
                                        <div class="page-kicker">Catatan Mekanik</div>
                                        <div class="mt-2 text-sm leading-7 text-slate-700">
                                            {{ $booking->catatan_mekanik }}</div>
                                    </div>
                                @endif

                                @if (!empty($booking->sparepart_terpakai))
                                    <div class="rounded-[24px] border border-slate-100 bg-slate-50/80 p-4">
                                        <div class="page-kicker">Sparepart Diganti</div>
                                        <div class="mt-2 text-sm leading-7 text-slate-700">
                                            {{ $booking->sparepart_terpakai }}</div>
                                    </div>
                                @endif

                                <div class="rounded-[24px] border border-slate-100 bg-slate-50/80 p-4">
                                    <div class="page-kicker">Aksi Cepat</div>
                                    <div class="mt-4 flex flex-wrap gap-3">
                                        @if ($booking->transaksi)
                                            @if (($booking->transaksi->status_pembayaran ?? null) === 'lunas')
                                                <a href="{{ route('transaksi.cetak', $booking->transaksi->id) }}"
                                                    class="btn-primary">Lihat Nota</a>
                                            @else
                                                <a href="{{ route('transaksi.bayar', $booking->transaksi->id) }}"
                                                    class="btn-primary">Lanjut Pembayaran</a>
                                            @endif
                                        @endif
                                        <a href="{{ route('booking.show', $booking->id) }}" class="btn-accent">Lihat
                                            Detail Lengkap</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                @else
                    <section class="empty-state">
                        <div class="empty-state-icon">BK</div>
                        <h3 class="text-2xl font-bold text-slate-950">Belum ada servis aktif</h3>
                        <p class="max-w-2xl text-sm leading-7 text-slate-500">
                            Anda belum memiliki jadwal servis saat ini. Gunakan halaman landing untuk membuat booking
                            baru dan pantau progresnya di sini setelah tersimpan.
                        </p>
                        <div class="mt-2 flex flex-wrap justify-center gap-3">
                            <a href="{{ route('landing') }}" class="btn-primary">Booking Sekarang</a>
                            <a href="{{ route('landing') }}#booking" class="btn-secondary">Buka Form Booking</a>
                        </div>
                    </section>
                @endif
            </div>
        </div>
    </section>
@endsection
