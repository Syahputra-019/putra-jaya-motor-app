<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Putra Jaya Motor | Booking Servis Online</title>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@php
    $bookingStatus = $booking->status ?? null;
    $paymentStatus = $booking->status_pembayaran ?? null;
    $progressWidth = '0%';

    if (in_array($bookingStatus, ['diproses', 'Proses'])) {
        $progressWidth = '33%';
    }

    if (in_array($bookingStatus, ['selesai', 'Selesai'])) {
        $progressWidth = '66%';
    }

    if ($paymentStatus === 'lunas') {
        $progressWidth = '100%';
    }
@endphp

<body class="landing-body" x-data="{ mobileMenu: false }">
    <nav class="landing-nav">
        <div class="landing-container flex items-center justify-between py-4">
            <a href="{{ route('landing') }}" class="flex items-center gap-3">
                <div class="sidebar-brand-mark h-12 w-12 rounded-2xl text-sm">
                    <img src="{{ asset('images/logooo.png') }}" alt="Logo PJM" class="sidebar-brand-mark object-cover">
                </div>
                <div>
                    <div class="text-sm font-bold uppercase tracking-[0.24em] text-slate-950">Putra Jaya Motor</div>
                    <div class="text-xs text-slate-500">Servis Motor</div>
                </div>
            </a>

            <div class="hidden items-center gap-8 lg:flex">
                <a href="#hero" class="text-sm font-semibold text-slate-600 transition hover:text-slate-950">Home</a>
                <a href="#layanan"
                    class="text-sm font-semibold text-slate-600 transition hover:text-slate-950">Layanan</a>
                <a href="#booking"
                    class="text-sm font-semibold text-slate-600 transition hover:text-slate-950">Booking</a>
            </div>

            <div class="hidden items-center gap-3 lg:flex">
                @guest
                    <a href="{{ route('login') }}" class="btn-secondary">Login</a>
                    <a href="{{ route('register') }}" class="btn-accent">Register</a>
                @endguest

                @auth
                    @if (Auth::user()->role === 'admin')
                        <a href="{{ route('dashboard') }}" class="btn-primary">Dashboard Admin</a>
                    @elseif (Auth::user()->role === 'mekanik')
                        <a href="{{ route('mekanik.jadwal') }}" class="btn-primary">Jadwal Servis</a>
                    @elseif (Auth::user()->role === 'pelanggan')
                        <div class="relative" x-data="{ openProfile: false }">
                            <button @click="openProfile = !openProfile" @click.away="openProfile = false"
                                class="flex items-center gap-3 rounded-full border border-slate-200 bg-white p-1.5 pr-4 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500">

                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white shadow-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>

                                <span class="text-sm font-semibold text-slate-700">
                                    {{ explode(' ', Auth::user()->name)[0] }}
                                </span>

                                <svg class="h-4 w-4 text-slate-400 transition-transform duration-200"
                                    :class="openProfile ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="openProfile" x-transition.opacity.duration.200ms
                                class="absolute right-0 z-50 mt-3 w-64 rounded-2xl border border-slate-100 bg-white p-2 shadow-xl"
                                style="display: none;">

                                <div class="px-3 py-2 text-xs font-bold uppercase tracking-wider text-slate-400">Akun Saya
                                </div>

                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-blue-600">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Profil Saya
                                </a>

                                <a href="{{ route('booking.mine') }}"
                                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-blue-600">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    Status Servis (Tracking)
                                </a>

                                <a href="{{ route('komplain.create') }}"
                                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-orange-50 hover:text-orange-600">
                                    <svg class="h-5 w-5 text-orange-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    Ajukan Komplain
                                </a>

                                <div class="my-1 border-t border-slate-100"></div>

                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-medium text-rose-600 transition hover:bg-rose-50">
                                        <svg class="h-5 w-5 text-rose-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>

            <button type="button"
                class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white lg:hidden"
                @click="mobileMenu = !mobileMenu">
                <span class="text-lg font-black text-slate-900">=</span>
            </button>
        </div>

        <div x-show="mobileMenu" x-transition class="border-t border-slate-200 bg-white lg:hidden">
            <div class="landing-container flex flex-col gap-3 py-4">
                <a href="#hero" class="text-sm font-semibold text-slate-700">Home</a>
                <a href="#layanan" class="text-sm font-semibold text-slate-700">Layanan</a>
                <a href="#booking" class="text-sm font-semibold text-slate-700">Booking</a>
                @guest
                    <a href="{{ route('login') }}" class="btn-secondary mt-2">Login</a>
                    <a href="{{ route('register') }}" class="btn-accent">Register</a>
                @endguest
                @guest
                    <a href="{{ route('login') }}" class="btn-secondary mt-2">Login</a>
                    <a href="{{ route('register') }}" class="btn-accent">Register</a>
                @endguest

                @auth
                    @if (Auth::user()->role === 'pelanggan')
                        <div class="mt-4 border-t border-slate-100 pt-4">
                            <div class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-400">Hai,
                                {{ Auth::user()->name }}</div>

                            <a href="{{ route('profile.edit') }}"
                                class="block rounded-lg py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Profil
                                Saya</a>

                            <a href="{{ route('booking.mine') }}"
                                class="block rounded-lg py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Status
                                Servis</a>

                            <a href="{{ route('komplain.create') }}"
                                class="block rounded-lg py-2 text-sm font-semibold text-orange-600 transition hover:bg-orange-50">Ajukan
                                Komplain</a>

                            <form action="{{ route('logout') }}" method="POST" class="mt-2">
                                @csrf
                                <button type="submit"
                                    class="block w-full rounded-lg py-2 text-left text-sm font-semibold text-rose-600 transition hover:bg-rose-50">Logout</button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn-primary mt-2">Dashboard Staff</a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    <section id="hero" class="landing-hero">
        <div class="landing-container landing-hero-grid">
            <div data-aos="fade-up" data-aos-delay="150">

                <h1 class="landing-title">Booking servis motor jadi lebih cepat, modern, dan nyaman dipantau.</h1>
                <p class="landing-lead">
                    Putra Jaya Motor membantu pelanggan booking tanpa ribet sekaligus memberi tim bengkel tampilan kerja
                    yang lebih rapi, konsisten, dan profesional.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="#booking" class="btn-primary">Booking Sekarang</a>
                    <a href="#layanan" class="btn-accent">Lihat Layanan</a>
                </div>

                <div class="mt-10 grid max-w-3xl grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="surface-card-tight" data-aos="fade-right" data-aos-delay="200">
                        <div class="page-kicker">Keunggulan</div>
                        <div class="mt-2 text-xl font-bold text-slate-950">Booking Online</div>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Ambil antrean tanpa harus datang lebih dulu ke
                            bengkel.</p>
                    </div>
                    <div class="surface-card-tight" data-aos="fade-right" data-aos-delay="300">
                        <div class="page-kicker">Keunggulan</div>
                        <div class="mt-2 text-xl font-bold text-slate-950">Status Real-time</div>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Pantau progres pengerjaan kendaraan dari satu
                            halaman.</p>
                    </div>
                    <div class="surface-card-tight" data-aos="fade-right" data-aos-delay="400">
                        <div class="page-kicker">Keunggulan</div>
                        <div class="mt-2 text-xl font-bold text-slate-950">Pembayaran Fleksibel</div>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Dukungan pembayaran digital dan transfer
                            manual.</p>
                    </div>
                </div>
            </div>

            <div class="landing-spotlight" data-aos="fade-up" data-aos-delay="150">
                <div class="landing-spotlight-panel">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-bold uppercase tracking-[0.24em] text-yellow-200">Operational View
                            </p>
                            <h2 class="mt-2 text-3xl font-bold">Servis terasa lebih premium dari halaman pertama.</h2>
                        </div>
                        <div class="rounded-[26px] bg-white/10 px-4 py-3 text-center backdrop-blur">
                            <div class="text-3xl font-bold text-yellow-300">24/7</div>
                            <div class="text-xs uppercase tracking-[0.24em] text-slate-200">Booking Access</div>
                        </div>
                    </div>

                    <div class="landing-stat-grid">
                        <div class="landing-stat-card" data-aos="fade-left" data-aos-delay="200">
                            <div class="text-xs uppercase tracking-[0.22em] text-slate-300">Queue</div>
                            <div class="mt-2 text-3xl font-bold text-white">Rapi</div>
                            <p class="mt-2 text-sm text-slate-200">Admin, mekanik, dan pelanggan melihat alur yang
                                sama.
                            </p>
                        </div>
                        <div class="landing-stat-card" data-aos="fade-left" data-aos-delay="300">
                            <div class="text-xs uppercase tracking-[0.22em] text-slate-300">Design</div>
                            <div class="mt-2 text-3xl font-bold text-white">Konsisten</div>
                            <p class="mt-2 text-sm text-slate-200">Komponen, ukuran, dan jarak tampil seragam.</p>
                        </div>
                        <div class="landing-stat-card" data-aos="fade-left" data-aos-delay="400">
                            <div class="text-xs uppercase tracking-[0.22em] text-slate-300">Palette</div>
                            <div class="mt-2 text-3xl font-bold text-white">Navy</div>
                            <p class="mt-2 text-sm text-slate-200">Warna utama biru tua dengan aksen kuning hangat.</p>
                        </div>
                        <div class="landing-stat-card" data-aos="fade-left" data-aos-delay="500">
                            <div class="text-xs uppercase tracking-[0.22em] text-slate-300">Experience</div>
                            <div class="mt-2 text-3xl font-bold text-white">Modern</div>
                            <p class="mt-2 text-sm text-slate-200">Lebih bersih, fokus, dan enak dipakai di desktop
                                maupun mobile.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @auth
        @if (Auth::user()->role === 'pelanggan')
            <section class="landing-section pt-12">
                <div class="landing-container">
                    <div class="tracking-card">
                        <div class="page-header-split">
                            <p class="page-kicker">Tracking Servis</p>
                            <h2 class="text-2xl font-bold text-slate-950">Status servis motor Anda</h2>
                            <p class="page-description">Pantau progres kendaraan langsung dari landing page tanpa harus
                                membuka halaman lain.</p>
                        </div>

                        @if ($booking)
                            <div
                                class="mt-6 grid gap-4 rounded-[28px] border border-slate-100 bg-slate-50/80 p-5 md:grid-cols-3">
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
                                        {{ \Carbon\Carbon::parse($booking->jadwal_booking)->format('d M Y, H:i') }}</div>
                                </div>
                            </div>

                            {{-- <div class="timeline-track">
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
                                    <div class="timeline-icon {{ $paymentStatus === 'lunas' ? 'is-done' : '' }}">4</div>
                                    <div class="timeline-label">Lunas</div>
                                </div>
                            </div> --}}
                            <div class="timeline-track">
                                <div class="timeline-progress" style="width: {{ $progressWidth }}; max-width: 75%;">
                                </div>

                                <div class="timeline-node">
                                    <div
                                        class="timeline-icon {{ in_array($bookingStatus, ['menunggu', 'Pending']) ? 'is-active' : 'is-done' }}">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="timeline-label">Antre</div>
                                </div>

                                <div class="timeline-node">
                                    <div
                                        class="timeline-icon {{ in_array($bookingStatus, ['diproses', 'Proses']) ? 'is-active' : (in_array($bookingStatus, ['selesai', 'Selesai']) || $paymentStatus === 'lunas' ? 'is-done' : '') }}">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                                        </svg>
                                    </div>
                                    <div class="timeline-label">Proses</div>
                                </div>

                                <div class="timeline-node">
                                    <div
                                        class="timeline-icon {{ in_array($bookingStatus, ['selesai', 'Selesai']) && $paymentStatus !== 'lunas' ? 'is-active' : ($paymentStatus === 'lunas' ? 'is-done' : '') }}">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="timeline-label">Selesai</div>
                                </div>

                                <div class="timeline-node">
                                    <div class="timeline-icon {{ $paymentStatus === 'lunas' ? 'is-done' : '' }}">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div class="timeline-label">Lunas</div>
                                </div>
                            </div>

                            <div
                                class="mt-6 rounded-[24px] border border-slate-100 bg-white p-5 text-sm leading-7 text-slate-600">
                                @if (in_array($bookingStatus, ['menunggu', 'Pending']))
                                    Kendaraan Anda sudah masuk antrean dan sedang menunggu giliran mekanik.
                                @elseif(in_array($bookingStatus, ['diproses', 'Proses']))
                                    Mekanik sedang mengerjakan kendaraan Anda. Progres akan diperbarui setelah servis
                                    selesai.
                                @elseif(in_array($bookingStatus, ['selesai', 'Selesai']) && $paymentStatus !== 'lunas')
                                    Servis telah selesai. Silakan lanjutkan pembayaran agar kendaraan siap dibawa pulang.
                                @elseif($paymentStatus === 'lunas')
                                    Pembayaran sudah lunas. Kendaraan siap diambil, terima kasih sudah menggunakan layanan
                                    kami.
                                @else
                                    Status booking belum tersedia atau sudah dibatalkan.
                                @endif
                            </div>
                        @else
                            <div class="empty-state mt-6">
                                <div class="empty-state-icon">P</div>
                                <h3 class="text-xl font-bold text-slate-950">Belum ada servis aktif</h3>
                                <p class="max-w-xl text-sm leading-6 text-slate-500">Anda belum memiliki booking aktif.
                                    Gunakan formulir di bawah untuk mengambil antrean servis.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif
    @endauth

    <section id="layanan" class="landing-section">
        <div class="landing-container">
            <div class="page-header">
                <div class="page-header-split">
                    <p class="page-kicker">Layanan Utama</p>
                    <h2 class="page-title">Bengkel yang terasa modern dari depan sampai operasional.</h2>
                    <p class="page-description">Kami merancang ulang tampilan agar pengalaman pelanggan dan staf
                        sama-sama terasa lebih premium, cepat, dan konsisten.</p>
                </div>
            </div>

            <div class="feature-grid mt-6">
                <div class="feature-card" data-aos="fade-left" data-aos-delay="150">
                    <div class="feature-icon">01</div>
                    <h3 class="mt-5 text-2xl font-bold text-slate-950">Servis Berkala</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-500">Perawatan rutin mesin, CVT, rem, dan komponen
                        penting lain agar motor tetap stabil dipakai harian.</p>
                </div>
                <div class="feature-card" data-aos="fade-left" data-aos-delay="250">
                    <div class="feature-icon">02</div>
                    <h3 class="mt-5 text-2xl font-bold text-slate-950">Ganti Oli dan Tune Up</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-500">Pilihan layanan cepat dengan harga jelas, form
                        rapi, dan pencatatan transaksi yang lebih profesional.</p>
                </div>
                <div class="feature-card" data-aos="fade-left" data-aos-delay="350">
                    <div class="feature-icon">03</div>
                    <h3 class="mt-5 text-2xl font-bold text-slate-950">Sparepart Berkualitas</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-500">Data stok, harga, dan transaksi part tampil lebih
                        konsisten sehingga lebih mudah dikelola oleh admin.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="booking" class="landing-section pt-0">
        <div class="landing-container" data-aos="fade-up" data-aos-delay="150">
            <div class="booking-shell">
                <div class="booking-sidebar">
                    <p class="page-kicker !text-yellow-200">Online Booking</p>
                    <h2 class="mt-4 text-4xl font-bold">Atur jadwal servis dengan tampilan yang lebih meyakinkan.</h2>
                    <p class="mt-4 text-base leading-8 text-slate-200">
                        Form booking kini tampil lebih bersih dan terstruktur. Pelanggan cukup isi data inti, lalu
                        sistem akan mengirim tiket antrean ke WhatsApp.
                    </p>

                    <div class="mt-8 space-y-4">
                        <div class="rounded-[24px] border border-white/10 bg-white/10 p-5 backdrop-blur"
                            data-aos="fade-up" data-aos-delay="250">
                            <div class="text-sm font-bold uppercase tracking-[0.24em] text-yellow-300">Cepat</div>
                            <p class="mt-2 text-sm leading-7 text-slate-200">Form ringkas, tidak membingungkan, dan
                                nyaman diisi dari desktop maupun ponsel.</p>
                        </div>
                        <div class="rounded-[24px] border border-white/10 bg-white/10 p-5 backdrop-blur"
                            data-aos="fade-up" data-aos-delay="350">
                            <div class="text-sm font-bold uppercase tracking-[0.24em] text-yellow-300">Terkonfirmasi
                            </div>
                            <p class="mt-2 text-sm leading-7 text-slate-200">Pelanggan menerima bukti antrean via
                                WhatsApp setelah data tersimpan.</p>
                        </div>
                    </div>
                </div>

                <div class="booking-form">
                    <div class="mb-6">
                        <p class="page-kicker">Form Booking</p>
                        <h3 class="mt-2 text-3xl font-bold text-slate-950">Ambil antrean servis sekarang</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-500">Masukkan data kendaraan dan keluhan utama.
                            Sistem akan langsung membuat booking baru untuk Anda.</p>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success mb-6">
                            <div class="font-black">OK</div>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger mb-6">
                            <div class="font-black">!</div>
                            <div>
                                <div class="font-bold">Form booking belum lengkap</div>
                                <ul class="mt-2 list-disc pl-5 text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('booking.public') }}" method="POST" class="form-shell">
                        @csrf

                        <div class="form-grid">
                            <div class="form-field form-field-full">
                                <label class="field-label" for="nama">Nama lengkap</label>
                                <input id="nama" type="text" name="nama" value="{{ old('nama') }}"
                                    class="form-input" placeholder="Masukkan nama lengkap" required>
                            </div>

                            <div class="form-field">
                                <label class="field-label" for="no_telp">No. WhatsApp</label>
                                <input id="no_telp" type="text" name="no_telp" value="{{ old('no_telp') }}"
                                    class="form-input" placeholder="0812xxxxxxx" required>
                            </div>

                            <div class="form-field">
                                <label class="field-label" for="jadwal_booking">Pilih waktu</label>
                                <input id="jadwal_booking" type="datetime-local" name="jadwal_booking"
                                    value="{{ old('jadwal_booking') }}" class="form-input" required>
                            </div>

                            <div class="form-field">
                                <label class="field-label" for="plat_nomor">Plat nomor</label>
                                <input id="plat_nomor" type="text" name="plat_nomor"
                                    value="{{ old('plat_nomor') }}" class="form-input" placeholder="N 1234 AB"
                                    required>
                            </div>

                            <div class="form-field">
                                <label class="field-label" for="tipe_motor">Tipe motor</label>
                                <input id="tipe_motor" type="text" name="tipe_motor"
                                    value="{{ old('tipe_motor') }}" class="form-input"
                                    placeholder="Vario 150 / NMAX" required>
                            </div>

                            <div class="form-field form-field-full">
                                <label class="field-label" for="keluhan">Keluhan</label>
                                <textarea id="keluhan" name="keluhan" class="form-textarea"
                                    placeholder="Jelaskan kebutuhan servis atau masalah kendaraan" required>{{ old('keluhan') }}</textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn-primary w-full">Ambil Antrean Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer class="border-t border-white/70 bg-white/70 py-10 backdrop-blur">
        <div class="landing-container flex flex-col gap-8 md:flex-row md:justify-between">

            <div class="flex flex-col gap-2 md:w-1/3">
                <div class="text-lg font-bold text-slate-950">Putra Jaya Motor</div>
                <div class="text-sm leading-relaxed text-slate-500">
                    Solusi perawatan dan perbaikan motor terpercaya. Melayani servis berkala dan penyediaan sparepart
                    dengan layanan transparan dan mekanik handal.
                </div>

                <div class="mt-4 text-sm font-medium text-slate-400">
                    &copy; {{ date('Y') }} Putra Jaya Motor. All Rights Reserved.
                </div>
            </div>

            <div class="flex flex-col gap-4 md:w-1/3">

                <div class="flex flex-wrap justify-center gap-5 text-sm">
                    <a href="#hero" class="font-semibold text-slate-600 transition hover:text-blue-600">Home</a>
                    <a href="#layanan" class="font-semibold text-slate-600 transition hover:text-blue-600">Layanan</a>
                    <a href="#booking" class="font-semibold text-slate-600 transition hover:text-blue-600">Booking</a>
                </div>
            </div>

            <div class="flex flex-col items-start gap-5 md:w-1/3 md:items-end">

                <div class="h-32 w-full overflow-hidden rounded-xl border border-slate-200 bg-slate-100 sm:w-72">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.5216345633857!2d106.8156689!3d-6.1946973!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTEnNDAuOSJTIDEwNsKwNDknMDMuNiJF!5e0!3m2!1sen!2sid!4v1630000000000!5m2!1sen!2sid"
                        width="100%" height="100%"
                        class="h-full w-full opacity-80 contrast-125 grayscale transition hover:opacity-100 hover:grayscale-0"
                        style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

                <div class="flex items-center gap-4">
                    <a href="#" class="text-slate-400 transition hover:text-[#1877F2]" aria-label="Facebook">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" />
                        </svg>
                    </a>
                    <a href="#" class="text-slate-400 transition hover:text-[#E4405F]" aria-label="Instagram">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="#" class="text-slate-400 transition hover:text-black" aria-label="X">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932 6.064-6.932zm-1.294 19.497h2.039L6.482 3.239H4.293L17.607 20.65z" />
                        </svg>
                    </a>
                    <a href="#" class="text-slate-400 transition hover:text-[#25D366]" aria-label="WhatsApp">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M12.002 2C6.478 2 2 6.478 2 12.002c0 1.758.455 3.42 1.32 4.908L2 22l5.244-1.322A9.957 9.957 0 0012.002 22c5.524 0 10-4.478 10-10.002C22.002 6.478 17.524 2 12.002 2zm.006 18.293c-1.468 0-2.905-.382-4.16-1.106l-.298-.173-3.088.777.828-3.003-.195-.306A8.158 8.158 0 013.82 11.996c0-4.524 3.682-8.204 8.208-8.204 4.526 0 8.206 3.68 8.206 8.204 0 4.526-3.68 8.206-8.206 8.206zm4.516-6.177c-.247-.123-1.462-.72-1.69-.803-.227-.082-.394-.123-.558.123-.165.247-.64 .803-.784.966-.145.164-.29.186-.537.062-.248-.124-1.045-.385-1.99-1.23-.735-.658-1.23-1.472-1.376-1.72-.144-.247-.015-.38.108-.504.11-.11.248-.288.372-.432.124-.143.165-.246.248-.41.082-.164.04-.308-.02-.432-.063-.124-.558-1.345-.765-1.84-.203-.485-.41-.42-.558-.427-.144-.008-.308-.008-.472-.008a.9.9 0 00-.65.308c-.227.247-.866.845-.866 2.062 0 1.218.887 2.395 1.01 2.56.124.164 1.745 2.663 4.226 3.733.59.255 1.05.408 1.41.522.593.188 1.134.16 1.56.097.476-.07 1.462-.598 1.668-1.176.206-.578.206-1.074.144-1.176-.062-.103-.227-.165-.474-.288z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                once: true, // Animasi cuma jalan sekali saat pertama kali di-scroll (biar nggak pusing)
                duration: 800, // Durasi animasi 0.8 detik (nggak terlalu cepat, nggak terlalu lambat)
                easing: 'ease-out-cubic', // Gerakan melambat halus di akhir
                offset: 100, // Jarak trigger animasi (100px sebelum elemen terlihat)
            });
        });
    </script>
</body>

</html>
