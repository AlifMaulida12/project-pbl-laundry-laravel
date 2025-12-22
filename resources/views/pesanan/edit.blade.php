@extends('layouts.app')

@section('title', 'Update Data Pesanan')

@section('contents')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Update Data Pesanan</h1>

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
                <form action="{{ route('pesanan.update', ['id' => $pesanan->id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="id_jenis_layanan" class="form-label">Jenis Layanan:</label>
                            <select name="id_jenis_layanan" class="form-select">
                                @foreach ($jenisLayanans as $jenisLayanan)
                                    <option value="{{ $jenisLayanan->id }}"
                                        {{ $pesanan->id_jenis_layanan == $jenisLayanan->id ? 'selected' : '' }}>
                                        {{ $jenisLayanan->nama_layanan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="id_status_laundry" class="form-label">Status Laundry:</label>
                            <select name="id_status_laundry" class="form-select">
                                @foreach ($statusLaundrys as $statusLaundry)
                                    <option value="{{ $statusLaundry->id }}"
                                        {{ $pesanan->id_status_laundry == $statusLaundry->id ? 'selected' : '' }}>
                                        {{ $statusLaundry->status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="id_pelanggan" class="form-label">Pelanggan:</label>
                            <select name="id_pelanggan" class="form-select">
                                @foreach ($pelanggans as $pelanggan)
                                    <option value="{{ $pelanggan->id }}"
                                        {{ $pesanan->id_pelanggan == $pelanggan->id ? 'selected' : '' }}>
                                        {{ $pelanggan->nama_pelanggan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="status_pembayaran" class="form-label">Status Pembayaran:</label>
                            <select name="status_pembayaran" class="form-select" required>
                                <option value="belum" {{ $pesanan->status_pembayaran == 'belum' ? 'selected' : '' }}>Belum
                                    Dibayar</option>
                                <option value="dibayar" {{ $pesanan->status_pembayaran == 'dibayar' ? 'selected' : '' }}>
                                    Sudah Dibayar</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="metode_pengambilan" class="form-label">Pengambilan Cucian:</label>
                            <select name="metode_pengambilan" class="form-select" required>
                                <option value="pickup" {{ $pesanan->metode_pengambilan == 'pickup' ? 'selected' : '' }}>
                                    Pick Up</option>
                                <option value="dropoff" {{ $pesanan->metode_pengambilan == 'dropoff' ? 'selected' : '' }}>
                                    Drop Off</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                        </div>

                        <div class="col-md-6">
                            <label for="waktu_pesanan_datang">Waktu Pesanan Datang:</label>
                            <input type="datetime-local" id="waktu_pesanan_datang" class="form-control"
                                name="waktu_pesanan_datang" value="{{ $pesanan->waktu_pesanan_datang }}">
                        </div>

                        <div class="col-md-6">
                            <label for="total_harga" class="form-label">Harga:</label>
                            <input type="number" name="total_harga" class="form-control" placeholder="Masukkan Harga"
                                value="{{ $pesanan->total_harga }}">
                        </div>

                        <div class="col-md-6">
                            <label for="berat" class="form-label">Berat:</label>
                            <input type="number" name="berat" class="form-control" placeholder="Masukkan Berat"
                                step="0.01" max="20" min="0.01" required value="{{ $pesanan->berat }}">
                        </div>

                        <div class="col-md-6">
                            <label for="estimasi_selesai" class="form-label">Estimasi Pesanan Selesai:</label>
                            <input type="datetime-local" name="estimasi_selesai" class="form-control"
                                value="{{ $pesanan->estimasi_selesai }}">
                        </div>

                        <div class="col-md-6">
                            <label for="waktu_pesanan_selesai" class="form-label">Waktu Pesanan Selesai:</label>
                            <input type="datetime-local" name="waktu_pesanan_selesai" class="form-control"
                                value="{{ $pesanan->waktu_pesanan_selesai }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
