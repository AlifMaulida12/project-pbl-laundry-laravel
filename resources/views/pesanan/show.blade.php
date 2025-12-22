@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('contents')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Detail Pesanan</h1>
        <hr />

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="jenis_layanan" class="form-label">Jenis Layanan</label>
                <input type="text" id="jenis_layanan" name="jenis_layanan" class="form-control"
                    value="{{ $pesanan->jenis_layanan->nama_layanan }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
                <input type="text" id="nama_pelanggan" name="nama_pelanggan" class="form-control"
                    value="{{ $pesanan->pelanggan->nama_pelanggan }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label for="alamat_pelanggan" class="form-label">Alamat Pelanggan</label>
                <input type="text" id="alamat_pelanggan" name="alamat_pelanggan" class="form-control"
                    value="{{ $pesanan->pelanggan->alamat_pelanggan }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label for="email_pelanggan" class="form-label">Email Pelanggan</label>
                <input type="text" id="email_pelanggan" name="email_pelanggan" class="form-control"
                    value="{{ $pesanan->pelanggan->email }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label for="nomor_hp" class="form-label">Nomor Hp Pelanggan</label>
                <input type="text" id="nomor_hp" name="nomor_hp" class="form-control"
                    value="{{ $pesanan->pelanggan->nomor_hp }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label for="status_laundry" class="form-label">Status Laundry</label>
                <input type="text" id="status_laundry" name="status_laundry" class="form-control"
                    value="{{ $pesanan->status_laundry->status }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label for="berat" class="form-label">Berat</label>
                <input type="text" id="berat" name="berat" class="form-control" value="{{ $pesanan->berat }}"
                    readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="text" id="harga" name="harga" class="form-control" value="{{ $pesanan->total_harga }}"
                    readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label for="diskon" class="abel">Metode Pengambilan Cucian</label>
                <input type="text" id="metode_pengambilan" name="metode_pengambilan" class="form-control"
                    value="{{ $pesanan->metode_pengambilan }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label for="status_pembayaran" class="abel">Status Pembayaran</label>
                <input type="text" id="status_pembayaran" name="status_pembayaran" class="form-control"
                    value="{{ $pesanan->status_pembayaran }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label for="waktu_pesanan_diambil" class="form-label">Waktu Pesanan Datang</label>
                <input type="text" id="waktu_pesanan_diambil" name="waktu_pesanan_diambil" class="form-control"
                    value="{{ $pesanan->waktu_pesanan_datang }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label for="estimasi_selesai" class="form-label">Estimasi Pesanan Selesai</label>
                <input type="text" id="estimasi_selesai" name="estimasi_selesai" class="form-control"
                    value="{{ $pesanan->estimasi_selesai }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label for="waktu_pesanan_selesai" class="form-label">Waktu Pesanan Selesai</label>
                <input type="text" id="waktu_pesanan_selesai" name="waktu_pesanan_selesai" class="form-control"
                    value="{{ $pesanan->waktu_pesanan_selesai }}" readonly>
            </div>
        </div>
    </div>
@endsection
