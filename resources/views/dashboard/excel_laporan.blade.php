<table>
    <thead>
        <tr>
            <th colspan="6" style="text-align: center; font-weight: bold;">LAPORAN PENDAPATAN BENGKEL</th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center;">Periode: {{ $judulFilter }}</th>
        </tr>
        <tr>
            <th colspan="6"></th> </tr>
        <tr>
            <th style="font-weight: bold; border: 1px solid #000;">No</th>
            <th style="font-weight: bold; border: 1px solid #000;">Tanggal</th>
            <th style="font-weight: bold; border: 1px solid #000;">Kode Transaksi</th>
            <th style="font-weight: bold; border: 1px solid #000;">Pelanggan</th>
            <th style="font-weight: bold; border: 1px solid #000;">Mekanik</th>
            <th style="font-weight: bold; border: 1px solid #000;">Total Biaya</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transaksi as $index => $item)
            <tr>
                <td style="border: 1px solid #000;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000;">{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                <td style="border: 1px solid #000;">{{ $item->kode_transaksi }}</td>
                <td style="border: 1px solid #000;">{{ $item->pelanggan->nama_pelanggan }}</td>
                <td style="border: 1px solid #000;">{{ $item->mekanik->nama_mekanik }}</td>
                <td style="border: 1px solid #000;">{{ $item->total_biaya }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="5" style="font-weight: bold; text-align: right; border: 1px solid #000;">TOTAL PENDAPATAN</td>
            <td style="font-weight: bold; border: 1px solid #000;">{{ $pendapatanTotal }}</td>
        </tr>
    </tbody>
</table>