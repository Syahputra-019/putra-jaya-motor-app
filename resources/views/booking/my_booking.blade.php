<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Saya | Putra Jaya Motor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

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
        $description = 'Mekanik kami sedang menangani kendaraan Anda. Pantau halaman ini untuk melihat pembaruan berikutnya.';
    }

    if (in_array($bookingStatus, ['selesai', 'Selesai'])) {
        $progressWidth = '66%';
        $statusLabel = 'Servis Selesai';
        $statusBadgeClass = 'badge-warning';
        $headline = 'Proses servis sudah selesai.';
        $description = 'Kendaraan sudah selesai dikerjakan. Silakan lanjut ke pembayaran atau tunggu arahan dari admin.';
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

<body class="landing-body">
    <nav class="landing-nav">
        <div class="landing-container flex items-center justify-between py-4">
            <a href="{{ route('landing') }}" class="flex items-center gap-3">
                <div class="sidebar-brand-mark h-12 w-12 rounded-2xl text-sm">PJM</div>
                <div>
                    <div class="text-sm font-bold uppercase tracking-[0.24em] text-slate-950">Putra Jaya</div>
                    <div class="text-xs text-slate-500">Customer Booking Portal</div>
                </div>
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ route('landing') }}" class="btn-secondary hidden sm:inline-flex">Kembali ke Beranda</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-primary">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="pb-16 pt-32">
        <div class="landing-container">
            <div class="mx-auto flex max-w-6xl flex-col gap-6">
                <section class="surface-card overflow-visible">
                    <div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr]">
                        <div>
                            <p class="page-kicker">Customer Tracking</p>
                            <h1 class="mt-3 text-4xl font-bold text-slate-950 md:text-5xl">Pantau progres servis motor Anda tanpa buka panel admin.</h1>
                            <p class="mt-4 max-w-2xl text-base leading-8 text-slate-600">
                                Halaman ini sekarang berdiri sendiri, lebih cocok untuk pelanggan, dan fokus menampilkan status booking dengan cara yang lebih nyaman dibaca.
                            </p>

                            <div class="mt-8 flex flex-wrap gap-3">
                                <a href="{{ route('landing') }}" class="btn-accent">Booking Baru</a>
                                <a href="{{ route('landing') }}#booking" class="btn-secondary">Lihat Form Booking</a>
                            </div>
                        </div>

                        <div class="landing-spotlight">
                            <div class="landing-spotlight-panel">
                                <p class="text-sm font-bold uppercase tracking-[0.24em] text-yellow-200">Halo, {{ auth()->user()->name }}</p>
                                <h2 class="mt-3 text-3xl font-bold">{{ $headline }}</h2>
                                <p class="mt-4 text-sm leading-7 text-slate-200">{{ $description }}</p>

                                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                                    <div class="landing-stat-card">
                                        <div class="text-xs uppercase tracking-[0.22em] text-slate-300">Status Saat Ini</div>
                                        <div class="mt-2">
                                            <span class="badge {{ $statusBadgeClass }}">{{ $statusLabel }}</span>
                                        </div>
                                    </div>
                                    <div class="landing-stat-card">
                                        <div class="text-xs uppercase tracking-[0.22em] text-slate-300">Akses</div>
                                        <div class="mt-2 text-2xl font-bold text-white">Mandiri</div>
                                        <p class="mt-2 text-sm text-slate-200">Halaman pelanggan tanpa sidebar dashboard.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                @if ($booking)
                    <section class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
                        <div class="tracking-card">
                            <div class="grid gap-4 rounded-[28px] border border-slate-100 bg-slate-50/80 p-5 md:grid-cols-3">
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
                                    <div class="timeline-icon {{ in_array($bookingStatus, ['menunggu', 'Pending']) ? 'is-active' : 'is-done' }}">1</div>
                                    <div class="timeline-label">Antre</div>
                                </div>

                                <div class="timeline-node">
                                    <div class="timeline-icon {{ in_array($bookingStatus, ['diproses', 'Proses']) ? 'is-active' : (in_array($bookingStatus, ['selesai', 'Selesai']) || $paymentStatus === 'lunas' ? 'is-done' : '') }}">2</div>
                                    <div class="timeline-label">Dikerjakan</div>
                                </div>

                                <div class="timeline-node">
                                    <div class="timeline-icon {{ in_array($bookingStatus, ['selesai', 'Selesai']) && $paymentStatus !== 'lunas' ? 'is-active' : ($paymentStatus === 'lunas' ? 'is-done' : '') }}">3</div>
                                    <div class="timeline-label">Selesai</div>
                                </div>

                                <div class="timeline-node">
                                    <div class="timeline-icon {{ $paymentStatus === 'lunas' ? 'is-done' : '' }}">4</div>
                                    <div class="timeline-label">Lunas</div>
                                </div>
                            </div>

                            <div class="mt-6 rounded-[24px] border border-slate-100 bg-white p-5 text-sm leading-7 text-slate-600">
                                {{ $description }}
                            </div>
                        </div>

                        <div class="surface-card">
                            <div>
                                <p class="page-kicker">Ringkasan Booking</p>
                                <h2 class="mt-3 text-2xl font-bold text-slate-950">Detail servis Anda</h2>
                                <p class="mt-3 text-sm leading-7 text-slate-500">
                                    Semua informasi utama booking diringkas di sini agar pelanggan lebih mudah memahami posisi servisnya.
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
                                        <div class="mt-2 text-sm leading-7 text-slate-700">{{ $booking->catatan_mekanik }}</div>
                                    </div>
                                @endif

                                @if (!empty($booking->sparepart_terpakai))
                                    <div class="rounded-[24px] border border-slate-100 bg-slate-50/80 p-4">
                                        <div class="page-kicker">Sparepart Diganti</div>
                                        <div class="mt-2 text-sm leading-7 text-slate-700">{{ $booking->sparepart_terpakai }}</div>
                                    </div>
                                @endif

                                <div class="rounded-[24px] border border-slate-100 bg-slate-50/80 p-4">
                                    <div class="page-kicker">Aksi Cepat</div>
                                    <div class="mt-4 flex flex-wrap gap-3">
                                        <a href="{{ route('landing') }}" class="btn-secondary">Kembali ke Landing</a>
                                        <a href="{{ route('landing') }}#booking" class="btn-primary">Booking Lagi</a>
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
                            Anda belum memiliki jadwal servis saat ini. Gunakan halaman landing untuk membuat booking baru dan pantau progresnya di sini setelah tersimpan.
                        </p>
                        <div class="mt-2 flex flex-wrap justify-center gap-3">
                            <a href="{{ route('landing') }}" class="btn-primary">Booking Sekarang</a>
                            <a href="{{ route('landing') }}#booking" class="btn-secondary">Buka Form Booking</a>
                        </div>
                    </section>
                @endif
            </div>
        </div>
    </main>
</body>

</html>
