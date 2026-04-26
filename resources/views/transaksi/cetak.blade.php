<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi - {{ $transaksi->kode_transaksi }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            /* Font khas struk kasir */
            color: #000;
            background: #fff;
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }

        .struk-container {
            width: 80mm;
            /* Lebar standar kertas thermal */
            margin: 0 auto;
            border: 1px dashed #000;
            padding: 15px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .divider {
            border-bottom: 1px dashed #000;
            margin: 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 2px 0;
            vertical-align: top;
        }

        /* Hilangkan elemen yang tidak perlu saat di-print */
        @media print {
            body {
                padding: 0;
            }

            .struk-container {
                border: none;
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="struk-container">
        <div class="text-center">
            <h2 style="margin:0;">BENGKEL MOTOR TA</h2>
            <div class="mb-6">
                @if ($transaksi->status_pembayaran == 'lunas')
                    <div
                        class="rounded-lg border-2 border-green-400 bg-green-100 px-4 py-3 text-center text-xl font-bold uppercase tracking-widest text-green-700 shadow-sm">
                        ✅ Lunas
                    </div>
                @elseif($transaksi->status_pembayaran == 'menunggu_konfirmasi')
                    <div
                        class="rounded-lg border-2 border-yellow-400 bg-yellow-100 px-4 py-3 text-center text-lg font-bold text-yellow-700 shadow-sm">
                        ⏳ Menunggu Konfirmasi Admin
                    </div>
                @else
                    <div
                        class="rounded-lg border-2 border-red-400 bg-red-100 px-4 py-3 text-center text-lg font-bold text-red-700 shadow-sm">
                        ❌ Belum Bayar
                    </div>
                @endif
            </div>
            <p style="margin:5px 0 0 0;">Jl. Kampus Merdeka No. 123</p>
            <p style="margin:0;">Telp: 0812-3456-7890</p>
        </div>

        <div class="divider"></div>

        <table>
            <tr>
                <td>Kode</td>
                <td>: {{ $transaksi->kode_transaksi }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>: {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>Pelanggan</td>
                <td>: {{ $transaksi->pelanggan->nama }}</td>
            </tr>
            <tr>
                <td>Mekanik</td>
                <td>: {{ $transaksi->mekanik->nama }}</td>
            </tr>
        </table>

        <div class="divider"></div>
        <div class="font-bold">Rincian Sparepart:</div>
        <table>
            @if ($transaksi->service)
                <tr>
                    <td colspan="2">JASA: {{ $transaksi->service->nama_servis }}</td>
                </tr>
                <tr>
                    <td>1 x {{ number_format($transaksi->service->harga, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($transaksi->service->harga, 0, ',', '.') }}</td>
                </tr>
            @endif

            @foreach ($transaksi->detailTransaksis as $detail)
                <tr>
                    <td colspan="2">PART: {{ $detail->sparepart->nama_sparepart }}</td>
                </tr>
                <tr>
                    <td>{{ $detail->jumlah }} x {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </table>

        <div class="divider"></div>

        <table>
            <tr class="font-bold">
                <td>TOTAL BIAYA</td>
                <td class="text-right">Rp {{ number_format($transaksi->total_biaya, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <div class="text-center" style="margin-top: 15px;">
            <p>Terima Kasih Atas Kunjungan Anda!</p>
            <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
        </div>

        <div class="no-print mt-4 text-center" style="margin-top: 20px;">
            <a href="{{ route('transaksi.index') }}"
                style="padding: 8px 15px; background: #3b82f6; color: white; text-decoration: none; border-radius: 5px;">Kembali
                ke Transaksi</a>
        </div>
    </div>

</body>

</html>
