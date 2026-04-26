<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Putra Jaya Motor - Sistem Booking</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-slate-50 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">

        <aside class="z-20 flex w-64 flex-col bg-blue-900 text-white shadow-xl">
            <div
                class="flex h-16 items-center justify-center border-b border-slate-700 text-xl font-bold tracking-wider">
                <span class="mr-2 text-blue-500">🔧</span> PJM Bengkel
            </div>

            <nav class="flex-1 space-y-2 overflow-y-auto px-4 py-6">

                @if (Auth::check() && Auth::user()->role === 'admin')
                    <a href="{{ route('dashboard') }}"
                        class="block rounded-lg bg-blue-600 px-4 py-3 font-medium text-white transition hover:bg-blue-700">Dashboard
                        Admin</a>

                    <a href="{{ route('mekanik.index') }}"
                        class="block rounded-lg px-4 py-3 font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white">Data
                        Mekanik</a>
                    <a href="{{ route('sparepart.index') }}"
                        class="block rounded-lg px-4 py-3 font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white">Data
                        Sparepart</a>
                    <a href="{{ route('pelanggan.index') }}"
                        class="block rounded-lg px-4 py-3 font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white">Data
                        Pelanggan</a>
                    <a href="{{ route('service.index') }}"
                        class="block rounded-lg px-4 py-3 font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white">Data
                        Jasa Servis</a>
                    <a href="{{ route('transaksi.index') }}"
                        class="block rounded-lg px-4 py-3 font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white">Kasir
                        & Transaksi</a>
                    <a href="{{ route('laporan.index') }}"
                        class="block rounded-lg px-4 py-3 font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white">Laporan
                        Pendapatan</a>
                @endif

                @if (Auth::check() && Auth::user()->role === 'pelanggan')
                @endif

                @if (Auth::check() && (Auth::user()->role === 'pelanggan' || Auth::user()->role === 'admin'))
                    <a href="{{ route('booking.index') }}"
                        class="block rounded-lg px-4 py-3 font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white">Booking
                        Antrean</a>
                @endif

                @if (Auth::check() && (Auth::user()->role === 'mekanik' || Auth::user()->role === 'admin'))
                    <a href="{{ route('mekanik.jadwal') }}"
                        class="block rounded-lg px-4 py-3 font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white">Jadwal
                        Servis</a>
                @endif

                <hr class="my-4 border-slate-700">

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center gap-2 rounded-lg px-4 py-3 text-left font-bold text-red-400 transition hover:bg-red-600 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        Logout
                    </button>
                </form>

            </nav>
        </aside>

        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">

            <header class="z-10 flex h-16 items-center justify-end bg-white px-6 shadow-sm">
                <div class="flex cursor-pointer items-center gap-3 rounded-lg p-2 transition hover:bg-slate-50">
                    <span class="text-sm font-semibold text-slate-700">Halo, {{ Auth::user()->name ?? 'Guest' }}</span>
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 font-bold uppercase text-white shadow-md">
                        {{ substr(Auth::user()->name ?? 'G', 0, 1) }}
                    </div>
                </div>
            </header>

            <main class="p-6">
                {{ $slot }}
            </main>

        </div>
    </div>
</body>

</html>
