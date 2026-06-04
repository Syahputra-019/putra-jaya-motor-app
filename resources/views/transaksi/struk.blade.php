@extends('components.app')

@section('title', 'Struk Pembayaran')

@section('content')
    <section class="pb-16 pt-10">
        <div class="container mx-auto max-w-4xl px-4">
            <div class="page-header">
                <div class="page-header-split">
                    <p class="page-kicker">Payment Receipt</p>
                    <h1 class="page-title">Struk pembayaran</h1>
                    <p class="page-description">Struk transaksi <span class="font-bold text-[color:var(--brand-navy-800)]">{{ $transaksi->kode_transaksi }}</span>.</p>
                </div>
            </div>

            <div class="surface-card">
                <div class="rounded-[24px] border border-slate-100 bg-slate-50/80 p-4">
                    <img
                        src="data:{{ $mimeType }};base64,{{ $base64 }}"
                        alt="Struk pembayaran {{ $transaksi->kode_transaksi }}"
                        class="mx-auto max-h-[75vh] w-auto rounded-[20px] object-contain shadow-lg"
                    >
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('transaksi.cetak', $transaksi->id) }}" class="btn-secondary">Lihat Nota</a>
                    <a href="{{ route('transaksi.struk', $transaksi->id) }}" target="_blank" class="btn-primary">Buka di Tab Baru</a>
                </div>
            </div>
        </div>
    </section>
@endsection
