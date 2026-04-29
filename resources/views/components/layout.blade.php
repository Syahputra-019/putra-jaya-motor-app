<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Putra Jaya Motor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

@php
    $user = Auth::user();
    $navigation = [];

    if ($user?->role === 'admin') {
        $navigation['Admin'] = [
            ['label' => 'Dashboard', 'route' => 'dashboard', 'patterns' => ['dashboard*']],
            [
                'label' => 'Data Mekanik',
                'route' => 'mekanik.index',
                'patterns' => ['mekanik.index', 'mekanik.create', 'mekanik.edit'],
            ],
            ['label' => 'Data Sparepart', 'route' => 'sparepart.index', 'patterns' => ['sparepart.*']],
            ['label' => 'Data Pelanggan', 'route' => 'pelanggan.index', 'patterns' => ['pelanggan.*']],
            ['label' => 'Jasa Servis', 'route' => 'service.index', 'patterns' => ['service.*']],
            ['label' => 'Kasir & Transaksi', 'route' => 'transaksi.index', 'patterns' => ['transaksi.*']],
            ['label' => 'Laporan', 'route' => 'laporan.index', 'patterns' => ['laporan.*']],
        ];
    }

    if ($user && in_array($user->role, ['admin', 'pelanggan'])) {
        $navigation['Booking'] = [
            ['label' => 'Antrean Booking', 'route' => 'booking.index', 'patterns' => ['booking.*']],
        ];
    }

    if ($user && in_array($user->role, ['admin', 'mekanik'])) {
        $navigation['Servis'] = [
            ['label' => 'Jadwal Servis', 'route' => 'mekanik.jadwal', 'patterns' => ['mekanik.jadwal*']],
        ];
    }
@endphp

<body class="app-body">
    <div x-data="{ sidebarOpen: false }" class="relative min-h-screen">
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-slate-950/55 lg:hidden"
            @click="sidebarOpen = false"></div>

        <aside class="app-sidebar mobile-sidebar lg:fixed lg:inset-y-0 lg:left-0 lg:z-30 lg:w-80 lg:translate-x-0"
            :class="{ 'open': sidebarOpen }">
            <div class="sidebar-brand">
                <div class="sidebar-brand-mark">PJM</div>
                <div class="sidebar-brand-copy">
                    <span>Putra Jaya</span>
                    <span>Sistem Operasional Bengkel</span>
                </div>
            </div>

            <nav class="app-nav">
                @foreach ($navigation as $section => $items)
                    <div class="nav-section-label">{{ $section }}</div>

                    @foreach ($items as $item)
                        @php
                            $isActive = collect($item['patterns'])->contains(
                                fn($pattern) => request()->routeIs($pattern),
                            );
                        @endphp
                        <a href="{{ route($item['route']) }}" class="app-nav-link {{ $isActive ? 'is-active' : '' }}">
                            <span class="app-nav-dot"></span>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                @endforeach

                <div class="nav-section-label">Sesi</div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="app-nav-link w-full text-left text-rose-200 hover:text-white">
                        <span class="app-nav-dot bg-rose-300 shadow-none"></span>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>
        </aside>

        <div class="app-shell">
            <header class="app-topbar">
                <div class="topbar-inner">
                    <div class="topbar-meta">
                        <button type="button"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm lg:hidden"
                            @click="sidebarOpen = true">
                            <span class="text-lg font-black">=</span>
                        </button>

                        <div>
                            <div class="topbar-title">Putra Jaya Motor</div>
                            <div class="topbar-subtitle">Panel kerja yang rapi, konsisten, dan nyaman dipakai harian.
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('profile.edit') }}"
                        class="user-panel flex cursor-pointer items-center gap-3 rounded-lg p-2 transition hover:bg-slate-100">

                        @if (isset($user) && $user->foto)
                            <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto Profil"
                                class="h-10 w-10 rounded-full border border-slate-200 object-cover">
                        @else
                            <div class="user-avatar">{{ strtoupper(substr($user->name ?? 'G', 0, 1)) }}</div>
                        @endif

                        <div>
                            <div class="text-sm font-bold text-slate-900">{{ $user->name ?? 'Guest' }}</div>
                            <div class="user-role">{{ $user->role ?? 'guest' }}</div>
                        </div>
                    </a>
                </div>
            </header>

            <main class="app-main">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>
