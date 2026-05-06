<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Komplain - Putra Jaya Motor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-slate-50 text-slate-800 antialiased">

    <nav class="border-b border-slate-200 bg-white px-6 py-4 shadow-sm">
        <div class="mx-auto flex max-w-3xl items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center gap-2 text-xl font-black text-blue-600">
                <span class="text-2xl">🏍️</span> PJM Bengkel
            </a>
            <a href="{{ route('komplain.index') }}"
                class="text-sm font-bold text-slate-500 transition hover:text-blue-600">
                Riwayat Komplain Saya
            </a>
        </div>
    </nav>

    <div class="mx-auto max-w-3xl px-4 py-10">
        <div class="rounded-3xl border border-slate-100 bg-white p-8 shadow-xl shadow-slate-200/50">
            <h2 class="mb-2 text-3xl font-black text-slate-800">Ada kendala pasca servis? 🛠️</h2>
            <p class="mb-8 text-slate-500">Tenang mas bro, kasih tau mekanik kita di sini. Kepuasan pelanggan nomor satu
                buat Putra Jaya Motor!</p>

            <form action="{{ route('komplain.store') }}" method="POST" enctype="multipart/form-data">
                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 p-4">
                        <ul class="list-disc pl-5 text-sm font-bold text-rose-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @csrf

                <div class="mb-6">
                    <label class="mb-2 block text-sm font-bold text-slate-700">Pilih Riwayat Servis</label>
                    <select name="booking_id"
                        class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 text-sm focus:ring-blue-500"
                        required>
                        <option value="">-- Pilih Servis yang Dikomplain --</option>
                        @foreach ($bookings as $item)
                            <option value="{{ $item->id }}">
                                {{ \Carbon\Carbon::parse($item->tanggal_booking)->format('d M Y') }} -
                                {{ $item->tipe_motor }} ({{ $item->plat_nomor }})
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-2 text-xs text-slate-400">*Hanya menampilkan riwayat servis yang sudah lunas/selesai.
                    </p>
                </div>

                <div class="mb-6">
                    <label class="mb-2 block font-bold text-slate-700">Jelaskan Kendalanya <span
                            class="text-rose-500">*</span></label>
                    <textarea name="deskripsi_komplain" rows="5"
                        class="w-full rounded-xl border-slate-200 bg-slate-50 p-4 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                        placeholder="Contoh: Mas, habis ganti kampas rem kemarin kok sekarang rem depannya agak blong ya kalau lagi kenceng..."
                        required></textarea>
                </div>

                <div class="mb-8">
                    <label class="mb-2 block font-bold text-slate-700">Foto Bukti / Kendala <span
                            class="font-normal text-slate-400">(Opsional)</span></label>
                    <input type="file" name="foto_bukti" accept="image/*"
                        class="w-full rounded-xl border border-dashed border-slate-300 bg-slate-50 p-4 text-sm text-slate-500 file:mr-4 file:rounded-full file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('landing') }}"
                        class="w-1/3 rounded-xl bg-slate-100 py-4 text-center font-bold text-slate-500 transition hover:bg-slate-200">Batal</a>
                    <button type="submit"
                        class="w-2/3 rounded-xl bg-blue-600 py-4 font-bold text-white shadow-lg shadow-blue-600/30 transition hover:bg-blue-700">
                        Kirim Komplain
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
