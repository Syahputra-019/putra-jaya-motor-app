<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Putra Jaya Motor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-slate-50 text-slate-800 antialiased">

    <nav class="sticky top-0 z-50 w-full border-b border-slate-200 bg-white/80 backdrop-blur-md">
        <div class="container mx-auto flex max-w-3xl items-center justify-between px-6 py-4">
            <a href="{{ route('landing') }}" class="text-sm font-semibold text-slate-600 transition hover:text-blue-600">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            <span class="font-bold text-slate-900">Pengaturan Akun</span>
        </div>
    </nav>

    <main class="container mx-auto max-w-3xl px-6 py-10">

        @if (session('success'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-green-800">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        @error('current_password')
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-red-800">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ $message }}
            </div>
        @enderror

        <div class="grid gap-8">
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-bold text-slate-900">Informasi Profil</h2>
                
                <form action="{{ route('profile.updateInfo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-6 flex flex-col items-center gap-5 rounded-xl border border-slate-200 bg-slate-50 p-5 sm:flex-row">
                        <div class="shrink-0">
                            <img id="preview-photo" 
                                 class="h-24 w-24 rounded-full object-cover border-4 border-white shadow-sm"
                                 src="{{ isset($user->foto) ? asset('storage/' . $user->foto) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=E2E8F0&color=475569' }}" 
                                 alt="Foto Profil">
                        </div>
                        <label class="block flex-1 cursor-pointer">
                            <span class="mb-2 block text-sm font-medium text-slate-700">Unggah Foto Profil Baru</span>
                            <input type="file" id="foto-profil" name="foto" accept="image/*" class="block w-full text-sm text-slate-500
                                file:mr-4 file:rounded-md file:border-0
                                file:bg-blue-50 file:px-4 file:py-2
                                file:text-sm file:font-semibold
                                file:text-blue-700 hover:file:bg-blue-100
                                cursor-pointer transition">
                            <p class="mt-2 text-xs text-slate-500">Format: JPG, JPEG, PNG. Maks 2MB.</p>
                        </label>
                    </div>
                    <div class="mb-4">
                        <label class="mb-1 block text-sm font-medium text-slate-700">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            required>
                    </div>

                    @if (in_array($user->role, ['pelanggan', 'mekanik']))
                        <div class="mb-4">
                            <label class="mb-1 block text-sm font-medium text-slate-700">Nomor WhatsApp/Telepon</label>

                            <div class="flex items-stretch overflow-hidden rounded-lg border border-slate-300 bg-white focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500">
                                <input type="text" name="no_telp" value="{{ old('no_telp', $no_telp) }}"
                                    class="w-full border-none px-4 py-2 text-slate-800 focus:outline-none focus:ring-0"
                                    placeholder="0812345678xx">
                            </div>

                            <p class="mt-1 text-xs text-slate-500">Nomor ini digunakan untuk pengiriman notifikasi servis via WA.</p>
                        </div>
                    @endif

                    <div class="mb-6">
                        <label class="mb-1 block text-sm font-medium text-slate-700">Email / Username</label>
                        <input type="email" value="{{ $user->email }}"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2 text-slate-500 cursor-not-allowed"
                            disabled>
                    </div>

                    <button type="submit"
                        class="rounded-lg bg-blue-600 px-5 py-2 font-semibold text-white transition hover:bg-blue-700">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-bold text-slate-900">Ubah Password</h2>
                <form action="{{ route('profile.updatePassword') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="mb-1 block text-sm font-medium text-slate-700">Password Saat Ini</label>
                        <input type="password" name="current_password"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            required>
                    </div>
                    <div class="mb-4">
                        <label class="mb-1 block text-sm font-medium text-slate-700">Password Baru</label>
                        <input type="password" name="password"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            required>
                        @error('password')
                            <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="mb-1 block text-sm font-medium text-slate-700">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            required>
                    </div>
                    <button type="submit"
                        class="rounded-lg bg-slate-900 px-5 py-2 font-semibold text-white transition hover:bg-slate-800">
                        Perbarui Password
                    </button>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputFoto = document.getElementById('foto-profil');
            const previewFoto = document.getElementById('preview-photo');

            if(inputFoto) {
                inputFoto.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewFoto.src = e.target.result;
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
</body>

</html>