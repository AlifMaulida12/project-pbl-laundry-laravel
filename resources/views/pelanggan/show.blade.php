@extends('layouts.app')
@section('title', 'Data Detail')
@section('contents')
    <h1 class="mb-3">Detail Pelanggan</h1>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama_pelanggan" class="form-control" value="{{ $pelanggan->nama_pelanggan }}" readonly>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Alamat</label>
            <input type="text" name="alamat" class="form-control" value="{{ $pelanggan->alamat_pelanggan }}" readonly>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Email</label>
            <input type="text" name="email" class="form-control" value="{{ $pelanggan->email }}" readonly>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Nomor Hp</label>
            <input type="text" name="nomor_hp" class="form-control" value="{{ $pelanggan->nomor_hp }}" readonly>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Level Pengguna</label>
            <input type="text" name="level" class="form-control" value="{{ $pelanggan->level }}" readonly>
        </div>
    </div>
@endsection
