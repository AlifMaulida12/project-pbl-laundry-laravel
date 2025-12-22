@extends('layouts.app')

@section('title', 'LAUNDRY')

@section('contents')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Data Pelanggan</h5>
                    <p class="card-text">{{ $totalPelangganSelesai }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Data Pesanan</h5>
                    <p class="card-text">{{ $totalPesananSelesai }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Data Pelanggan Belum Selesai</h5>
                    <p class="card-text">{{ $totalPelangganBelumSelesai }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Data Pesanan Belum Selesai</h5>
                    <p class="card-text">{{ $totalPesananBelumSelesai }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pendapatan {{ $selectedMonth == 0 ? 'Tahun' : 'Bulan' }}
                        {{ $selectedMonth == 0 ? $year : date('F', mktime(0, 0, 0, $selectedMonth, 1)) . ' ' . $year }}</h5>
                    <p class="card-text">Rp {{ number_format($pendapatan) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <form action="{{ route('dashboard') }}" method="GET">
                <div class="form-group">
                    <label for="year">Pilih Tahun:</label>
                    <select name="year" id="year" class="form-control">
                        @for ($i = date('Y'); $i >= 2000; $i--)
                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="form-group">
                    <label for="month">Pilih Bulan:</label>
                    <select name="month" id="month" class="form-control">
                        <option value="0" {{ $selectedMonth == 0 ? 'selected' : '' }}>Semua</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $selectedMonth == $i ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Tampilkan</button>
                <a href="{{ route('dashboard.cetak-laporan', ['month' => request('month'), 'year' => request('year')]) }}"
                    class="btn btn-success ms-3" target="_blank">
                    Cetak Laporan
                </a>
            </form>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <h4>Laporan Pendapatan {{ $selectedMonth == 0 ? 'Tahun' : 'Bulan' }}
                {{ $selectedMonth == 0 ? $year : date('F', mktime(0, 0, 0, $selectedMonth, 1)) . ' ' . $year }}</h4>
            <table class="table table-bordered">
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
                    @foreach ($laporanPendapatanBulanan as $index => $laporan)
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
                    <!-- Total Keseluruhan -->
                    @if ($laporanPendapatanBulanan->isNotEmpty())
                        <tr>
                            <td colspan="6"><strong>Total Keseluruhan</strong></td>
                            <td><strong>Rp {{ number_format($laporanPendapatanBulanan->sum('total_harga')) }}</strong></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection
