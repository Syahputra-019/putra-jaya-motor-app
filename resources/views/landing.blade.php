<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Putra Jaya Motor | Profesional Workshop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-white text-slate-900">

    <nav class="fixed z-50 w-full border-b border-slate-100 bg-white/80 backdrop-blur-md">
        <div class="container mx-auto flex items-center justify-between px-6 py-4">
            <div class="flex items-center gap-2">
                <div class="rounded-lg bg-blue-600 p-2 text-white">
                    <i class="fas fa-motorcycle text-xl"></i>
                </div>
                <span class="text-xl font-extrabold uppercase tracking-tighter">Putra<span
                        class="text-blue-600">Jaya</span></span>
            </div>

            <div class="hidden items-center gap-8 text-sm font-semibold md:flex">
                <a href="#" class="transition hover:text-blue-600">Home</a>
                <a href="#layanan" class="transition hover:text-blue-600">Layanan</a>
                <a href="#booking" class="transition hover:text-blue-600">Booking</a>
                <div class="h-6 w-[1px] bg-slate-200"></div>
                @guest
                    <a href="{{ route('login') }}" class="text-slate-600 transition hover:text-blue-600">Login</a>
                    <a href="{{ route('register') }}"
                        class="rounded-full bg-slate-900 px-5 py-2.5 text-white shadow-lg shadow-blue-200 transition hover:bg-blue-600">Register</a>
                @endguest

                @auth
                    <div class="group relative">
                        <button
                            class="flex items-center gap-2 rounded-full bg-slate-100 px-4 py-2 transition hover:bg-slate-200">
                            <div
                                class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-600 text-[10px] text-white">
                                <i class="fas fa-user"></i>
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down ml-1 text-[10px]"></i>
                        </button>

                        <div
                            class="absolute right-0 mt-2 hidden w-48 rounded-2xl border border-slate-100 bg-white py-2 shadow-xl transition-all group-hover:block">

                            @if (Auth::user()->role === 'admin' || Auth::user()->role === 'mekanik')
                                <a href="{{ route('dashboard') }}"
                                    class="block px-4 py-2 text-xs font-bold text-slate-700 transition hover:bg-blue-50 hover:text-blue-600">
                                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard Admin
                                </a>
                            @endif

                            @if (Auth::user()->role === 'pelanggan')
                                <a href="{{ route('booking.mine') }}"
                                    class="block px-4 py-2 text-xs font-bold text-slate-700 transition hover:bg-blue-50 hover:text-blue-600">
                                    <i class="fas fa-motorcycle mr-2"></i> Booking Saya
                                </a>
                            @endif

                            <hr class="my-2 border-slate-50">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full px-4 py-2 text-left text-xs font-bold text-red-600 transition hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>

            <button class="text-2xl md:hidden"><i class="fas fa-bars"></i></button>
        </div>
    </nav>

    <section class="relative overflow-hidden pb-20 pt-32 lg:pb-32 lg:pt-48">
        <div class="container mx-auto flex flex-col items-center gap-12 px-6 lg:flex-row">
            <div class="z-10 text-center lg:w-1/2 lg:text-left">
                <span
                    class="mb-6 inline-block rounded-full bg-blue-50 px-3 py-1 text-xs font-bold uppercase tracking-widest text-blue-600">
                    Mekanik Berpengalaman & Terpercaya
                </span>
                <h1 class="mb-6 text-5xl font-extrabold leading-[1.1] text-slate-900 lg:text-7xl">
                    Rawat Motor Kamu <br> Tanpa <span class="italic text-blue-600">Ribet.</span>
                </h1>
                <p class="mx-auto mb-10 max-w-lg text-lg text-slate-500 lg:mx-0">
                    Solusi servis motor modern di Putra Jaya Motor. Booking jadwal servis secara online dan pantau
                    riwayat kendaraan kamu dengan mudah.
                </p>
                <div class="flex flex-col justify-center gap-4 sm:flex-row lg:justify-start">
                    <a href="#booking"
                        class="rounded-2xl bg-blue-600 px-8 py-4 text-lg font-bold text-white shadow-2xl shadow-blue-300 transition hover:bg-blue-700">
                        Mulai Booking <i class="fas fa-calendar-check ml-2"></i>
                    </a>
                    <a href="#layanan"
                        class="rounded-2xl border border-slate-200 bg-white px-8 py-4 text-lg font-bold text-slate-900 transition hover:bg-slate-50">
                        Lihat Layanan
                    </a>
                </div>
            </div>
            <div class="relative lg:w-1/2">
                <div
                    class="absolute left-1/2 top-1/2 -z-10 h-[120%] w-[120%] -translate-x-1/2 -translate-y-1/2 rounded-full bg-blue-100 opacity-50 blur-[120px]">
                </div>
                <img src="https://images.unsplash.com/photo-1599819811279-d5ad9cccf838?q=80&w=1000&auto=format&fit=crop"
                    class="rotate-2 rounded-[40px] shadow-2xl transition-transform duration-500 hover:rotate-0"
                    alt="Motorcycles">
            </div>
        </div>
    </section>

    @auth
        @if (Auth::user()->role === 'pelanggan')
            <section id="tracking-servis" class="relative z-20 -mt-10 mb-20">
                <div class="container mx-auto px-6 lg:max-w-4xl">
                    <div class="rounded-[32px] border border-slate-100 bg-white p-8 shadow-2xl shadow-blue-900/5 lg:p-12">
                        <div class="mb-8 text-center">
                            <h2 class="text-2xl font-extrabold text-slate-900">Status Servis Motor Kamu</h2>
                            <p class="text-slate-500">Pantau progres pengerjaan kendaraan secara real-time</p>
                        </div>

                        @if ($booking)
                            <div class="mb-8 flex items-center justify-between border-b border-slate-100 pb-6">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Kendaraan</p>
                                    <h3 class="text-xl font-extrabold text-slate-900">{{ $booking->tipe_motor }}</h3>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Plat Nomor</p>
                                    <span
                                        class="rounded-lg bg-slate-100 px-4 py-2 font-mono text-sm font-bold tracking-widest text-slate-900">
                                        {{ $booking->plat_nomor }}
                                    </span>
                                </div>
                            </div>

                            <div class="relative mb-12 mt-8">
                                <div class="relative flex items-center justify-between px-2 md:px-8">
                                    <div class="absolute left-0 top-6 h-1.5 w-full rounded-full bg-slate-100"></div>

                                    @php
                                        $width = '0%';
                                        if (in_array($booking->status, ['diproses', 'Proses'])) {
                                            $width = '33%';
                                        }
                                        if (in_array($booking->status, ['selesai', 'Selesai'])) {
                                            $width = '66%';
                                        }
                                        if ($booking->status_pembayaran == 'lunas') {
                                            $width = '100%';
                                        }
                                    @endphp
                                    <div class="absolute left-0 top-6 h-1.5 rounded-full bg-blue-600 transition-all duration-1000 ease-out"
                                        style="width: {{ $width }}"></div>

                                    <div class="relative z-10 flex flex-col items-center">
                                        <div
                                            class="{{ in_array($booking->status, ['menunggu', 'Pending']) ? 'border-blue-600 bg-white text-blue-600 shadow-lg shadow-blue-200' : 'border-blue-600 bg-blue-600 text-white' }} flex h-14 w-14 items-center justify-center rounded-2xl border-4 transition-all">
                                            <i class="fas fa-clipboard-list text-xl"></i>
                                        </div>
                                        <p class="mt-4 text-xs font-bold uppercase tracking-wider md:text-sm">Antre</p>
                                    </div>

                                    <div class="relative z-10 flex flex-col items-center">
                                        <div
                                            class="{{ in_array($booking->status, ['diproses', 'Proses']) ? 'border-blue-600 bg-white text-blue-600 shadow-lg shadow-blue-200' : (in_array($booking->status, ['selesai', 'Selesai']) || $booking->status_pembayaran == 'lunas' ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-200 bg-slate-100 text-slate-400') }} flex h-14 w-14 items-center justify-center rounded-2xl border-4 transition-all">
                                            <i class="fas fa-tools text-xl"></i>
                                        </div>
                                        <p
                                            class="{{ in_array($booking->status, ['diproses', 'Proses', 'selesai', 'Selesai']) || $booking->status_pembayaran == 'lunas' ? 'text-blue-600' : 'text-slate-400' }} mt-4 text-xs font-bold uppercase tracking-wider md:text-sm">
                                            Dikerjakan</p>
                                    </div>

                                    <div class="relative z-10 flex flex-col items-center">
                                        <div
                                            class="{{ in_array($booking->status, ['selesai', 'Selesai']) && $booking->status_pembayaran != 'lunas' ? 'border-blue-600 bg-white text-blue-600 shadow-lg shadow-blue-200' : ($booking->status_pembayaran == 'lunas' ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-200 bg-slate-100 text-slate-400') }} flex h-14 w-14 items-center justify-center rounded-2xl border-4 transition-all">
                                            <i class="fas fa-check-double text-xl"></i>
                                        </div>
                                        <p
                                            class="{{ in_array($booking->status, ['selesai', 'Selesai']) || $booking->status_pembayaran == 'lunas' ? 'text-blue-600' : 'text-slate-400' }} mt-4 text-xs font-bold uppercase tracking-wider md:text-sm">
                                            Selesai</p>
                                    </div>

                                    <div class="relative z-10 flex flex-col items-center">
                                        <div
                                            class="{{ $booking->status_pembayaran == 'lunas' ? 'border-green-500 bg-green-500 text-white shadow-lg shadow-green-200' : 'border-slate-200 bg-slate-100 text-slate-400' }} flex h-14 w-14 items-center justify-center rounded-2xl border-4 transition-all">
                                            <i class="fas fa-receipt text-xl"></i>
                                        </div>
                                        <p
                                            class="{{ $booking->status_pembayaran == 'lunas' ? 'text-green-600' : 'text-slate-400' }} mt-4 text-xs font-bold uppercase tracking-wider md:text-sm">
                                            Lunas</p>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-blue-100 bg-blue-50/50 p-5 text-center">
                                @if (in_array($booking->status, ['menunggu', 'Pending']))
                                    <p class="text-sm text-blue-800"><i
                                            class="fas fa-info-circle mr-2"></i><strong>Menunggu Antrean:</strong>
                                        Kendaraan kamu sudah terdaftar dan menunggu giliran mekanik.</p>
                                @elseif(in_array($booking->status, ['diproses', 'Proses']))
                                    <p class="text-sm text-blue-800"><i
                                            class="fas fa-spinner fa-spin mr-2"></i><strong>Sedang Dikerjakan:</strong>
                                        Mekanik kami sedang melakukan perawatan pada motor kamu.</p>
                                @elseif(in_array($booking->status, ['selesai', 'Selesai']) && $booking->status_pembayaran != 'lunas')
                                    <p class="text-sm font-bold text-blue-800"><i class="fas fa-bell mr-2"></i>Servis
                                        Selesai! Silakan menuju kasir atau lakukan pembayaran online.</p>
                                @elseif($booking->status_pembayaran == 'lunas')
                                    <p class="text-sm font-bold text-green-700"><i
                                            class="fas fa-check-circle mr-2"></i>Pembayaran Lunas! Kendaraan sudah bisa
                                        dibawa pulang. Terima kasih!</p>
                                @else
                                    <p class="text-sm font-bold text-red-600"><i
                                            class="fas fa-exclamation-triangle mr-2"></i>Status pesanan dibatalkan atau
                                        tidak ditemukan.</p>
                                @endif
                            </div>
                        @else
                            <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 py-12 text-center">
                                <div
                                    class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-200 text-slate-400">
                                    <i class="fas fa-motorcycle text-2xl"></i>
                                </div>
                                <h3 class="mb-2 text-lg font-extrabold text-slate-900">Belum Ada Servis Aktif</h3>
                                <p class="text-slate-500">Kamu belum memiliki jadwal servis saat ini. Yuk ambil antrean di
                                    bawah!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif
    @endauth

    <section id="layanan" class="bg-slate-50 py-24">
        <div class="container mx-auto px-6">
            <div class="mb-16 text-center">
                <h2 class="mb-4 text-3xl font-extrabold md:text-4xl">Layanan Unggulan Kami</h2>
                <div class="mx-auto h-1.5 w-20 rounded-full bg-blue-600"></div>
            </div>
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <div class="group rounded-[32px] border border-slate-100 bg-white p-10 transition hover:shadow-2xl">
                    <div
                        class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 transition group-hover:bg-blue-600 group-hover:text-white">
                        <i class="fas fa-tools text-2xl"></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold">Servis Berkala</h3>
                    <p class="leading-relaxed text-slate-500">Perawatan rutin mesin, pembersihan CVT, dan pengecekan
                        menyeluruh agar motor tetap prima.</p>
                </div>
                <div class="group rounded-[32px] border border-slate-100 bg-white p-10 transition hover:shadow-2xl">
                    <div
                        class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 transition group-hover:bg-blue-600 group-hover:text-white">
                        <i class="fas fa-oil-can text-2xl"></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold">Ganti Oli</h3>
                    <p class="leading-relaxed text-slate-500">Berbagai pilihan oli original berkualitas tinggi untuk
                        melumasi mesin motor kamu secara maksimal.</p>
                </div>
                <div class="group rounded-[32px] border border-slate-100 bg-white p-10 transition hover:shadow-2xl">
                    <div
                        class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 transition group-hover:bg-blue-600 group-hover:text-white">
                        <i class="fas fa-tire text-2xl"></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold">Suku Cadang</h3>
                    <p class="leading-relaxed text-slate-500">Penyediaan sparepart asli (OEM) dengan garansi pemasangan
                        dari mekanik profesional.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="booking" class="py-24">
        <div class="container mx-auto px-6">
            <div class="flex flex-col overflow-hidden rounded-[48px] bg-slate-900 shadow-2xl lg:flex-row">
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 p-12 text-white lg:w-5/12 lg:p-16">
                    <h2 class="mb-8 text-4xl font-extrabold">Siap Beraksi? <br> Atur Jadwal Kamu.</h2>
                    <p class="mb-10 text-lg text-blue-100">Hanya butuh 1 menit untuk melakukan booking antrean. Kamu
                        akan langsung menerima bukti antrean via WhatsApp.</p>

                    <ul class="space-y-6">
                        <li class="flex items-start gap-4">
                            <div class="rounded-lg bg-white/20 p-2"><i class="fas fa-clock"></i></div>
                            <div>
                                <h4 class="font-bold">Hemat Waktu</h4>
                                <p class="text-sm text-blue-100">Datang sesuai jadwal, langsung dikerjakan.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="rounded-lg bg-white/20 p-2"><i class="fas fa-mobile-alt"></i></div>
                            <div>
                                <h4 class="font-bold">Notifikasi WA</h4>
                                <p class="text-sm text-blue-100">Bukti booking masuk langsung ke smartphone.</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="bg-white p-12 lg:w-7/12 lg:p-16">
                    @if (session('success'))
                        <div
                            class="mb-8 flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 px-6 py-4 text-green-700">
                            <i class="fas fa-check-circle text-xl"></i>
                            <span class="font-semibold">{{ session('success') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('booking.public') }}" method="POST"
                        class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        @csrf
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-bold">Nama Lengkap</label>
                            <input type="text" name="nama"
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 outline-none transition focus:ring-2 focus:ring-blue-500"
                                placeholder="Masukkan nama kamu" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-bold">No. WhatsApp</label>
                            <input type="text" name="no_telp"
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 outline-none transition focus:ring-2 focus:ring-blue-500"
                                placeholder="0812xxxx" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-bold">Pilih Waktu</label>
                            <input type="datetime-local" name="jadwal_booking"
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 outline-none transition focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-bold">Plat Nomor</label>
                            <input type="text" name="plat_nomor"
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 outline-none transition focus:ring-2 focus:ring-blue-500"
                                placeholder="N 1234 AB" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-bold">Tipe Motor</label>
                            <input type="text" name="tipe_motor"
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 outline-none transition focus:ring-2 focus:ring-blue-500"
                                placeholder="Vario 150 / NMAX" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-bold">Keluhan</label>
                            <textarea name="keluhan" rows="3"
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 outline-none transition focus:ring-2 focus:ring-blue-500"
                                placeholder="Apa yang ingin diservis?" required></textarea>
                        </div>
                        <div class="pt-4 md:col-span-2">
                            <button type="submit"
                                class="w-full rounded-2xl bg-slate-900 py-5 text-sm font-bold uppercase tracking-widest text-white shadow-xl transition hover:bg-blue-600">
                                Ambil Antrean Sekarang <i class="fab fa-whatsapp ml-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer class="border-t border-slate-100 bg-white py-16">
        <div class="container mx-auto px-6">
            <div class="flex flex-col items-center justify-between gap-8 md:flex-row">
                <div>
                    <div class="mb-4 flex items-center justify-center gap-2 md:justify-start">
                        <div class="rounded-lg bg-slate-900 p-2 text-white">
                            <i class="fas fa-motorcycle"></i>
                        </div>
                        <span class="text-xl font-extrabold uppercase">Putra<span
                                class="text-blue-600">Jaya</span></span>
                    </div>
                    <p class="text-sm text-slate-500">Bengkel Motor Profesional. Layanan cepat, harga bersahabat.</p>
                </div>
                <div class="flex gap-6 text-sm font-bold">
                    <a href="#" class="transition hover:text-blue-600">Tentang Kami</a>
                    <a href="#" class="transition hover:text-blue-600">Kontak</a>
                    <a href="{{ route('login') }}" class="transition hover:text-blue-600">Login Admin</a>
                </div>
            </div>
            <div class="mt-12 border-t border-slate-50 pt-8 text-center text-xs text-slate-400">
                &copy; 2024 Putra Jaya Motor. Built with <i class="fas fa-heart text-red-500"></i> by You.
            </div>
        </div>
    </footer>

</body>

</html>
