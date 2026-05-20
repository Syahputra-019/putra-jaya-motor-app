@extends('components.app')

@section('title', 'Cek Jadwal Kosong')

@section('content')
    <div class="container mx-auto max-w-6xl px-4 py-12">
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-bold text-slate-800">Jadwal Slot Booking Servis</h1>
            <p class="mt-2 text-slate-500">Pilih tanggal yang tersedia untuk servis motor Anda. Kuota maksimal harian adalah
                5 motor.</p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
            @foreach ($jadwal as $hari)
                @php
                    $jumlah = $hari['jumlah_booking'];

                    if ($jumlah <= 2) {
                        $bgClass = 'bg-green-50 text-green-700 border-green-200';
                    } elseif ($jumlah <= 4) {
                        $bgClass = 'bg-yellow-50 text-yellow-700 border-yellow-200';
                    } else {
                        $bgClass = 'bg-red-50 text-red-700 border-red-200';
                    }
                @endphp

                <div
                    class="{{ $bgClass }} flex flex-col items-center rounded-2xl border p-6 text-center shadow-sm transition hover:shadow-md">
                    <span class="mb-2 text-sm font-semibold uppercase tracking-wider">
                        {{ \Carbon\Carbon::parse($hari['tanggal'])->locale('id')->isoFormat('dddd') }}
                    </span>
                    <span class="mb-4 text-3xl font-bold">
                        {{ \Carbon\Carbon::parse($hari['tanggal'])->format('d M') }}
                    </span>

                    <div class="mb-6">
                        <span class="inline-block rounded-full bg-white/60 px-3 py-1 text-xs font-bold">
                            Sisa: {{ $hari['sisa_kuota'] }} Slot
                        </span>
                    </div>

                    @if ($hari['sisa_kuota'] > 0)
                        <a href="{{ route('landing', ['tanggal' => $hari['tanggal']]) }}#booking"
                            class="mt-auto w-full rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700">Pilih
                            Tanggal Ini</a>
                    @else
                        <button disabled
                            class="mt-auto w-full cursor-not-allowed rounded-xl bg-slate-300 px-4 py-2.5 text-sm font-bold text-slate-500">Penuh</button>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection
