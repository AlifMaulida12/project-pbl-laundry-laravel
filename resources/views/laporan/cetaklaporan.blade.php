<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        table.static {
            position: relative;
            border: 1px solid #543535;
        }
    </style>
    <title>CETAK LAPORAN</title>
</head>

<body>
    <div class="form-group">
        <p align="center"><b>LAPORAN PENDAPATAN {{ $selectedMonthName ?? '' }}</b></p>
        <table class="static" align="center" rules="all" border="1px" style="width: 95%;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pelanggan</th>
                    <th>Jenis Layanan</th>
                    <th>Jumlah Pesanan</th>
                    <th>Berat (kg)</th>
                    <th>Harga Layanan</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cetaklaporanPendapatanBulanan as $index => $laporan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $laporan->nama }}</td>
                        <td>{{ $laporan->nama_layanan }}</td>
                        <td>{{ $laporan->jumlah_pesanan }}</td>
                        <td>{{ $laporan->total_berat }}</td>
                        <td>Rp {{ number_format($laporan->harga) }}</td>
                        <td>Rp {{ number_format($laporan->total_harga) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="6"><strong>Total Keseluruhan</strong></td>
                    <td><strong>Rp {{ number_format($cetaklaporanPendapatanBulanan->sum('total_harga')) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <script type="text/javascript">
        window.print();
    </script>
</body>

</html>
