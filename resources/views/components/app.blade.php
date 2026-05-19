<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Area Pelanggan') - Putra Jaya Motor</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>

@php
    $currentUser = Auth::user();
    $isPelanggan = $currentUser?->role === 'pelanggan';
    $firstName = $currentUser ? explode(' ', trim($currentUser->name))[0] : '';
@endphp

<body class="bg-slate-50 font-sans text-slate-800 antialiased">
    <div x-data="{ mobileMenu: false }">
        {{-- Navbar --}}
        <nav class="sticky top-0 z-40 border-b border-slate-200 bg-white/80 backdrop-blur-lg">
            <div class="container mx-auto flex items-center px-4 py-4">
                {{-- Left: Brand/Logo --}}
                <div class="flex lg:w-1/4">
                    <a href="{{ route('landing') }}" class="flex items-center gap-3">
                        <div class="sidebar-brand-mark h-12 w-12 rounded-2xl text-sm">
                            <img src="{{ asset('images/logooo.png') }}" alt="Logo PJM"
                                class="sidebar-brand-mark object-cover">
                        </div>
                        <div>
                            <div class="text-sm font-bold uppercase tracking-[0.24em] text-slate-950">Putra Jaya Motor
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Center: Navigation Links --}}
                <div class="hidden flex-1 items-center justify-center gap-8 lg:flex">
                    <a href="{{ route('landing') }}#home"
                        class="text-sm font-semibold text-slate-700 transition hover:text-blue-600">Home</a>
                    <a href="{{ route('landing') }}#layanan"
                        class="text-sm font-semibold text-slate-700 transition hover:text-blue-600">Layanan</a>
                    <a href="{{ route('landing') }}#booking"
                        class="text-sm font-semibold text-slate-700 transition hover:text-blue-600">Booking</a>
                    <a href="{{ route('landing') }}#testimonial"
                        class="text-sm font-semibold text-slate-700 transition hover:text-blue-600">Testimonial</a>
                </div>

                {{-- Right: Auth Actions & Mobile Toggle --}}
                <div class="flex items-center justify-end gap-3 lg:w-1/4">
                    @auth
                        <x-notification-bell />

                        @if ($isPelanggan)
                            <div class="relative" x-data="{ openProfile: false }">
                                <button @click="openProfile = !openProfile" @click.away="openProfile = false"
                                    class="flex items-center gap-2 rounded-full border border-slate-200 bg-white p-1.5 pr-2 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 sm:gap-3 sm:pr-4">

                                    @if ($currentUser->foto)
                                        <img src="{{ asset('storage/' . $currentUser->foto) }}"
                                            alt="Foto {{ $currentUser->name }}" class="h-9 w-9 rounded-full object-cover">
                                    @else
                                        <div
                                            class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white shadow-sm">
                                            {{ strtoupper(substr($currentUser->name, 0, 1)) }}
                                        </div>
                                    @endif

                                    <span class="hidden text-sm font-semibold text-slate-700 sm:block">
                                        {{ $firstName }}
                                    </span>

                                    <svg class="hidden h-4 w-4 text-slate-400 transition-transform duration-200 sm:block"
                                        :class="openProfile ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div x-show="openProfile" x-transition.opacity.duration.200ms
                                    class="absolute right-0 z-50 mt-3 w-72 max-w-[calc(100vw-2rem)] rounded-2xl border border-slate-100 bg-white p-2 shadow-xl"
                                    style="display: none;">

                                    <div class="px-3 py-2 text-xs font-bold uppercase tracking-wider text-slate-400">Akun
                                        Saya</div>

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
                                        Status Servis
                                    </a>

                                    <a href="{{ route('pelanggan.riwayat') }}"
                                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-blue-600">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Riwayat Servis
                                    </a>

                                    <div class="my-1 border-t border-slate-100"></div>

                                    <a href="{{ route('komplain.index') }}"
                                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-blue-600">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        Daftar Komplain
                                    </a>

                                    <div class="my-1 border-t border-slate-100"></div>

                                    <a href="{{ route('testimonial.create') }}"
                                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-blue-600">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                        </svg>
                                        Ulasan Kami
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
                    @else
                        <div class="hidden items-center gap-3 lg:flex">
                            <a href="{{ route('login') }}"
                                class="text-sm font-bold text-slate-700 transition hover:text-blue-600">Login</a>
                            <a href="{{ route('register') }}"
                                class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-500/30">Daftar</a>
                        </div>
                    @endauth

                    {{-- Mobile Menu Button --}}
                    <button type="button"
                        class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white lg:hidden"
                        @click="mobileMenu = !mobileMenu">
                        <span class="text-lg font-black text-slate-900">=</span>
                    </button>
                </div>
            </div>

            {{-- Mobile Menu Panel --}}
            <div x-show="mobileMenu" x-transition class="border-t border-slate-200 bg-white lg:hidden"
                style="display: none;">
                <div class="container mx-auto flex flex-col gap-3 px-4 py-4">
                    <a href="{{ route('landing') }}#home"
                        class="block rounded-lg py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Home</a>
                    <a href="{{ route('landing') }}#layanan"
                        class="block rounded-lg py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Layanan</a>
                    <a href="{{ route('landing') }}#booking"
                        class="block rounded-lg py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Booking</a>

                    @auth
                        @if (Auth::user()->role === 'pelanggan')
                            <div class="mt-2 border-t border-slate-100 pt-4">
                                <div class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-400">Hai,
                                    {{ Auth::user()->name }}</div>
                                <a href="{{ route('profile.edit') }}"
                                    class="block rounded-lg py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Profil
                                    Saya</a>
                                <a href="{{ route('booking.mine') }}"
                                    class="block rounded-lg py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Status
                                    Servis</a>
                                <a href="{{ route('pelanggan.riwayat') }}"
                                    class="block rounded-lg py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Riwayat
                                    Servis</a>
                                <a href="{{ route('komplain.index') }}"
                                    class="block rounded-lg py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Daftar
                                    Komplain</a>
                                <a href="{{ route('testimonial.create') }}"
                                    class="block rounded-lg py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Ulasan
                                    Kami</a>
                                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full rounded-lg py-2 text-left text-sm font-semibold text-rose-600 transition hover:bg-rose-50">Logout</button>
                                </form>
                            </div>
                        @endif
                    @else
                        <div class="mt-2 flex flex-col gap-2 border-t border-slate-100 pt-4">
                            <a href="{{ route('login') }}"
                                class="block w-full rounded-xl border border-slate-200 px-5 py-3 text-center text-sm font-bold text-slate-700 transition hover:bg-slate-50">Login</a>
                            <a href="{{ route('register') }}"
                                class="block w-full rounded-xl bg-blue-600 px-5 py-3 text-center text-sm font-bold text-white transition hover:bg-blue-700">Daftar</a>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>

        {{-- Main Content --}}
        <main>
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="border-t border-slate-200 bg-white/70 py-10 backdrop-blur lg:py-16">
            <div class="container mx-auto px-4">
                <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
                    {{-- Info Bengkel --}}
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl">
                                <img src="{{ asset('images/logooo.png') }}" alt="Logo PJM" class="object-cover">
                            </div>
                            <div class="text-lg font-bold text-slate-950">Putra Jaya Motor</div>
                        </div>
                        <p class="text-sm leading-relaxed text-slate-500">
                            Bengkel motor terpercaya dengan pelayanan terbaik dan mekanik profesional. Kami siap menjaga
                            performa motor Anda agar selalu prima.
                        </p>
                    </div>

                    {{-- Link Cepat --}}
                    <div>
                        <h4 class="mb-4 text-sm font-bold text-slate-900">Menu Cepat</h4>
                        <ul class="space-y-2">
                            <li><a href="{{ route('landing') }}#home"
                                    class="text-sm text-slate-500 transition hover:text-blue-600">Home</a></li>
                            <li><a href="{{ route('landing') }}#layanan"
                                    class="text-sm text-slate-500 transition hover:text-blue-600">Layanan</a></li>
                            <li><a href="{{ route('landing') }}#booking"
                                    class="text-sm text-slate-500 transition hover:text-blue-600">Booking Servis</a>
                            </li>
                        </ul>
                    </div>

                    {{-- Kontak --}}
                    <div>
                        <h4 class="mb-4 text-sm font-bold text-slate-900">Kontak Kami</h4>
                        <ul class="space-y-2 text-sm text-slate-500">
                            <li>WA: 0812-3456-7890</li>
                            <li>Jl. Raya Bogor No. 123</li>
                            <li>Buka: Setiap Hari (08:00 - 17:00)</li>
                        </ul>
                    </div>

                    {{-- Maps --}}
                    <div>
                        <h4 class="mb-4 text-sm font-bold text-slate-900">Lokasi Bengkel</h4>
                        <div class="h-32 w-full overflow-hidden rounded-xl bg-slate-100">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.7011317222677!2d112.6256784!3d-7.926251799999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd629fc0037be71%3A0x9574a348acee40bd!2sPutra%20Jaya%20Motor!5e0!3m2!1sid!2sid!4v1779044397334!5m2!1sid!2sid"
                                width="100%" height="100%" style="border:0;" allowfullscreen=""
                                loading="lazy"></iframe>
                        </div>
                    </div>
                </div>

                <div class="mt-12 border-t border-slate-200 pt-8 text-center text-sm text-slate-500">
                    &copy; {{ date('Y') }} Putra Jaya Motor. All Rights Reserved.
                </div>
            </div>
        </footer>
    </div>
    @yield('scripts')
</body>

</html>
