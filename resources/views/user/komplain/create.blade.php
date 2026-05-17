@extends('components.app')

@section('title', 'Buat Komplain')

@section('content')
    <section class="py-10">
        <div class="container mx-auto max-w-4xl px-4">
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="page-kicker">After Service Support</p>
                    <h1 class="mt-2 text-3xl font-bold text-slate-950">Ajukan komplain</h1>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Kalau ada kendala setelah servis, jelaskan detailnya
                        di sini agar tim bengkel bisa menindaklanjuti lebih cepat.</p>
                </div>
                <a href="{{ route('komplain.index') }}" class="btn-secondary">Riwayat Komplain</a>
            </div>

            <div class="rounded-[32px] border border-slate-100 bg-white p-8 shadow-xl shadow-slate-200/50">
                @if ($errors->any())
                    <div class="alert alert-danger mb-6">
                        <div class="font-black">!</div>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form action="{{ route('komplain.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf

                    <div class="form-field">
                        <label class="field-label" for="booking_id">Pilih riwayat servis</label>
                        <select id="booking_id" name="booking_id"
                            class="block w-full rounded-2xl border-0 bg-slate-50 px-4 py-3 text-sm text-slate-900 ring-1 ring-inset ring-slate-200 transition-colors hover:bg-white focus:ring-2 focus:ring-inset focus:ring-slate-900"
                            required>
                            <option value="">-- Pilih servis yang dikomplain --</option>
                            @foreach ($bookings as $item)
                                <option value="{{ $item->id }}">
                                    {{ \Carbon\Carbon::parse($item->jadwal_booking)->format('d M Y') }} -
                                    {{ $item->tipe_motor }} ({{ $item->plat_nomor }})
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-xs leading-5 text-slate-500">Hanya riwayat servis yang sudah selesai yang
                            ditampilkan di sini.</p>
                    </div>

                    <div class="form-field">
                        <label class="field-label" for="deskripsi_komplain">Jelaskan kendalanya</label>
                        <textarea id="deskripsi_komplain" name="deskripsi_komplain" rows="6"
                            class="form-textarea min-h-[160px]" placeholder="Contoh: Setelah ganti kampas rem, rem depan terasa kurang pakem saat dipakai..." required>{{ old('deskripsi_komplain') }}</textarea>
                    </div>

                    <div class="form-field">
                        <label class="field-label" for="foto_bukti">Foto bukti / kendala</label>
                        <input id="foto_bukti" type="file" name="foto_bukti" accept="image/*"
                            class="block w-full rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-4 text-sm text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:font-semibold file:text-white hover:file:bg-slate-800">
                        <p class="mt-2 text-xs leading-5 text-slate-500">Opsional, tapi sangat membantu untuk
                            memperjelas kondisi motor.</p>
                    </div>

                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <a href="{{ route('komplain.index') }}" class="btn-secondary text-center">Batal</a>
                        <button type="submit" class="btn-primary">Kirim Komplain</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
