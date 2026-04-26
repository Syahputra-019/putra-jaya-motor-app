<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Putra Jaya Motor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="auth-body">
    <div class="auth-shell">
        <div class="auth-grid">
            <div class="auth-showcase">
                <div>
                    <span class="auth-badge">Customer Access</span>
                    <h1 class="mt-6 text-5xl font-bold leading-tight">Buat akun pelanggan untuk memantau servis motor dengan tampilan yang lebih rapi.</h1>
                    <p class="mt-5 max-w-xl text-base leading-8 text-slate-200">
                        Setelah terdaftar, pelanggan dapat mengakses status booking dan melihat progres servis dari tampilan yang seragam dengan sistem internal.
                    </p>
                </div>

                <div class="space-y-4">
                    <div class="rounded-[28px] border border-white/10 bg-white/10 p-5 backdrop-blur">
                        <div class="text-sm font-bold uppercase tracking-[0.22em] text-yellow-300">Benefit</div>
                        <p class="mt-2 text-sm leading-7 text-slate-200">Akun pelanggan memudahkan tracking antrean, histori servis, dan proses pembayaran digital.</p>
                    </div>
                    <div class="rounded-[28px] border border-white/10 bg-white/10 p-5 backdrop-blur">
                        <div class="text-sm font-bold uppercase tracking-[0.22em] text-yellow-300">Visual</div>
                        <p class="mt-2 text-sm leading-7 text-slate-200">Semua tampilan sekarang memakai ritme spacing, warna, dan bentuk komponen yang sama.</p>
                    </div>
                </div>
            </div>

            <div class="auth-card">
                <div class="mb-8">
                    <div class="flex items-center gap-3">
                        <div class="sidebar-brand-mark h-12 w-12 rounded-2xl text-sm">PJM</div>
                        <div>
                            <div class="text-lg font-bold text-slate-950">Putra Jaya Motor</div>
                            <div class="text-sm text-slate-500">Registrasi pelanggan baru</div>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <p class="page-kicker">Registrasi</p>
                    <h2 class="mt-2 text-3xl font-bold text-slate-950">Buat akun baru</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-500">Daftar untuk mengakses area pelanggan dan memantau riwayat servis kendaraan Anda.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger mb-6">
                        <div class="font-black">!</div>
                        <div>
                            <div class="font-bold">Registrasi belum berhasil</div>
                            <ul class="mt-2 list-disc pl-5 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('register.post') }}" method="POST" class="form-shell">
                    @csrf

                    <div class="form-field">
                        <label class="field-label" for="name">Nama lengkap</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" class="auth-input"
                            placeholder="Contoh: Budi Santoso" required>
                    </div>

                    <div class="form-field">
                        <label class="field-label" for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" class="auth-input"
                            placeholder="nama@email.com" required>
                    </div>

                    <div class="form-field">
                        <label class="field-label" for="password">Password</label>
                        <input id="password" type="password" name="password" class="auth-input"
                            placeholder="Minimal 8 karakter" required>
                    </div>

                    <div class="form-field">
                        <label class="field-label" for="password_confirmation">Konfirmasi password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                            class="auth-input" placeholder="Ulangi password" required>
                    </div>

                    <button type="submit" class="btn-primary w-full">Daftar Sekarang</button>
                </form>

                <div class="mt-8 flex items-center justify-between gap-4 text-sm text-slate-500">
                    <a href="{{ route('landing') }}" class="font-semibold text-slate-600 hover:text-slate-950">Kembali ke beranda</a>
                    <a href="{{ route('login') }}" class="font-semibold text-[color:var(--brand-navy-800)] hover:text-slate-950">Sudah punya akun?</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
