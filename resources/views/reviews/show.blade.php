@extends('layouts.app')
@section('title', 'Data Detail')
@section('contents')
    <h1 class="mb-3">Detail Pelanggan</h1>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama_pelanggan" class="form-control" value="{{ $review->pelanggan->nama_pelanggan }}"
                readonly>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Jenis Layanan</label>
            <input type="text" name="alamat" class="form-control"
                value="{{ $review->pesanan->jenis_layanan->nama_layanan ?? 'N/A' }}" readonly>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Rating</label>
            <input type="text" name="email" class="form-control" value="{{ $review->rating }}" readonly>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Ulasan Pelanggan</label>
            <input type="text" name="nomor_hp" class="form-control" value="{{ $review->review }}" readonly>
        </div>
    </div>
@endsection
