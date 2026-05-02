<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Putra Jaya Motor</title>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="auth-body">
    <div class="auth-shell">
        <div class="auth-grid" data-aos="fade-up" data-aos-delay="150">
            <div class="auth-showcase">
                <div>
                    <span class="auth-badge">Workshop Control</span>
                    <h1 class="mt-6 text-5xl font-bold leading-tight">Masuk ke sistem bengkel dengan tampilan baru yang
                        lebih modern.</h1>
                    <p class="my-5 max-w-xl text-base leading-8 text-slate-200">
                        Kelola booking, transaksi, stok sparepart, dan laporan dalam satu panel yang konsisten dengan
                        nuansa biru tua dan aksen kuning.
                    </p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="rounded-[28px] border border-white/10 bg-white/10 p-5 backdrop-blur" data-aos="fade-up" data-aos-delay="200">
                        <div class="text-3xl font-bold text-yellow-300">01</div>
                        <p class="mt-2 text-sm text-slate-200">Workflow operasional lebih rapi dan enak dipantau.</p>
                    </div>
                    <div class="rounded-[28px] border border-white/10 bg-white/10 p-5 backdrop-blur" data-aos="fade-up" data-aos-delay="300">
                        <div class="text-3xl font-bold text-yellow-300">02</div>
                        <p class="mt-2 text-sm text-slate-200">Komponen visual seragam dari landing page sampai
                            dashboard.</p>
                    </div>
                </div>
            </div>

            <div class="auth-card">
                <div class="mb-8">
                    <div class="flex items-center gap-3">
                        <div class="sidebar-brand-mark h-12 w-12 rounded-2xl text-sm">
                            <img src="{{ asset('images/logooo.png') }}" alt="Logo PJM"
                                class="sidebar-brand-mark object-cover">
                        </div>
                        <div>
                            <div class="text-lg font-bold text-slate-950">Putra Jaya Motor</div>
                            <div class="text-sm text-slate-500">Sistem operasional bengkel</div>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <p class="page-kicker">Akses Sistem</p>
                    <h2 class="mt-2 text-3xl font-bold text-slate-950">Login ke akun Anda</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-500">Gunakan email dan password yang sudah terdaftar
                        untuk membuka dashboard atau area kerja Anda.</p>
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
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                            class="auth-input" placeholder="nama@email.com" required>
                    </div>

                    <div class="form-field">
                        <label class="field-label" for="password">Password</label>

                        <div class="relative">
                            <input id="password" type="password" name="password" class="auth-input w-full pr-10"
                                placeholder="Masukkan password" required>

                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 focus:outline-none">

                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>

                                <svg id="eyeIconSlash" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="hidden h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full">Login</button>
                </form>

                <div class="mt-8 flex items-center justify-between gap-4 text-sm text-slate-500">
                    <a href="{{ route('landing') }}" class="font-semibold text-slate-600 hover:text-slate-950">Kembali
                        ke beranda</a>
                    <a href="{{ route('register') }}"
                        class="font-semibold text-[color:var(--brand-navy-800)] hover:text-slate-950">Buat akun
                        pelanggan</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeIconSlash = document.getElementById('eyeIconSlash');

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    // Cek tipe input saat ini, lalu ubah
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);

                    // Toggle class 'hidden' di antara kedua SVG
                    if (type === 'text') {
                        eyeIcon.classList.add('hidden');
                        eyeIconSlash.classList.remove('hidden');
                    } else {
                        eyeIcon.classList.remove('hidden');
                        eyeIconSlash.classList.add('hidden');
                    }
                });
            }
        });
    </script>

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
