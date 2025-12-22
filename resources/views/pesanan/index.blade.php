@extends('layouts.app')
@section('title', 'Data Pesanan')

@section('contents')

    <!-- Topbar Search-->
    <form action="{{ route('pesanan.search') }}" method="GET"
        class="d-none d-sm-inline-block form-inline mr-auto ml-md-1 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" name="search" class="form-control bg-light border-0 small"
                placeholder="Cari Berdasarkan Nama..." aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- Topbar filter berdasarkan status cucian-->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <form action="{{ route('pesanan.index') }}" method="GET" id="filter_form">
            <div class="form-group">
                <label for="status_filter">Filter berdasarkan Status Laundry:</label>
                <select class="form-control" id="status_filter" name="status_filter">
                    <option value="">Semua</option>
                    @foreach ($statusLaundrys as $status)
                        <option value="{{ $status->id }}">{{ $status->status }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
        <a href="{{ route('pesanan.create') }}" class="btn btn-primary">Tambah Data Pesanan</a>
    </div>

    <hr />
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <div class="table-responsive">
        <table class="table table-hover text-center">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Jenis Layanan</th>
                    <th>Nama Pelanggan</th>
                    <th>Status Laundry</th>
                    <th>Waktu Pesanan Datang</th>
                    <th>Harga</th>
                    <th>Berat</th>
                    <th>Waktu Pesanan Selesai</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($pesanans->count() > 0)
                    @foreach ($pesanans as $index => $pesanan)
                        <tr>
                            <td class="align-middle">
                                {{ ($pesanans->currentPage() - 1) * $pesanans->perPage() + $index + 1 }}</td>
                            <td>{{ $pesanan->jenis_layanan->nama_layanan }}</td>
                            <td>{{ optional($pesanan->pelanggan)->nama_pelanggan }}</td>
                            <td>{{ $pesanan->status_laundry->status }}</td>
                            <td>{{ $pesanan->waktu_pesanan_datang }}</td>
                            <td>{{ $pesanan->total_harga }}</td>
                            <td>{{ $pesanan->berat }}</td>
                            <td>{{ $pesanan->waktu_pesanan_selesai }}</td>
                            <td class="align-middle">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="{{ route('pesanan.show', $pesanan->id) }}"
                                        class="btn btn-secondary">Detail</a>
                                    <a href="{{ route('pesanan.edit', $pesanan->id) }}" class="btn btn-warning">Edit</a>
                                    <form action="{{ route('pesanan.delete', $pesanan->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin Menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="9">Data Kosong</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <!-- Tambahkan link pagination dengan mempertahankan parameter pencarian -->
    <div class="d-flex justify-content-center">
        {{ $pesanans->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
    </div>
@endsection
