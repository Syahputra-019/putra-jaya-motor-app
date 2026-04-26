<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan - {{ $judulFilter }}</title>
    <style>
        body {
            margin: 0;
            padding: 32px;
            font-family: Arial, Helvetica, sans-serif;
            color: #0f172a;
            background: #eef3fb;
        }

        .report {
            max-width: 1100px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(15, 23, 42, 0.12);
        }

        .report-header {
            padding: 32px;
            color: #ffffff;
            background: linear-gradient(145deg, #0d1f3a, #1b447d);
        }

        .report-header h1 {
            margin: 0;
            font-size: 30px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .report-header p {
            margin: 10px 0 0;
            color: rgba(255, 255, 255, 0.76);
        }

        .report-body {
            padding: 28px 32px 32px;
        }

        .summary {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .summary-card {
            flex: 1 1 240px;
            padding: 18px 20px;
            border-radius: 22px;
            background: #f8fafc;
        }

        .summary-label {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #64748b;
        }

        .summary-value {
            margin-top: 8px;
            font-size: 24px;
            font-weight: 800;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 14px 12px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
            font-size: 14px;
        }

        th {
            font-size: 12px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #64748b;
            background: #f8fafc;
        }

        .text-right {
            text-align: right;
        }

        .actions {
            margin-top: 24px;
            text-align: center;
        }

        .actions a,
        .actions button {
            display: inline-block;
            margin: 0 6px;
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
            background: #475569;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }

            .report {
                max-width: none;
                border-radius: 0;
                box-shadow: none;
            }

            .actions {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="report">
        <div class="report-header">
            <h1>Laporan Pendapatan Bengkel</h1>
            <p>Periode {{ $judulFilter }} - dicetak pada {{ date('d M Y H:i') }}</p>
        </div>

        <div class="report-body">
            <div class="summary">
                <div class="summary-card">
                    <div class="summary-label">Periode Laporan</div>
                    <div class="summary-value">{{ $judulFilter }}</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Total Pendapatan</div>
                    <div class="summary-value">Rp {{ number_format($pendapatanTotal, 0, ',', '.') }}</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Jumlah Transaksi</div>
                    <div class="summary-value">{{ $transaksi->count() }}</div>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
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
                            <td>{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                            <td>{{ $item->kode_transaksi }}</td>
                            <td>{{ $item->pelanggan->nama_pelanggan }}</td>
                            <td>{{ $item->mekanik->nama_mekanik }}</td>
                            <td class="text-right">Rp {{ number_format($item->total_biaya, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #64748b;">Tidak ada data transaksi lunas pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="actions">
                <button onclick="window.print()">Print</button>
                <a href="{{ route('dashboard') }}">Kembali</a>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>
