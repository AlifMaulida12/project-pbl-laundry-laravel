@extends('layouts.app')
@section('title', 'Edit Data')
@section('contents')
    <h1 class="mb-0">Edit Data Pelanggan</h1>
    <hr />
    <form action="{{ route('pelanggan.update', $pelanggan->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row mb-3">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="nama_pelanggan" class="form-control" placeholder="Nama"
                    value="{{ $pelanggan->nama_pelanggan }}" minlength="3" maxlength="30" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Alamat</label>
                <input type="text" name="alamat_pelanggan" class="form-control" placeholder="Alamat"
                    value="{{ $pelanggan->alamat_pelanggan }}" minlength="10" maxlength="30" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input type="text" name="email" class="form-control" placeholder="Email"
                    value="{{ $pelanggan->email }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Password (Opsional)</label>
                <input type="password" name="password" class="form-control" placeholder="Password Baru">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control"
                    placeholder="Konfirmasi Ulang Password Baru">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Nomor Hp</label>
                <input type="text" name="nomor_hp" class="form-control" placeholder="Nomor Hp"
                    value="{{ $pelanggan->nomor_hp }}" pattern="^\+?[0-9]{10,13}$"
                    title="Nomor HP hanya boleh mengandung angka dan simbol &#43; (minimal 10 digit, maksimal 13 digit)"
                    minlength="10" maxlength="13" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Level (Opsional)</label>
                <input type="text" name="level" class="form-control" placeholder="Level Pengguna"
                    value="{{ $pelanggan->level }}">
            </div>
        </div>

        <div class="row">
            <div class="d-grid">
                <button type="submit" class="btn btn-warning">Update</button>
            </div>
        </div>
    </form>
@endsection
