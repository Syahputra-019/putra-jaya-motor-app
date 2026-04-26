<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Putra Jaya Motor | Booking Servis Online</title>
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
                <div class="sidebar-brand-mark h-12 w-12 rounded-2xl text-sm">PJM</div>
                <div>
                    <div class="text-sm font-bold uppercase tracking-[0.24em] text-slate-950">Putra Jaya</div>
                    <div class="text-xs text-slate-500">Servis Motor Modern</div>
                </div>
            </a>

            <div class="hidden items-center gap-8 lg:flex">
                <a href="#hero" class="text-sm font-semibold text-slate-600 transition hover:text-slate-950">Home</a>
                <a href="#layanan" class="text-sm font-semibold text-slate-600 transition hover:text-slate-950">Layanan</a>
                <a href="#booking" class="text-sm font-semibold text-slate-600 transition hover:text-slate-950">Booking</a>
            </div>

            <div class="hidden items-center gap-3 lg:flex">
                @guest
                    <a href="{{ route('login') }}" class="btn-secondary">Login</a>
                    <a href="{{ route('register') }}" class="btn-accent">Register</a>
                @endguest

                @auth
                    @if (Auth::user()->role === 'admin')
                        <a href="{{ route('dashboard') }}" class="btn-primary">Dashboard</a>
                    @elseif (Auth::user()->role === 'mekanik')
                        <a href="{{ route('mekanik.jadwal') }}" class="btn-primary">Jadwal Servis</a>
                    @elseif (Auth::user()->role === 'pelanggan')
                        <a href="{{ route('booking.mine') }}" class="btn-primary">Booking Saya</a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-secondary">Logout</button>
                    </form>
                @endauth
            </div>

            <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white lg:hidden"
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
            </div>
        </div>
    </nav>

    <section id="hero" class="landing-hero">
        <div class="landing-container landing-hero-grid">
            <div>
                <span class="landing-tag">Blue and Gold Experience</span>
                <h1 class="landing-title">Booking servis motor jadi lebih cepat, modern, dan nyaman dipantau.</h1>
                <p class="landing-lead">
                    Putra Jaya Motor membantu pelanggan booking tanpa ribet sekaligus memberi tim bengkel tampilan kerja yang lebih rapi, konsisten, dan profesional.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="#booking" class="btn-primary">Booking Sekarang</a>
                    <a href="#layanan" class="btn-accent">Lihat Layanan</a>
                </div>

                <div class="mt-10 grid max-w-3xl grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="surface-card-tight">
                        <div class="page-kicker">Keunggulan</div>
                        <div class="mt-2 text-xl font-bold text-slate-950">Booking Online</div>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Ambil antrean tanpa harus datang lebih dulu ke bengkel.</p>
                    </div>
                    <div class="surface-card-tight">
                        <div class="page-kicker">Keunggulan</div>
                        <div class="mt-2 text-xl font-bold text-slate-950">Status Real-time</div>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Pantau progres pengerjaan kendaraan dari satu halaman.</p>
                    </div>
                    <div class="surface-card-tight">
                        <div class="page-kicker">Keunggulan</div>
                        <div class="mt-2 text-xl font-bold text-slate-950">Pembayaran Fleksibel</div>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Dukungan pembayaran digital dan transfer manual.</p>
                    </div>
                </div>
            </div>

            <div class="landing-spotlight">
                <div class="landing-spotlight-panel">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-bold uppercase tracking-[0.24em] text-yellow-200">Operational View</p>
                            <h2 class="mt-2 text-3xl font-bold">Servis terasa lebih premium dari halaman pertama.</h2>
                        </div>
                        <div class="rounded-[26px] bg-white/10 px-4 py-3 text-center backdrop-blur">
                            <div class="text-3xl font-bold text-yellow-300">24/7</div>
                            <div class="text-xs uppercase tracking-[0.24em] text-slate-200">Booking Access</div>
                        </div>
                    </div>

                    <div class="landing-stat-grid">
                        <div class="landing-stat-card">
                            <div class="text-xs uppercase tracking-[0.22em] text-slate-300">Queue</div>
                            <div class="mt-2 text-3xl font-bold text-white">Rapi</div>
                            <p class="mt-2 text-sm text-slate-200">Admin, mekanik, dan pelanggan melihat alur yang sama.</p>
                        </div>
                        <div class="landing-stat-card">
                            <div class="text-xs uppercase tracking-[0.22em] text-slate-300">Design</div>
                            <div class="mt-2 text-3xl font-bold text-white">Konsisten</div>
                            <p class="mt-2 text-sm text-slate-200">Komponen, ukuran, dan jarak tampil seragam.</p>
                        </div>
                        <div class="landing-stat-card">
                            <div class="text-xs uppercase tracking-[0.22em] text-slate-300">Palette</div>
                            <div class="mt-2 text-3xl font-bold text-white">Navy</div>
                            <p class="mt-2 text-sm text-slate-200">Warna utama biru tua dengan aksen kuning hangat.</p>
                        </div>
                        <div class="landing-stat-card">
                            <div class="text-xs uppercase tracking-[0.22em] text-slate-300">Experience</div>
                            <div class="mt-2 text-3xl font-bold text-white">Modern</div>
                            <p class="mt-2 text-sm text-slate-200">Lebih bersih, fokus, dan enak dipakai di desktop maupun mobile.</p>
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
                            <p class="page-description">Pantau progres kendaraan langsung dari landing page tanpa harus membuka halaman lain.</p>
                        </div>

                        @if ($booking)
                            <div class="mt-6 grid gap-4 rounded-[28px] border border-slate-100 bg-slate-50/80 p-5 md:grid-cols-3">
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
                                    <div class="mt-2 text-xl font-bold text-slate-950">{{ \Carbon\Carbon::parse($booking->jadwal_booking)->format('d M Y, H:i') }}</div>
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
                                @if (in_array($bookingStatus, ['menunggu', 'Pending']))
                                    Kendaraan Anda sudah masuk antrean dan sedang menunggu giliran mekanik.
                                @elseif(in_array($bookingStatus, ['diproses', 'Proses']))
                                    Mekanik sedang mengerjakan kendaraan Anda. Progres akan diperbarui setelah servis selesai.
                                @elseif(in_array($bookingStatus, ['selesai', 'Selesai']) && $paymentStatus !== 'lunas')
                                    Servis telah selesai. Silakan lanjutkan pembayaran agar kendaraan siap dibawa pulang.
                                @elseif($paymentStatus === 'lunas')
                                    Pembayaran sudah lunas. Kendaraan siap diambil, terima kasih sudah menggunakan layanan kami.
                                @else
                                    Status booking belum tersedia atau sudah dibatalkan.
                                @endif
                            </div>
                        @else
                            <div class="empty-state mt-6">
                                <div class="empty-state-icon">P</div>
                                <h3 class="text-xl font-bold text-slate-950">Belum ada servis aktif</h3>
                                <p class="max-w-xl text-sm leading-6 text-slate-500">Anda belum memiliki booking aktif. Gunakan formulir di bawah untuk mengambil antrean servis.</p>
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
                    <p class="page-description">Kami merancang ulang tampilan agar pengalaman pelanggan dan staf sama-sama terasa lebih premium, cepat, dan konsisten.</p>
                </div>
            </div>

            <div class="feature-grid mt-6">
                <div class="feature-card">
                    <div class="feature-icon">01</div>
                    <h3 class="mt-5 text-2xl font-bold text-slate-950">Servis Berkala</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-500">Perawatan rutin mesin, CVT, rem, dan komponen penting lain agar motor tetap stabil dipakai harian.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">02</div>
                    <h3 class="mt-5 text-2xl font-bold text-slate-950">Ganti Oli dan Tune Up</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-500">Pilihan layanan cepat dengan harga jelas, form rapi, dan pencatatan transaksi yang lebih profesional.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">03</div>
                    <h3 class="mt-5 text-2xl font-bold text-slate-950">Sparepart Berkualitas</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-500">Data stok, harga, dan transaksi part tampil lebih konsisten sehingga lebih mudah dikelola oleh admin.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="booking" class="landing-section pt-0">
        <div class="landing-container">
            <div class="booking-shell">
                <div class="booking-sidebar">
                    <p class="page-kicker !text-yellow-200">Online Booking</p>
                    <h2 class="mt-4 text-4xl font-bold">Atur jadwal servis dengan tampilan yang lebih meyakinkan.</h2>
                    <p class="mt-4 text-base leading-8 text-slate-200">
                        Form booking kini tampil lebih bersih dan terstruktur. Pelanggan cukup isi data inti, lalu sistem akan mengirim tiket antrean ke WhatsApp.
                    </p>

                    <div class="mt-8 space-y-4">
                        <div class="rounded-[24px] border border-white/10 bg-white/10 p-5 backdrop-blur">
                            <div class="text-sm font-bold uppercase tracking-[0.24em] text-yellow-300">Cepat</div>
                            <p class="mt-2 text-sm leading-7 text-slate-200">Form ringkas, tidak membingungkan, dan nyaman diisi dari desktop maupun ponsel.</p>
                        </div>
                        <div class="rounded-[24px] border border-white/10 bg-white/10 p-5 backdrop-blur">
                            <div class="text-sm font-bold uppercase tracking-[0.24em] text-yellow-300">Terkonfirmasi</div>
                            <p class="mt-2 text-sm leading-7 text-slate-200">Pelanggan menerima bukti antrean via WhatsApp setelah data tersimpan.</p>
                        </div>
                    </div>
                </div>

                <div class="booking-form">
                    <div class="mb-6">
                        <p class="page-kicker">Form Booking</p>
                        <h3 class="mt-2 text-3xl font-bold text-slate-950">Ambil antrean servis sekarang</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-500">Masukkan data kendaraan dan keluhan utama. Sistem akan langsung membuat booking baru untuk Anda.</p>
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
                                <input id="nama" type="text" name="nama" value="{{ old('nama') }}" class="form-input"
                                    placeholder="Masukkan nama lengkap" required>
                            </div>

                            <div class="form-field">
                                <label class="field-label" for="no_telp">No. WhatsApp</label>
                                <input id="no_telp" type="text" name="no_telp" value="{{ old('no_telp') }}" class="form-input"
                                    placeholder="0812xxxxxxx" required>
                            </div>

                            <div class="form-field">
                                <label class="field-label" for="jadwal_booking">Pilih waktu</label>
                                <input id="jadwal_booking" type="datetime-local" name="jadwal_booking"
                                    value="{{ old('jadwal_booking') }}" class="form-input" required>
                            </div>

                            <div class="form-field">
                                <label class="field-label" for="plat_nomor">Plat nomor</label>
                                <input id="plat_nomor" type="text" name="plat_nomor" value="{{ old('plat_nomor') }}" class="form-input"
                                    placeholder="N 1234 AB" required>
                            </div>

                            <div class="form-field">
                                <label class="field-label" for="tipe_motor">Tipe motor</label>
                                <input id="tipe_motor" type="text" name="tipe_motor" value="{{ old('tipe_motor') }}" class="form-input"
                                    placeholder="Vario 150 / NMAX" required>
                            </div>

                            <div class="form-field form-field-full">
                                <label class="field-label" for="keluhan">Keluhan</label>
                                <textarea id="keluhan" name="keluhan" class="form-textarea" placeholder="Jelaskan kebutuhan servis atau masalah kendaraan" required>{{ old('keluhan') }}</textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn-primary w-full">Ambil Antrean Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer class="border-t border-white/70 bg-white/70 py-10 backdrop-blur">
        <div class="landing-container flex flex-col gap-4 text-sm text-slate-500 md:flex-row md:items-center md:justify-between">
            <div>
                <div class="text-base font-bold text-slate-950">Putra Jaya Motor</div>
                <div>Blue and gold redesign untuk pengalaman bengkel yang lebih modern.</div>
            </div>
            <div class="flex flex-wrap gap-4">
                <a href="#hero" class="font-semibold text-slate-600 hover:text-slate-950">Home</a>
                <a href="#layanan" class="font-semibold text-slate-600 hover:text-slate-950">Layanan</a>
                <a href="#booking" class="font-semibold text-slate-600 hover:text-slate-950">Booking</a>
            </div>
        </div>
    </footer>
</body>

</html>
