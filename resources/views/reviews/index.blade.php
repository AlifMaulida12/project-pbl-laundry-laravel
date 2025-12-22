@extends('layouts.app')
@section('title', 'Data Review Pesanan')

@section('contents')

    <!-- Topbar Search -->
    <form action="{{ route('reviews.search') }}" method="GET"
        class="d-none d-sm-inline-block form-inline mr-auto ml-md-1 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" name="search" class="form-control bg-light border-0 small"
                placeholder="Cari Berdasarkan Nama Pelanggan..." aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- Filter untuk Review Pelanggan -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <form action="{{ route('reviews.index') }}" method="GET" id="filter_form">
            <div class="form-group">
                <label for="jenis_layanan_filter">Filter berdasarkan Jenis Layanan:</label>
                <select class="form-control" id="jenis_layanan_filter" name="jenis_layanan_filter">
                    <option value="">Semua</option>
                    @foreach ($jenisLayanans as $layanan)
                        <option value="{{ $layanan->id }}">{{ $layanan->nama_layanan }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    </div>
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
                    <th>Nama Pelanggan</th>
                    <th>Jenis Layanan</th>
                    <th>Rating</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($reviews->count() > 0)
                    @foreach ($reviews as $index => $review)
                        <tr>
                            <td class="align-middle">{{ $reviews->firstItem() + $index }}</td>
                            <td>{{ $review->pelanggan->nama_pelanggan }}</td>
                            <td>{{ $review->pesanan->jenis_layanan->nama_layanan ?? 'N/A' }}</td>
                            <td>
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $review->rating)
                                        ★
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </td>
                            <td class="align-middle">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="{{ route('reviews.show', $review->id) }}" class="btn btn-secondary">Lihat</a>
                                    <form action="{{ route('reviews.delete', $review->id) }}" method="POST"
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
                        <td class="text-center" colspan="5">Data Kosong</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Tambahkan link pagination dengan mempertahankan parameter pencarian -->
    <div class="d-flex justify-content-center">
        {{ $reviews->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
    </div>

@endsection
