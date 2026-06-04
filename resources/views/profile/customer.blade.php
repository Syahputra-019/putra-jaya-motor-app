@extends('components.app')

@section('title', 'Profil Saya')

@php
    $defaultPhoto = 'https://ui-avatars.com/api/?name=' .
        urlencode($user->name) .
        '&background=E2E8F0&color=475569';
@endphp

@section('content')
    <section class="py-10">
        <div class="container mx-auto max-w-6xl px-4">
            <div class="grid gap-6 xl:grid-cols-[minmax(0,1.15fr)_360px]">
                <div class="space-y-6">
                    <section class="surface-card">
                        <div class="border-b border-slate-100 pb-5">
                            <p class="page-kicker">Account Settings</p>
                            <h1 class="mt-2 text-3xl font-bold text-slate-950">Profil saya</h1>
                            <p class="mt-2 text-sm leading-6 text-slate-500">Kelola data akun, foto profil, dan nomor
                                telepon Anda dari satu tempat.</p>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success mt-6">
                                <div class="font-black">OK</div>
                                <div>{{ session('success') }}</div>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger mt-6">
                                <div class="font-black">!</div>
                                <div>
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

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
                                            class="absolute -bottom-1 -right-1 rounded-full border border-white bg-blue-600 px-2 py-1 text-[10px] font-bold uppercase tracking-[0.2em] text-white">
                                            CUS
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

                                <div class="form-field md:col-span-2">
                                    <label class="field-label" for="no_telp">Nomor WhatsApp / Telepon</label>
                                    <input id="no_telp" type="text" name="no_telp"
                                        value="{{ old('no_telp', $no_telp) }}" class="form-input"
                                        placeholder="0812345678xx">
                                    <p class="mt-2 text-xs leading-5 text-slate-500">Nomor ini dipakai untuk kontak
                                        servis dan notifikasi.</p>
                                </div>

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
                            <p class="mt-2 text-sm leading-6 text-slate-500">Gunakan password yang kuat agar akun Anda
                                tetap aman.</p>
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
                                <p class="text-sm text-slate-500">Pelanggan</p>
                            </div>
                        </div>

                        <div class="mt-5 space-y-3">
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                                <div class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Email</div>
                                <div class="mt-2 text-sm font-medium text-slate-800">{{ $user->email }}</div>
                            </div>

                            <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                                <div class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">No.
                                    Telepon</div>
                                <div class="mt-2 text-sm font-medium text-slate-800">
                                    {{ $no_telp ?: 'Belum diisi' }}
                                </div>
                            </div>

                            <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                                <div class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Akses Cepat
                                </div>
                                <div class="mt-3 flex flex-wrap gap-3">
                                    <a href="{{ route('booking.mine') }}" class="btn-secondary">Status Servis</a>
                                    <a href="{{ route('pelanggan.pembayaran') }}" class="btn-primary">Pembayaran</a>
                                    <a href="{{ route('pelanggan.riwayat') }}" class="btn-warning !px-4 !py-2">Riwayat</a>
                                </div>
                            </div>
                        </div>
                    </section>
                </aside>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
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
@endsection
