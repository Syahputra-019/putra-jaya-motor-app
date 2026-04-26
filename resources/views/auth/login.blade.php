<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Putra Jaya Motor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="auth-body">
    <div class="auth-shell">
        <div class="auth-grid">
            <div class="auth-showcase">
                <div>
                    <span class="auth-badge">Workshop Control</span>
                    <h1 class="mt-6 text-5xl font-bold leading-tight">Masuk ke sistem bengkel dengan tampilan baru yang lebih modern.</h1>
                    <p class="mt-5 max-w-xl text-base leading-8 text-slate-200">
                        Kelola booking, transaksi, stok sparepart, dan laporan dalam satu panel yang konsisten dengan nuansa biru tua dan aksen kuning.
                    </p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="rounded-[28px] border border-white/10 bg-white/10 p-5 backdrop-blur">
                        <div class="text-3xl font-bold text-yellow-300">01</div>
                        <p class="mt-2 text-sm text-slate-200">Workflow operasional lebih rapi dan enak dipantau.</p>
                    </div>
                    <div class="rounded-[28px] border border-white/10 bg-white/10 p-5 backdrop-blur">
                        <div class="text-3xl font-bold text-yellow-300">02</div>
                        <p class="mt-2 text-sm text-slate-200">Komponen visual seragam dari landing page sampai dashboard.</p>
                    </div>
                </div>
            </div>

            <div class="auth-card">
                <div class="mb-8">
                    <div class="flex items-center gap-3">
                        <div class="sidebar-brand-mark h-12 w-12 rounded-2xl text-sm">PJM</div>
                        <div>
                            <div class="text-lg font-bold text-slate-950">Putra Jaya Motor</div>
                            <div class="text-sm text-slate-500">Sistem operasional bengkel</div>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <p class="page-kicker">Akses Sistem</p>
                    <h2 class="mt-2 text-3xl font-bold text-slate-950">Login ke akun Anda</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-500">Gunakan email dan password yang sudah terdaftar untuk membuka dashboard atau area kerja Anda.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger mb-6">
                        <div class="font-black">!</div>
                        <div>
                            <div class="font-bold">Login gagal</div>
                            <div class="mt-1 text-sm">{{ $errors->first() }}</div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" class="form-shell">
                    @csrf

                    <div class="form-field">
                        <label class="field-label" for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" class="auth-input"
                            placeholder="nama@email.com" required>
                    </div>

                    <div class="form-field">
                        <label class="field-label" for="password">Password</label>
                        <input id="password" type="password" name="password" class="auth-input"
                            placeholder="Masukkan password" required>
                    </div>

                    <button type="submit" class="btn-primary w-full">Masuk ke Sistem</button>
                </form>

                <div class="mt-8 flex items-center justify-between gap-4 text-sm text-slate-500">
                    <a href="{{ route('landing') }}" class="font-semibold text-slate-600 hover:text-slate-950">Kembali ke beranda</a>
                    <a href="{{ route('register') }}" class="font-semibold text-[color:var(--brand-navy-800)] hover:text-slate-950">Buat akun pelanggan</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
