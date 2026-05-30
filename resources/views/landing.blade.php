@extends('components.app')

@section('title', 'Booking Servis Online')

@section('styles')
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Custom Select2 Styling to match your UI */
        .select2-container--default .select2-selection--multiple {
            @apply w-full rounded-2xl border border-slate-200 bg-slate-50/80 px-2 py-1.5 text-sm transition duration-200 outline-none !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") !important;
            background-position: right 0.75rem center !important;
            background-repeat: no-repeat !important;
            background-size: 1.25em 1.25em !important;
            padding-right: 2.5rem !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            @apply border-blue-200 bg-white ring-4 ring-blue-50 !important;
        }

        /* Custom style untuk tag select2 */
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            @apply bg-blue-100 border-blue-200 text-blue-700 rounded-lg px-2 py-0.5 text-xs font-bold !important;
        }

        /* Hide scrollbar for carousel */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endsection

@section('content')

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
                    <a href="{{ route('booking.jadwal') }}" class="btn-accent">Lihat
                        Sisa Slot Hari Ini</a>
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

                            @if (($booking->status_konfirmasi ?? '') === 'menunggu' || ($booking->status_konfirmasi ?? '') === 'pending')
                                <div class="mt-4 rounded-[24px] border border-yellow-200 bg-yellow-50 p-5">
                                    <div class="flex items-start gap-4">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-yellow-200 text-xl">⚠️</div>
                                        <div>
                                            <h4 class="font-bold text-yellow-900">Persetujuan Harga Layanan</h4>
                                            <p class="mt-1 text-sm leading-6 text-yellow-800">Mekanik telah mengecek motor Anda untuk layanan 'Lainnya'. Silakan konfirmasi estimasi harga untuk melanjutkan pengerjaan.</p>
                                            @if($booking->catatan_mekanik ?? $booking->rekomendasi_servis ?? null)
                                                <div class="mt-3 rounded-xl bg-white/60 p-3 text-sm italic text-yellow-900">
                                                    "{{ $booking->catatan_mekanik ?? $booking->rekomendasi_servis }}"
                                                </div>
                                            @endif
                                            <div class="mt-4 flex flex-wrap gap-3">
                                                <form action="{{ route('booking.konfirmasi_rekomendasi', $booking->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status_konfirmasi" value="approved">
                                                    <button type="submit" class="btn-primary !py-2 text-sm">Setuju & Lanjut Servis</button>
                                                </form>
                                                <form action="{{ route('booking.konfirmasi_rekomendasi', $booking->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status_konfirmasi" value="rejected">
                                                    <button type="submit" class="btn-danger !py-2 text-sm">Tolak Rekomendasi</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif(($booking->status_konfirmasi ?? '') === 'approved')
                                <div class="mt-4 rounded-[20px] border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
                                    ✅ Anda telah menyetujui harga/rekomendasi mekanik. Pengerjaan dilanjutkan.
                                </div>
                            @elseif(($booking->status_konfirmasi ?? '') === 'rejected')
                                <div class="mt-4 rounded-[20px] border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                                    ❌ Anda menolak rekomendasi tambahan mekanik.
                                </div>
                            @endif

                            @if ($booking->transaksi)
                                <div class="mt-5 flex flex-wrap gap-3">
                                    @if (($booking->transaksi->status_pembayaran ?? null) === 'lunas')
                                        <a href="{{ route('transaksi.cetak', $booking->transaksi->id) }}"
                                            class="btn-secondary">Lihat Nota</a>
                                    @else
                                        <a href="{{ route('transaksi.bayar', $booking->transaksi->id) }}"
                                            class="btn-primary">Lanjutkan Pembayaran</a>
                                    @endif
                                </div>
                            @endif
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

                    @if (session('error_kuota'))
                        <div class="alert alert-danger mb-6 border-red-200 bg-red-50 text-red-700">
                            <div class="font-black">!</div>
                            <div>{{ session('error_kuota') }}</div>
                        </div>
                    @endif

                    <form action="{{ route('booking.public') }}" method="POST" class="form-shell">
                        @csrf

                        <div class="form-grid">
                            <div class="form-field form-field-full">
                                <label class="field-label" for="nama">Nama lengkap</label>
                                <input id="nama" type="text" name="nama"
                                    value="{{ auth()->check() ? auth()->user()->name : old('nama') }}" class="form-input"
                                    placeholder="Masukkan nama lengkap" required>
                            </div>

                            <div class="form-field">
                                <label class="field-label" for="no_telp">No. Telp / WhatsApp</label>
                                <input id="no_telp" type="text" name="no_telp"
                                    value="{{ $pelanggan && !str_starts_with($pelanggan->no_telp, 'pending-') ? $pelanggan->no_telp : old('no_telp') }}"
                                    class="form-input" placeholder="0812xxxxxxx" required>
                            </div>

                            <div class="form-field">
                                <label class="field-label" for="plat_nomor">Plat Nomor Kendaraan</label>
                                <input id="plat_nomor" type="text" name="plat_nomor" value="{{ old('plat_nomor') }}"
                                    class="form-input" placeholder="N 1234 AB" required>
                            </div>

                            <div class="form-field">
                                <label class="field-label" for="tipe_motor">Tipe / Merk Motor</label>
                                <input id="tipe_motor" type="text" name="tipe_motor" value="{{ old('tipe_motor') }}"
                                    class="form-input" placeholder="Vario 150 / NMAX" required>
                            </div>

                            <div class="form-field">
                                <label class="field-label" for="jadwal_booking">Pilih Waktu Booking</label>
                                <input id="jadwal_booking" type="datetime-local" name="jadwal_booking"
                                    value="{{ request('tanggal') ? request('tanggal') . 'T09:00' : old('jadwal_booking') }}"
                                    class="form-input" required>
                            </div>

                            <div class="form-field form-field-full">
                                <label class="field-label mb-2">Pilihan Layanan Utama (Bisa pilih lebih dari satu)</label>
                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                                    @foreach ($services as $layanan)
                                        <label
                                            class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 transition hover:bg-slate-100">
                                            <input type="checkbox" name="kategori_servis[]"
                                                value="{{ $layanan->nama_service }}" data-price="{{ $layanan->harga }}"
                                                class="service-checkbox h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                            <span class="text-sm font-medium text-slate-700">{{ $layanan->nama_service }}
                                                (Rp {{ number_format($layanan->harga, 0, ',', '.') }})
                                            </span>
                                        </label>
                                    @endforeach

                                    <!-- Opsi Lainnya -->
                                    <label
                                        class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 transition hover:bg-slate-100">
                                        <input type="checkbox" id="checkbox-lainnya" name="kategori_servis[]"
                                            value="Lainnya" data-price="0"
                                            class="service-checkbox h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                        <span class="text-sm font-medium text-slate-700">Lainnya</span>
                                    </label>
                                </div>

                                <div id="input-lainnya-container" class="mt-3 hidden">
                                    <input type="text" id="layanan_lainnya" name="layanan_lainnya"
                                        value="{{ old('layanan_lainnya') }}" class="form-input"
                                        placeholder="Sebutkan layanan lainnya yang Anda butuhkan (Contoh: Ganti aki, dll)">
                                </div>
                            </div>

                            <div class="form-field form-field-full">
                                <label class="field-label" for="sparepart_diminta">Request Ganti Sparepart
                                    (Opsional)</label>
                                <select id="sparepart_diminta" name="sparepart_diminta[]" class="select2-multiple"
                                    multiple="multiple">
                                    @foreach ($spareparts as $s)
                                        <option value="{{ $s->nama_sparepart }}" data-price="{{ $s->harga }}">
                                            {{ $s->nama_sparepart }} (Stok: {{ $s->stok }}) - Rp
                                            {{ number_format($s->harga, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-2 text-xs italic text-slate-400">*Pilih sparepart jika Anda ingin mekanik
                                    menyiapkannya terlebih dahulu.</p>
                            </div>

                            <div class="form-field form-field-full">
                                <label class="field-label" for="keluhan">Keluhan Tambahan (Opsional)</label>
                                <textarea id="keluhan" name="keluhan" class="form-textarea"
                                    placeholder="Jelaskan masalah kendaraan atau permintaan khusus Anda">{{ old('keluhan') }}</textarea>
                            </div>

                            <div class="form-field form-field-full">
                                <div class="rounded-xl border border-blue-200 bg-blue-50/50 p-5">
                                    <p class="text-sm font-bold text-slate-600">Estimasi Biaya</p>
                                    <p class="mt-1 text-3xl font-bold text-blue-700" id="estimasi-biaya">Rp 0</p>
                                    <p class="mt-2 text-xs text-slate-500">*Ini hanya estimasi kasar berdasarkan layanan &
                                        sparepart yang dipilih. Harga akhir bisa menyesuaikan kondisi aktual kendaraan saat
                                        diperiksa <mekanik>
                                        <admin></admin>.</p>
                                    <div id="alert-lainnya"
                                        class="mt-3 hidden rounded-lg border border-yellow-200 bg-yellow-50 p-3 text-sm text-yellow-800 transition-all">
                                        ⚠️ <strong>Peringatan:</strong> Karena Anda memilih layanan 'Lainnya', estimasi ini
                                        belum termasuk biaya pengerjaan/sparepart untuk layanan tersebut. Mekanik/Admin kami akan
                                        mengonfirmasi harga aslinya nanti.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-primary w-full">Ambil Antrean Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- Section Testimonial --}}
    @if ($testimonials->count() > 0)
        <section id="testimonial" class="landing-section py-16">
            <div class="container mx-auto px-4">
                <div class="mb-12 flex flex-col items-center justify-between gap-6 md:flex-row">
                    <div class="text-center md:text-left">
                        <h2 class="text-3xl font-bold text-slate-900">Apa Kata Pelanggan Kami</h2>
                        <p class="mt-2 text-slate-500">Pengalaman nyata dari pelanggan yang telah mempercayakan motornya
                            kepada kami.</p>
                    </div>
                    <div class="flex gap-3">
                        <button id="btn-prev-testimonial" aria-label="Previous"
                            class="flex h-12 w-12 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:bg-blue-50 hover:text-blue-600 hover:shadow-md focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button id="btn-next-testimonial" aria-label="Next"
                            class="flex h-12 w-12 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:bg-blue-50 hover:text-blue-600 hover:shadow-md focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="relative -mx-4 px-4 sm:mx-0 sm:px-0">
                    <div id="testimonial-container"
                        class="hide-scrollbar flex snap-x snap-mandatory gap-6 overflow-x-auto scroll-smooth pb-8 pt-4">
                        @foreach ($testimonials as $testi)
                            <div
                                class="flex w-[85%] shrink-0 snap-center flex-col justify-between rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-100 transition duration-300 hover:-translate-y-1 hover:shadow-xl sm:w-[calc(50%-0.75rem)] lg:w-[calc(33.333333%-1rem)]">
                                <div>
                                    {{-- Rating --}}
                                    <div class="mb-4 flex items-center gap-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $testi->rating)
                                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5 text-slate-200" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>

                                    {{-- Isi Testimonial --}}
                                    <p class="mb-8 text-sm italic leading-relaxed text-slate-600">
                                        "{{ $testi->isi_testimonial }}"
                                    </p>
                                </div>

                                {{-- User Info --}}
                                <div class="mt-auto flex items-center gap-3 border-t border-slate-100 pt-5">
                                    {{-- Inisial / Foto --}}
                                    <div
                                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-600">
                                        @if ($testi->user && $testi->user->foto)
                                            <img src="{{ asset('storage/' . $testi->user->foto) }}" alt="User Image"
                                                class="h-full w-full rounded-full object-cover">
                                        @else
                                            {{ strtoupper(substr($testi->user->name ?? 'A', 0, 1)) }}
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="truncate text-sm font-semibold text-slate-900">
                                            {{ $testi->user->name ?? 'Anonim' }}</h4>
                                        <p class="text-xs text-slate-500">Pelanggan</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

@endsection

@section('scripts')
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                once: true,
                duration: 800,
                easing: 'ease-out-cubic',
                offset: 100,
            });

            // Inisialisasi Select2
            $('.select2-multiple').select2({
                placeholder: "Cari dan pilih sparepart...",
                allowClear: true,
                width: '100%'
            });

            // Kalkulator Estimasi Biaya
            const estimasiBiayaEl = document.getElementById('estimasi-biaya');
            const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
            const sparepartSelect = $('#sparepart_diminta');

            function calculateEstimasi() {
                let total = 0;
                let hasLainnya = false;

                serviceCheckboxes.forEach(cb => {
                    if (cb.checked) {
                        total += parseFloat(cb.dataset.price) || 0;
                        if (cb.value === 'Lainnya') {
                            hasLainnya = true;
                        }
                    }
                });

                sparepartSelect.find(':selected').each(function() {
                    total += parseFloat($(this).attr('data-price')) || 0;
                });

                if (hasLainnya) {
                    estimasiBiayaEl.textContent = 'Rp ' + total.toLocaleString('id-ID') + ' +';
                    document.getElementById('alert-lainnya').classList.remove('hidden');
                } else {
                    estimasiBiayaEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
                    document.getElementById('alert-lainnya').classList.add('hidden');
                }
            }

            serviceCheckboxes.forEach(cb => cb.addEventListener('change', calculateEstimasi));
            sparepartSelect.on('change', calculateEstimasi);

            // Logika Checkbox Lainnya
            const checkboxLainnya = document.getElementById('checkbox-lainnya');
            const inputLainnyaContainer = document.getElementById('input-lainnya-container');
            const inputLayananLainnya = document.getElementById('layanan_lainnya');

            function toggleInputLainnya() {
                if (checkboxLainnya.checked) {
                    inputLainnyaContainer.classList.remove('hidden');
                    inputLayananLainnya.setAttribute('required', 'required');
                } else {
                    inputLainnyaContainer.classList.add('hidden');
                    inputLayananLainnya.removeAttribute('required');
                }
            }

            checkboxLainnya.addEventListener('change', toggleInputLainnya);
            toggleInputLainnya(); // Panggil saat awal dimuat (menjaga old input)

            // Logika Filter Sparepart Berdasarkan Jasa yang Dipilih
            const allSparepartOptions = $('#sparepart_diminta option').clone();

            function updateSparepartDropdown() {
                let keywords = [];
                // Kata kunci yang mungkin terkait dengan layanan tertentu
                const keywordMap = {
                    'oli': ['oli', 'oil', 'pelumas'],
                    'kampas': ['kampas', 'rem', 'pad', 'shoe', 'disc'],
                    'rem': ['kampas', 'rem', 'pad', 'shoe', 'minyak', 'cakram', 'disc'],
                    'ban': ['ban', 'tire', 'pentil', 'tubeless'],
                    'busi': ['busi', 'spark'],
                    'cvt': ['cvt', 'v-belt', 'roller', 'ganda', 'vbelt', 'belt'],
                    'aki': ['aki', 'accu', 'baterai'],
                    'filter': ['filter', 'saringan'],
                    'rantai': ['rantai', 'gear', 'gir'],
                    'gear': ['rantai', 'gear', 'gir'],
                    'lampu': ['lampu', 'bohlam', 'bulb', 'led']
                };

                // Kumpulkan semua kata kunci dari jasa yang dicentang
                $('.service-checkbox:checked').each(function() {
                    if ($(this).val() === 'Lainnya') return;

                    let serviceName = $(this).val().toLowerCase();

                    // Cek map manual
                    for (let key in keywordMap) {
                        if (serviceName.includes(key)) {
                            keywords = keywords.concat(keywordMap[key]);
                        }
                    }

                    // Ambil kata-kata dari nama layanan juga (hindari kata umum)
                    let words = serviceName.replace(/[^a-zA-Z0-9\s]/g, '').split(' ')
                        .filter(w => w.length > 2 && !['ganti', 'pasang', 'servis', 'service', 'perbaikan',
                            'benerin', 'cek', 'dan', 'atau', 'untuk'
                        ].includes(w));
                    keywords = keywords.concat(words);
                });

                // Hapus duplikat
                keywords = [...new Set(keywords)];

                let selectedValues = $('#sparepart_diminta').val() || [];
                $('#sparepart_diminta').empty();

                if (keywords.length === 0) {
                    $('#sparepart_diminta').append(allSparepartOptions.clone());
                } else {
                    let optgroupRekomendasi = $('<optgroup label="Sesuai Layanan Pilihan"></optgroup>');
                    let optgroupLainnya = $('<optgroup label="Sparepart Lainnya"></optgroup>');

                    allSparepartOptions.each(function() {
                        let spName = $(this).text().toLowerCase();
                        let matches = keywords.some(kw => spName.includes(kw));

                        if (matches) {
                            optgroupRekomendasi.append($(this).clone());
                        } else {
                            optgroupLainnya.append($(this).clone());
                        }
                    });

                    // Tambahkan ke dropdown
                    if (optgroupRekomendasi.children().length > 0) {
                        $('#sparepart_diminta').append(optgroupRekomendasi);
                        $('#sparepart_diminta').append(optgroupLainnya);
                    } else {
                        $('#sparepart_diminta').append(allSparepartOptions.clone());
                    }
                }

                // Kembalikan pilihan sebelumnya jika masih ada dan update Select2 UI
                $('#sparepart_diminta').val(selectedValues).trigger('change.select2');
            }

            $('.service-checkbox').on('change', updateSparepartDropdown);
            // Panggil sekali di awal untuk antisipasi old input (jika form validasi error)
            updateSparepartDropdown();

            // Carousel / Slider Testimonial Vanilla JS
            const testimonialContainer = document.getElementById('testimonial-container');
            const btnPrevTestimonial = document.getElementById('btn-prev-testimonial');
            const btnNextTestimonial = document.getElementById('btn-next-testimonial');

            if (testimonialContainer && btnPrevTestimonial && btnNextTestimonial) {
                btnPrevTestimonial.addEventListener('click', () => {
                    // Scroll sejauh lebar 1 elemen pertama beserta gap-nya (24px = gap-6)
                    const scrollAmount = testimonialContainer.firstElementChild.offsetWidth + 24;
                    testimonialContainer.scrollBy({
                        left: -scrollAmount,
                        behavior: 'smooth'
                    });
                });

                btnNextTestimonial.addEventListener('click', () => {
                    const scrollAmount = testimonialContainer.firstElementChild.offsetWidth + 24;
                    testimonialContainer.scrollBy({
                        left: scrollAmount,
                        behavior: 'smooth'
                    });
                });
            }
        });
    </script>
@endsection
