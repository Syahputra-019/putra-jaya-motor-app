<x-layout>
    @php
        $homeRoute = match ($user->role) {
            'admin' => route('dashboard'),
            'mekanik' => route('mekanik.jadwal'),
            default => route('booking.index'),
        };

        $defaultPhoto = 'https://ui-avatars.com/api/?name=' .
            urlencode($user->name) .
            '&background=E2E8F0&color=475569';
    @endphp

    <div class="page-shell">
        <div class="page-header">
            <div class="page-header-split">
                <p class="page-kicker">Account Settings</p>
                <h1 class="page-title">Profil akun</h1>
                <p class="page-description">Kelola identitas akun, foto profil, nomor telepon, dan keamanan login dari
                    satu halaman yang konsisten untuk semua role.</p>
            </div>

            <div class="page-actions">
                <a href="{{ $homeRoute }}" class="btn-secondary">Kembali</a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <div class="font-black">OK</div>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="font-black">!</div>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.15fr)_360px]">
            <div class="space-y-6">
                <section class="surface-card">
                    <div class="border-b border-slate-100 pb-5">
                        <p class="page-kicker">Informasi Utama</p>
                        <h2 class="mt-2 text-2xl font-bold text-slate-950">Data profil</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Perbarui nama, nomor telepon, dan foto profil
                            agar data akun tetap akurat di seluruh sistem.</p>
                    </div>

                    <form action="{{ route('profile.updateInfo') }}" method="POST" enctype="multipart/form-data"
                        class="mt-6 space-y-6">
                        @csrf
                        @method('PUT')

                        <div
                            class="rounded-[28px] border border-slate-100 bg-gradient-to-br from-slate-50 via-white to-slate-100/70 p-5">
                            <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
                                <div class="relative shrink-0">
                                    <img id="preview-photo"
                                        class="h-24 w-24 rounded-full border-4 border-white object-cover shadow-lg shadow-slate-200/70"
                                        src="{{ $user->foto ? asset('storage/' . $user->foto) : $defaultPhoto }}"
                                        alt="Foto Profil">
                                    <div
                                        class="absolute -bottom-1 -right-1 rounded-full border border-white bg-slate-900 px-2 py-1 text-[10px] font-bold uppercase tracking-[0.2em] text-white">
                                        {{ strtoupper(substr($user->role, 0, 3)) }}
                                    </div>
                                </div>

                                <div class="flex-1">
                                    <div class="text-sm font-semibold text-slate-900">{{ $user->name }}</div>
                                    <div class="mt-1 text-sm text-slate-500">{{ $user->email }}</div>
                                    <label class="mt-4 block cursor-pointer">
                                        <span class="mb-2 block text-sm font-medium text-slate-700">Unggah foto
                                            profil baru</span>
                                        <input type="file" id="foto-profil" name="foto" accept="image/*"
                                            class="block w-full rounded-xl border border-slate-200 bg-white text-sm text-slate-500 file:mr-4 file:rounded-lg file:border-0 file:bg-slate-900 file:px-4 file:py-2.5 file:font-semibold file:text-white hover:file:bg-slate-800">
                                        <p class="mt-2 text-xs leading-5 text-slate-500">Format JPG, JPEG, atau PNG.
                                            Maksimal 2 MB.</p>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div class="form-field md:col-span-2">
                                <label class="field-label" for="name">Nama lengkap</label>
                                <input id="name" type="text" name="name"
                                    value="{{ old('name', $user->name) }}" class="form-input" required>
                            </div>

                            @if (in_array($user->role, ['pelanggan', 'mekanik']))
                                <div class="form-field md:col-span-2">
                                    <label class="field-label" for="no_telp">Nomor WhatsApp / Telepon</label>
                                    <input id="no_telp" type="text" name="no_telp"
                                        value="{{ old('no_telp', $no_telp) }}" class="form-input"
                                        placeholder="0812345678xx">
                                    <p class="mt-2 text-xs leading-5 text-slate-500">Nomor ini dipakai untuk kontak
                                        servis dan notifikasi.</p>
                                </div>
                            @endif

                            <div class="form-field md:col-span-2">
                                <label class="field-label" for="email_preview">Email / Username</label>
                                <input id="email_preview" type="email" value="{{ $user->email }}"
                                    class="form-input cursor-not-allowed bg-slate-100 text-slate-500" disabled>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </section>

                <section class="surface-card">
                    <div class="border-b border-slate-100 pb-5">
                        <p class="page-kicker">Security</p>
                        <h2 class="mt-2 text-2xl font-bold text-slate-950">Ubah password</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Gunakan password yang kuat agar akun tetap aman
                            dipakai di panel bengkel.</p>
                    </div>

                    <form action="{{ route('profile.updatePassword') }}" method="POST" class="mt-6 space-y-5">
                        @csrf
                        @method('PUT')

                        <div class="form-field">
                            <label class="field-label" for="current_password">Password saat ini</label>
                            <input id="current_password" type="password" name="current_password" class="form-input"
                                required>
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div class="form-field">
                                <label class="field-label" for="password">Password baru</label>
                                <input id="password" type="password" name="password" class="form-input" required>
                            </div>

                            <div class="form-field">
                                <label class="field-label" for="password_confirmation">Konfirmasi password baru</label>
                                <input id="password_confirmation" type="password" name="password_confirmation"
                                    class="form-input" required>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="btn-accent">Perbarui Password</button>
                        </div>
                    </form>
                </section>
            </div>

            <aside class="space-y-6">
                <section class="surface-card">
                    <p class="page-kicker">Ringkasan Akun</p>
                    <div class="mt-4 flex items-center gap-4">
                        <img class="h-16 w-16 rounded-2xl border border-slate-200 object-cover"
                            src="{{ $user->foto ? asset('storage/' . $user->foto) : $defaultPhoto }}"
                            alt="Foto Profil Ringkas">
                        <div>
                            <h3 class="text-lg font-bold text-slate-950">{{ $user->name }}</h3>
                            <p class="text-sm text-slate-500">{{ ucfirst($user->role) }}</p>
                        </div>
                    </div>

                    <div class="mt-5 space-y-3">
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Email</div>
                            <div class="mt-2 text-sm font-medium text-slate-800">{{ $user->email }}</div>
                        </div>

                        @if (in_array($user->role, ['pelanggan', 'mekanik']))
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                                <div class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">No.
                                    Telepon</div>
                                <div class="mt-2 text-sm font-medium text-slate-800">
                                    {{ $no_telp ?: 'Belum diisi' }}
                                </div>
                            </div>
                        @endif

                        <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Akses Cepat
                            </div>
                            <div class="mt-3 flex flex-wrap gap-3">
                                <a href="{{ $homeRoute }}" class="btn-secondary">Kembali ke Menu</a>
                                <a href="{{ route('profile.edit') }}" class="btn-warning !px-4 !py-2">Refresh Halaman</a>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="surface-card">
                    <p class="page-kicker">Tips</p>
                    <h3 class="mt-2 text-lg font-bold text-slate-950">Biar tampilan profil rapi</h3>
                    <div class="mt-4 space-y-3 text-sm leading-6 text-slate-600">
                        <div class="rounded-2xl border border-slate-100 bg-white p-4">
                            Gunakan foto profil persegi agar hasil crop bulat tetap proporsional di topbar.
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-white p-4">
                            Simpan nomor telepon aktif supaya admin, mekanik, dan pelanggan mudah saling terhubung.
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-white p-4">
                            Setelah mengganti password, gunakan kombinasi huruf besar, kecil, angka, dan simbol.
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputFoto = document.getElementById('foto-profil');
            const previewFoto = document.getElementById('preview-photo');

            if (!inputFoto || !previewFoto) {
                return;
            }

            inputFoto.addEventListener('change', function() {
                const file = this.files[0];

                if (!file) {
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    previewFoto.src = event.target.result;
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
</x-layout>
