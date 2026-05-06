<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Komplain - Putra Jaya Motor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-800 antialiased">

    <nav class="border-b border-slate-200 bg-white px-6 py-4 shadow-sm">
        <div class="mx-auto flex max-w-3xl items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center gap-2 text-xl font-black text-blue-600">
                ← Kembali ke Beranda
            </a>
            <a href="{{ route('komplain.create') }}"
                class="rounded-full bg-orange-100 px-4 py-2 text-sm font-bold text-orange-600 transition hover:bg-orange-200">
                + Buat Komplain Baru
            </a>
        </div>
    </nav>

    <div class="mx-auto max-w-3xl px-4 py-10">
        <h2 class="mb-8 text-3xl font-black text-slate-800">Riwayat Komplain Anda 🧾</h2>

        @if (session('success'))
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-100 px-4 py-3 font-bold text-emerald-700">
                ✅ {{ session('success') }}
            </div>
        @endif

        <div class="space-y-6">
            @forelse($komplains as $item)
                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-md">
                    <div class="mb-4 flex items-start justify-between border-b border-slate-100 pb-4">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Tanggal
                                Pengajuan</span>
                            <p class="font-semibold text-slate-700">
                                {{ \Carbon\Carbon::parse($item->created_at)->format('d F Y, H:i') }}</p>
                            <p class="mt-1 text-sm font-bold text-blue-600">Servis:
                                {{ $item->booking->tipe_motor ?? 'Motor Servis' }}</p>
                        </div>
                        <div>
                            @if ($item->status === 'menunggu')
                                <span
                                    class="rounded-full bg-rose-100 px-3 py-1 text-xs font-bold text-rose-600">Menunggu
                                    Respon</span>
                            @elseif($item->status === 'diproses')
                                <span
                                    class="rounded-full bg-orange-100 px-3 py-1 text-xs font-bold text-orange-600">Diproses</span>
                            @else
                                <span
                                    class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-600">Selesai</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <span class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-400">Keluhan
                            Anda:</span>
                        <p class="rounded-lg border border-slate-100 bg-slate-50 p-3 text-slate-700">
                            {{ $item->deskripsi_komplain }}</p>
                    </div>

                    @if ($item->tanggapan_bengkel)
                        <div class="mt-4 rounded-xl border border-blue-100 bg-blue-50 p-4">
                            <span class="mb-1 block text-xs font-bold uppercase tracking-wider text-blue-500">Balasan
                                Bengkel:</span>
                            <p class="font-medium text-slate-800">{{ $item->tanggapan_bengkel }}</p>
                        </div>
                    @else
                        <div class="mt-4 text-sm italic text-slate-400">
                            Belum ada tanggapan dari mekanik. Mohon bersabar ya mas bro.
                        </div>
                    @endif
                </div>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white py-12 text-center">
                    <p class="text-lg font-bold text-slate-400">Belum ada riwayat komplain.</p>
                    <p class="mt-1 text-sm text-slate-400">Motor aman sentosa! 🛵💨</p>
                </div>
            @endforelse
        </div>
    </div>
</body>

</html>
