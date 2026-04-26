<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan - {{ $judulFilter }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            color: #333;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px double #000;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            text-transform: uppercase;
        }

        .info {
            margin-bottom: 20px;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        th {
            background-color: #f2f2f2;
            text-transform: uppercase;
        }

        .text-right {
            text-align: right;
        }

        .total-section {
            font-weight: bold;
            font-size: 16px;
            margin-top: 20px;
            text-align: right;
        }

        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 12px;
        }

        .signature {
            margin-top: 80px;
            font-weight: bold;
            text-decoration: underline;
        }

        /* CSS biar pas diprint rapi */
        @media print {
            .no-print {
                display: none;
            }

            body {
                margin: 0;
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>LAPORAN PENDAPATAN BENGKEL</h1>
        <p>Alamat Bengkel Ortu Lu (Isi Sendiri Bro) | Telp: 0812xxxxxx</p>
    </div>

    <div class="info">
        <strong>Periode Laporan:</strong> {{ $judulFilter }} <br>
        <strong>Dicetak Pada:</strong> {{ date('d/m/Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tgl</th>
                <th>Kode</th>
                <th>Pelanggan</th>
                <th>Mekanik</th>
                <th class="text-right">Total Biaya</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksi as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ date('d/m/y', strtotime($item->tanggal)) }}</td>
                    <td>{{ $item->kode_transaksi }}</td>
                    <td>{{ $item->pelanggan->nama_pelanggan }}</td>
                    <td>{{ $item->mekanik->nama_mekanik }}</td>
                    <td class="text-right">Rp {{ number_format($item->total_biaya, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data transaksi lunas pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total-section">
        TOTAL PENDAPATAN: Rp {{ number_format($pendapatanTotal, 0, ',', '.') }}
    </div>

    <div class="footer">
        Dicetak oleh sistem pada {{ date('d F Y') }} <br>
        <div class="signature">
            (
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            )
        </div>
        <p>Owner Bengkel</p>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()"
            style="padding: 10px 20px; background: #4f46e5; color: #fff; border: none; border-radius: 5px; cursor: pointer;">Print
            Sekarang</button>
        <a href="{{ route('dashboard') }}"
            style="padding: 10px 20px; background: #6b7280; color: #fff; text-decoration: none; border-radius: 5px; margin-left: 10px;">Kembali</a>
    </div>

    <script>
        // Otomatis buka dialog print pas halaman kelar loading
        window.onload = function() {
            window.print();
        };
    </script>

</body>

</html>
