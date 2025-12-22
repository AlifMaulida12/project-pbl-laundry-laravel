@extends('layouts.app')

@section('title', 'Tambahkan Pesanan Baru')

@section('contents')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Tambahkan Data Pesanan</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('pesanan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="id_jenis_layanan" class="form-label">Jenis Layanan:</label>
                            <select name="id_jenis_layanan" class="form-select">
                                @foreach ($jenisLayanans as $jenisLayanan)
                                    <option value="{{ $jenisLayanan->id }}">{{ $jenisLayanan->nama_layanan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="id_status_laundry" class="form-label">Status Laundry:</label>
                            <select name="id_status_laundry" class="form-select">
                                @foreach ($statusLaundrys as $statusLaundry)
                                    <option value="{{ $statusLaundry->id }}">{{ $statusLaundry->status }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="id_pelanggan" class="form-label">Pelanggan:</label>
                            <select name="id_pelanggan" class="form-select">
                                @foreach ($pelanggans as $pelanggan)
                                    <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama_pelanggan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="status_pembayaran" class="form-label">Status Pembayaran:</label>
                            <select name="status_pembayaran" class="form-select" required>
                                <option value="belum">Belum Dibayar</option>
                                <option value="dibayar">Sudah Dibayar</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="metode_pengambilan" class="form-label">Pengambilan Cucian:</label>
                            <select name="metode_pengambilan" class="form-select" required>
                                <option value="pickup">Pick Up</option>
                                <option value="dropoff">Drop Off</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                        </div>

                        <div class="col-md-6">
                            <label for="waktu_pesanan_datang" class="form-label">Waktu Pesanan Datang:</label>
                            <input type="date" name="waktu_pesanan_datang" class="form-control"
                                value="{{ old('waktu_pesanan_diambil') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="total_harga" class="form-label">Harga:</label>
                            <input type="number" name="total_harga" class="form-control" placeholder="Harga">
                        </div>

                        <div class="col-md-6">
                            <label for="berat" class="form-label">Berat:</label>
                            <input type="number" name="berat" class="form-control" placeholder="Masukkan Berat"
                                step="0.01" max="20" min="0.01" required>
                        </div>

                        <div class="col-md-6">
                            <label for="estimasi_selesai" class="form-label">Estimasi Pesanan Selesai:</label>
                            <input type="date" name="estimasi_selesai" class="form-control"
                                value="{{ old('estimasi_selesai') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="waktu_pesanan_selesai" class="form-label">Waktu Pesanan Selesai:</label>
                            <input type="date" name="waktu_pesanan_selesai" class="form-control"
                                value="{{ old('waktu_pesanan_selesai') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
