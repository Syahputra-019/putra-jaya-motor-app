<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi - {{ $transaksi->kode_transaksi }}</title>
    <style>
        body {
            margin: 0;
            padding: 32px;
            font-family: Arial, Helvetica, sans-serif;
            background: #eef3fb;
            color: #0f172a;
        }

        .receipt {
            max-width: 440px;
            margin: 0 auto;
            border-radius: 28px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 30px 60px rgba(15, 23, 42, 0.14);
        }

        .receipt-header {
            padding: 28px;
            color: #ffffff;
            background: linear-gradient(145deg, #0d1f3a, #1b447d);
        }

        .receipt-title {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .receipt-subtitle {
            margin-top: 8px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.78);
        }

        .status {
            margin-top: 18px;
            display: inline-block;
            padding: 10px 16px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            background: #f6c54b;
            color: #081427;
        }

        .receipt-body {
            padding: 28px;
        }

        .meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .meta td {
            padding: 6px 0;
            font-size: 14px;
            vertical-align: top;
        }

        .meta td:first-child {
            width: 130px;
            color: #64748b;
        }

        .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 18px 0;
        }

        .section-title {
            margin: 0 0 12px;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #64748b;
        }

        .item {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }

        .item:last-child {
            border-bottom: 0;
        }

        .item-name {
            font-weight: 700;
            color: #0f172a;
        }

        .item-note {
            margin-top: 4px;
            font-size: 12px;
            color: #64748b;
        }

        .total {
            display: flex;
            justify-content: space-between;
            margin-top: 18px;
            padding-top: 18px;
            border-top: 2px solid #0d1f3a;
            font-size: 18px;
            font-weight: 800;
        }

        .footer {
            margin-top: 22px;
            border-radius: 20px;
            background: #f8fafc;
            padding: 16px;
            text-align: center;
            font-size: 13px;
            line-height: 1.7;
            color: #475569;
        }

        .actions {
            margin-top: 22px;
            text-align: center;
        }

        .actions a,
        .actions button {
            display: inline-block;
            padding: 10px 18px;
            border: 0;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 700;
            cursor: pointer;
            color: #ffffff;
            background: #0d1f3a;
        }

        .actions a {
            margin-left: 10px;
            background: #475569;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }

            .receipt {
                box-shadow: none;
                max-width: none;
                border-radius: 0;
            }

            .actions {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="receipt">
        <div class="receipt-header">
            <h1 class="receipt-title">Putra Jaya Motor</h1>
            <div class="receipt-subtitle">Nota servis dan transaksi pelanggan</div>
            <div class="status">
                @if ($transaksi->status_pembayaran === 'lunas')
                    Lunas
                @elseif($transaksi->status_pembayaran === 'menunggu_konfirmasi')
                    Menunggu Konfirmasi
                @else
                    Belum Bayar
                @endif
            </div>
        </div>

        <div class="receipt-body">
            <table class="meta">
                <tr>
                    <td>Kode</td>
                    <td>{{ $transaksi->kode_transaksi }}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d M Y') }}</td>
                </tr>
                <tr>
                    <td>Pelanggan</td>
                    <td>{{ $transaksi->pelanggan->nama_pelanggan ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Mekanik</td>
                    <td>{{ $transaksi->mekanik->nama_mekanik ?? '-' }}</td>
                </tr>
            </table>

            <div class="divider"></div>

            <h2 class="section-title">Rincian Transaksi</h2>

            @if ($transaksi->service)
                <div class="item">
                    <div>
                        <div class="item-name">{{ $transaksi->service->nama_service }}</div>
                        <div class="item-note">Jasa servis</div>
                    </div>
                    <div>Rp {{ number_format($transaksi->service->harga, 0, ',', '.') }}</div>
                </div>
            @endif

            @foreach ($transaksi->detailTransaksis as $detail)
                <div class="item">
                    <div>
                        <div class="item-name">{{ $detail->sparepart->nama_sparepart }}</div>
                        <div class="item-note">{{ $detail->jumlah }} x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</div>
                    </div>
                    <div>Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</div>
                </div>
            @endforeach

            <div class="total">
                <span>Total Bayar</span>
                <span>Rp {{ number_format($transaksi->total_biaya, 0, ',', '.') }}</span>
            </div>

            <div class="footer">
                Terima kasih telah menggunakan layanan Putra Jaya Motor.<br>
                Simpan nota ini sebagai bukti transaksi Anda.
            </div>

            <div class="actions">
                <button onclick="window.print()">Print</button>
                @if (auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('transaksi.index') }}">Kembali</a>
                @elseif(auth()->check() && auth()->user()->role === 'pelanggan')
                    <a href="{{ route('booking.mine') }}">Kembali</a>
                @else
                    <a href="{{ route('landing') }}">Kembali</a>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
