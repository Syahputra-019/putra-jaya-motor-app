@extends('components.app')

@section('title', 'Riwayat Komplain')

@section('content')
    <section class="py-10">
        <div class="container mx-auto max-w-5xl px-4">
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="page-kicker">After Service Support</p>
                    <h1 class="mt-2 text-3xl font-bold text-slate-950">Riwayat komplain Anda</h1>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Pantau semua komplain pasca-servis dan lihat balasan
                        terbaru dari bengkel di satu halaman.</p>
                </div>
                <a href="{{ route('komplain.create') }}" class="btn-primary">Buat Komplain Baru</a>
            </div>

            @if (session('success'))
                <div class="alert alert-success mb-6">
                    <div class="font-black">OK</div>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            <div class="space-y-6">
                @forelse($komplains as $item)
                    <article class="rounded-[28px] border border-slate-100 bg-white p-6 shadow-sm shadow-slate-200/60">
                        <div class="flex flex-col gap-4 border-b border-slate-100 pb-5 sm:flex-row sm:justify-between">
                            <div>
                                <div class="text-xs font-bold uppercase tracking-[0.22em] text-slate-400">Tanggal
                                    Pengajuan</div>
                                <p class="mt-2 font-semibold text-slate-800">
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d F Y, H:i') }}
                                </p>
                                <p class="mt-1 text-sm font-semibold text-blue-600">
                                    Servis: {{ $item->booking->tipe_motor ?? 'Motor Servis' }}
                                </p>
                            </div>

                            <div>
                                @if ($item->status === 'menunggu')
                                    <span
                                        class="inline-flex rounded-full bg-rose-100 px-3 py-1 text-xs font-bold text-rose-600">
                                        Menunggu Respon
                                    </span>
                                @elseif($item->status === 'diproses')
                                    <span
                                        class="inline-flex rounded-full bg-orange-100 px-3 py-1 text-xs font-bold text-orange-600">
                                        Diproses
                                    </span>
                                @else
                                    <span
                                        class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-600">
                                        Selesai
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-5">
                            <div class="mb-2 text-xs font-bold uppercase tracking-[0.22em] text-slate-400">Keluhan Anda
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4 text-sm leading-7 text-slate-700">
                                {{ $item->deskripsi_komplain }}
                            </div>
                        </div>

                        @if ($item->tanggapan_bengkel)
                            <div class="mt-5 rounded-2xl border border-blue-100 bg-blue-50 p-4">
                                <div class="mb-2 text-xs font-bold uppercase tracking-[0.22em] text-blue-500">Balasan
                                    Bengkel</div>
                                <p class="text-sm font-medium leading-7 text-slate-800">
                                    {{ $item->tanggapan_bengkel }}
                                </p>
                            </div>
                        @else
                            <div class="mt-5 text-sm italic text-slate-400">
                                Belum ada tanggapan dari bengkel. Mohon tunggu sebentar ya.
                            </div>
                        @endif
                    </article>
                @empty
                    <div class="rounded-[28px] border border-dashed border-slate-300 bg-white py-14 text-center shadow-sm">
                        <h3 class="text-xl font-bold text-slate-500">Belum ada riwayat komplain</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-400">Kalau ada kendala pasca-servis, Anda bisa kirim
                            komplain dari halaman ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
