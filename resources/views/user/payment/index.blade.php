@extends('components.app')

@section('title', 'Pembayaran Saya')

@section('content')
    <section class="pb-16 pt-10">
        <div class="container mx-auto max-w-6xl px-4">
            <div class="mb-6 page-header">
                <div class="page-header-split">
                    <p class="page-kicker">Payment Center</p>
                    <h1 class="page-title">Pembayaran saya</h1>
                    <p class="page-description">Tagihan servis, status pembayaran, dan nota digital pelanggan.</p>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success mb-6">
                    <div class="font-black">OK</div>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger mb-6">
                    <div class="font-black">!</div>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <div class="mb-6 grid gap-4 md:grid-cols-4">
                <div class="surface-card-tight">
                    <div class="page-kicker">Total Tagihan Aktif</div>
                    <div class="mt-2 text-2xl font-bold text-slate-950">
                        Rp {{ number_format($summary['total_tagihan'], 0, ',', '.') }}
                    </div>
                </div>
                <div class="surface-card-tight">
                    <div class="page-kicker">Belum Bayar</div>
                    <div class="mt-2 text-2xl font-bold text-slate-950">{{ $summary['belum_bayar'] }}</div>
                </div>
                <div class="surface-card-tight">
                    <div class="page-kicker">Menunggu Admin</div>
                    <div class="mt-2 text-2xl font-bold text-slate-950">{{ $summary['menunggu_konfirmasi'] }}</div>
                </div>
                <div class="surface-card-tight">
                    <div class="page-kicker">Lunas</div>
                    <div class="mt-2 text-2xl font-bold text-slate-950">{{ $summary['lunas'] }}</div>
                </div>
            </div>

            @if ($transaksis->isEmpty())
                <section class="empty-state">
                    <div class="empty-state-icon">PY</div>
                    <h3 class="text-2xl font-bold text-slate-950">Belum ada tagihan pembayaran</h3>
                    <p class="max-w-2xl text-sm leading-7 text-slate-500">
                        Tagihan akan muncul setelah admin membuat transaksi dari servis yang sudah selesai.
                    </p>
                    <a href="{{ route('booking.mine') }}" class="btn-primary">Lihat Status Servis</a>
                </section>
            @else
                <div class="space-y-4">
                    @foreach ($transaksis as $transaksi)
                        @php
                            $status = $transaksi->status_pembayaran ?? 'belum_bayar';
                            $statusLabel = [
                                'belum_bayar' => 'Belum Bayar',
                                'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
                                'lunas' => 'Lunas',
                                'gagal' => 'Gagal',
                            ][$status] ?? ucfirst(str_replace('_', ' ', $status));

                            $statusClass = [
                                'belum_bayar' => 'badge-warning',
                                'menunggu_konfirmasi' => 'badge-info',
                                'lunas' => 'badge-success',
                                'gagal' => 'badge-danger',
                            ][$status] ?? 'badge-neutral';

                            $metodeLabel = [
                                'cash' => 'Admin Kasir',
                                'transfer_manual' => 'Transfer Manual',
                                'midtrans' => 'Online Midtrans',
                            ][$transaksi->metode_pembayaran] ?? 'Belum Dipilih';

                            if ($status === 'belum_bayar') {
                                $metodeLabel = 'Belum Dipilih';
                            } elseif ($transaksi->bukti_struk) {
                                $metodeLabel = 'Transfer Manual';
                            }
                        @endphp

                        <article class="surface-card">
                            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                                        <span class="badge badge-info">{{ $metodeLabel }}</span>
                                    </div>

                                    <div class="mt-4 grid gap-4 md:grid-cols-4">
                                        <div>
                                            <div class="page-kicker">Kode</div>
                                            <div class="mt-2 font-bold text-slate-950">{{ $transaksi->kode_transaksi }}</div>
                                        </div>
                                        <div>
                                            <div class="page-kicker">Tanggal</div>
                                            <div class="mt-2 font-semibold text-slate-800">
                                                {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d M Y') }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="page-kicker">Kendaraan</div>
                                            <div class="mt-2 font-semibold text-slate-800">
                                                {{ $transaksi->booking->tipe_motor ?? '-' }}
                                            </div>
                                            <div class="mt-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
                                                {{ $transaksi->booking->plat_nomor ?? 'Tanpa plat' }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="page-kicker">Total</div>
                                            <div class="mt-2 text-xl font-bold text-slate-950">
                                                Rp {{ number_format($transaksi->total_biaya, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-5 rounded-[24px] border border-slate-100 bg-slate-50/80 p-4">
                                        <div class="page-kicker">Rincian Singkat</div>
                                        <div class="mt-3 grid gap-2 text-sm text-slate-600 sm:grid-cols-2">
                                            @foreach ($transaksi->line_items->take(4) as $item)
                                                <div class="flex justify-between gap-3">
                                                    <span>{{ $item['nama'] }} x{{ $item['jumlah'] }}</span>
                                                    <span class="font-semibold text-slate-800">
                                                        Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="flex w-full flex-col gap-3 lg:w-56">
                                    @if ($status === 'belum_bayar')
                                        <a href="{{ route('transaksi.bayar', $transaksi->id) }}"
                                            class="btn-primary inline-flex justify-center">Bayar Online</a>
                                        <a href="{{ route('transaksi.bayar', $transaksi->id) }}"
                                            class="btn-secondary inline-flex justify-center">Upload Struk</a>
                                        <div class="rounded-[20px] border border-slate-100 bg-slate-50/80 p-4 text-xs leading-6 text-slate-500">
                                            Pembayaran langsung akan diperbarui oleh admin dari dashboard kasir.
                                        </div>
                                    @elseif ($status === 'menunggu_konfirmasi')
                                        <a href="{{ route('transaksi.cetak', $transaksi->id) }}"
                                            class="btn-secondary inline-flex justify-center">Nota Sementara</a>
                                        @if ($transaksi->bukti_struk)
                                            <a href="{{ route('transaksi.struk', $transaksi->id) }}"
                                                target="_blank" class="btn-accent inline-flex justify-center">Lihat Struk</a>
                                        @endif
                                    @elseif ($status === 'lunas')
                                        <a href="{{ route('transaksi.cetak', $transaksi->id) }}"
                                            class="btn-primary inline-flex justify-center">Lihat Nota</a>
                                    @else
                                        <a href="{{ route('transaksi.bayar', $transaksi->id) }}"
                                            class="btn-secondary inline-flex justify-center">Coba Bayar Lagi</a>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="surface-card-tight mt-6">
                    {{ $transaksis->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
