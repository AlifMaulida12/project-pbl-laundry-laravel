@extends('layouts.app')

@section('title', 'Tambahkan Data Pelanggan')

@section('contents')
    <h1 class="mb-4">Masukkan Data Pelanggan</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pelanggan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <div class="col-md-6 mb-3">
                <input type="text" name="nama_pelanggan" class="form-control" placeholder="Nama" minlength="3"
                    maxlength="30" required>
            </div>

            <div class="col-md-6 mb-3">
                <input type="text" name="alamat_pelanggan" class="form-control" placeholder="Alamat" minlength="10"
                    maxlength="30" required>
            </div>

            <div class="col-md-6 mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email"
                    pattern="[a-zA-Z0-9._%+-]+@gmail\.com" title="Hanya alamat email dengan domain @gmail.com yang valid"
                    required>
            </div>

            <div class="col-md-6 mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <div class="col-md-6 mb-3">
                <input type="text" name="nomor_hp" class="form-control" placeholder="Nomor Hp"
                    pattern="^\+?[0-9]{10,13}$"
                    title="Nomor HP hanya boleh mengandung angka dan simbol &#43; (minimal 10 digit, maksimal 13 digit)"
                    minlength="10" maxlength="13" required>
            </div>


            <div class="col-md-6 mb-3">
                <input type="password" name="password_confirmation" class="form-control"
                    placeholder="Konfirmasi Ulang Password" required>
            </div>

            <div class="col-md-6 mb-3">
                <input type="text" name="level" class="form-control"
                    placeholder="Level Pengguna (Opsional Admin atau guest)" minlength="5" maxlength="5"
                    pattern="[A-Za-z]+" title="Hanya huruf yang diperbolehkan">
            </div>

        </div>

        <div class="row">
            <div class="col">
                <button type="submit" class="btn btn-primary">Tambahkan Data</button>
            </div>
        </div>
    </form>
@endsection
